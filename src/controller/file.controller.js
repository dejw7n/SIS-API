const uploadFile = require("../middleware/upload");
const fs = require("fs");
var mime = require("mime-types");
var path = require("path");
var config = require("../middleware/config");
var db = require("../db");
var nconf = require("nconf");

const getFileSizeLimit = (req, res) => {
	res.status(200).send({
		message: config.get("file_size_limit"),
	});
};

const directoryPath = config.get("file_base_dir");
const baseUrl = config.get("base_url");

global.numberFilesUploaded = 0;
global.selectedFilesUuid = [];
const upload = async (req, res) => {
	try {
		await uploadFile(req, res);
		res.status(200).send({
			success: true,
		});
		if (req.file == undefined) {
			return res.status(400).send({ message: "Please upload a file!" });
		}
		processUploadedFile(req.file, global.selectedFilesUuid[global.numberFilesUploaded], req.body.deferUuid);
		if (req.body.fileAmount == global.numberFilesUploaded) {
			global.numberFilesUploaded = 0;
			global.selectedFilesUuid = [];
		} else {
			global.numberFilesUploaded++;
		}
	} catch (err) {
		console.log(err);

		if (err.code == "LIMIT_FILE_SIZE") {
			return res.status(500).send({
				success: false,
				message: "File size cannot be larger than " + nconf.get("file_size_limit") + " byte!",
			});
		}

		res.status(500).send({
			message: `${req.file.originalname}: Could not upload the file. ${err}`,
		});
	}
};
const getFileInfoById = async (req, res) => {
	let respond = await db.asyncQuery(`SELECT * FROM files WHERE id=${db.pool.escape(req.params.id)}`);
	return res.status(200).send(respond[0]);
};
const getFileByDeferUuid = async (req, res) => {
	let respond = await db.asyncQuery(`SELECT files.id, files.uuid, files.type, files.size, files.upload_date, files.name FROM files_deferred JOIN files ON files.id = files_deferred.file_id WHERE defer_uuid=${db.pool.escape(req.params.deferUuid)}`);
	let respond2 = db.asyncQuery(`DELETE FROM files_deferred WHERE defer_uuid = ${db.pool.escape(req.params.deferUuid)}`);
	return res.status(200).send(respond[0]);
};

async function processUploadedFile(file, fileUuid, deferUuid) {
	let fileId = await saveFileToDb(file, fileUuid);
	if (deferUuid != "null") {
		deferFileInDb(deferUuid, fileId);
	}
}

const publicDownload = async (req, res) => {
	try {
		const uuid = req.params.uuid;
		let response = await db.asyncQuery(`SELECT * FROM files WHERE uuid = ${db.pool.escape(uuid)}`, null);
		response = response[0];
		let fileUuid = response.uuid;
		let fileName = response.name;

		res.download("./" + directoryPath + fileUuid + "/" + fileName, fileName, (err) => {
			if (err) {
				res.status(500).send({
					message: "Could not download the file. " + err,
				});
			}
		});
	} catch (error) {
		console.log("#CATCH: " + error);
	}
};

async function saveFileToDb(file, uuid) {
	let fileExtension = path.extname(file.originalname);
	let fileType = mime.lookup(file.originalname);
	let response = await db.asyncQuery(`INSERT INTO files SET ?`, { uuid: uuid, name: file.originalname, type: fileType, size: file.size });
	return response.insertId;
}

function deferFileInDb(deferUuid, fileId) {
	sql = `INSERT INTO files_deferred(defer_uuid, file_id) values (${db.pool.escape(deferUuid)},${db.pool.escape(fileId)})`;
	db.asyncQuery(sql, null);
}

async function cleanUpDeferredFiles() {
	let response = await db.asyncQuery(`SELECT files.id, files.uuid FROM files_deferred INNER JOIN files ON files.id=files_deferred.file_id`);
	response = JSON.parse(JSON.stringify(response));
	response.forEach(async (element) => {
		let dir = __basedir + nconf.get("file_base_dir") + "/" + element.uuid;
		fs.rm(dir, { recursive: true, force: true }, (err) => {
			if (err) {
				throw err;
			}
		});
		let response = await db.asyncQuery(`DELETE FROM files_deferred WHERE file_id=${db.pool.escape(element.id)};`, null);
		db.asyncQuery(`DELETE FROM files WHERE uuid=${db.pool.escape(element.uuid)};`, null);
	});
}
function removeFile(file_uuid) {
	db.asyncQuery(`DELETE FROM files WHERE uuid=${db.pool.escape(file_uuid)}`, null);
	fs.rm(__basedir + directoryPath + file_uuid, { recursive: true, force: true }, (err) => {
		if (err) {
			throw err;
		}
	});
}

const getListFiles = (req, res) => {
	fs.readdir("./" + directoryPath, function (err, files) {
		if (err) {
			res.status(500).send({
				message: "Unable to scan files!",
			});
		}

		let fileInfos = [];

		files.forEach((file) => {
			fileInfos.push({
				name: file,
				url: directoryPath + file,
			});
		});

		res.status(200).send(fileInfos);
	});
};

const getFiles = async (req, res) => {
	let fileInfos = [];

	let response = await db.asyncQuery("SELECT * FROM files", null);
	response.forEach((file) => {
		fileInfos.push({
			id: file.id,
			name: file.name,
			uuid: file.uuid,
			type: file.type,
			size: file.size,
			upload_date: file.upload_date,
			file_url: baseUrl + file.uuid,
		});
	});

	res.status(200).send(fileInfos);
};

async function getFilesByPostId(postId) {
	let response = await db.asyncQuery(`SELECT file_id FROM posts_files_mapping WHERE post_id=${db.pool.escape(postId)}`, null);
	let fileInfos = [];
	for (let i = 0; i < response.length; i++) {
		let response2 = await db.asyncQuery(`SELECT * FROM files WHERE id=${response[i].file_id}`);
		response2 = Object.values(JSON.parse(JSON.stringify(response2)));
		fileInfos.push(response2[0]);
	}
	return fileInfos;
}

const download = async (req, res) => {
	try {
		const fileId = req.params.id;
		let response = await db.asyncQuery(`SELECT * FROM files WHERE id = ${db.pool.escape(fileId)}`, null);
		response = response[0];
		let fileUuid = response.uuid;
		let fileName = response.name;

		res.download("./" + directoryPath + fileUuid + "/" + fileName, fileName, (err) => {
			if (err) {
				res.status(500).send({
					message: "Could not download the file. " + err,
				});
			}
		});
	} catch (error) {
		console.log("#CATCH: " + error);
	}
};
const downloadUrl = async (req, res) => {
	try {
		const uuid = req.params.uuid;
		let response = await db.asyncQuery(`SELECT * FROM files WHERE uuid = ${db.pool.escape(uuid)}`, null);
		response = response[0];
		let fileUuid = response.uuid;
		let fileName = response.name;

		let url = {
			url: baseUrl + fileUuid,
		};

		res.status(200).send(url);
	} catch (error) {
		console.log("#CATCH: " + error);
	}
};

const remove = (req, res) => {
	const fileName = req.params.name;

	fs.unlink(directoryPath + fileName, (err) => {
		if (err) {
			res.status(500).send({
				message: "Could not delete the file. " + err,
			});
		}

		res.status(200).send({});
	});
};

const deleteFileByUuid = (req, res) => {
	const uuid = req.params.uuid;

	try {
		fs.rmSync(directoryPath + uuid, { recursive: true, force: true });

		let response = db.asyncQuery("DELETE FROM files WHERE uuid = " + db.pool.escape(uuid), null);

		res.status(200).send({});
	} catch (err) {
		res.status(500).send({
			message: "Could not delete the file. " + err,
		});
	}
};

module.exports = {
	upload,
	getFileInfoById,
	getFileByDeferUuid,
	removeFile,
	getFiles,
	getListFiles,
	getFileSizeLimit,
	cleanUpDeferredFiles,
	getFilesByPostId,
	download,
	remove,
	deleteFileByUuid,
	publicDownload,
	downloadUrl,
};
