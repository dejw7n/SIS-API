const uploadFile = require("../middleware/upload");
const fs = require("fs");
var mime = require("mime-types");
var path = require("path");
var config = require("../middleware/config");
var db = require("../db");

const getFileSizeLimit = (req, res) => {
	res.status(200).send({
		message: config.get("file_size_limit"),
	});
};

const directoryPath = config.get("file_base_dir");

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
		}
		global.numberFilesUploaded++;
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

async function processUploadedFile(file, fileUuid, deferUuid) {
	let fileId = await saveFileToDb(file, fileUuid);
	if (deferUuid != "null") {
		deferFileInDb(deferUuid, fileId);
	}
}

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
	console.log("call removeFile");
	db.asyncQuery(`DELETE FROM files WHERE uuid=${db.pool.escape(file_uuid)}`, null);
	fs.rm(__basedir + directoryPath + file_uuid, { recursive: true, force: true }, (err) => {
		if (err) {
			throw err;
		}
	});
}

const getListFiles = (req, res) => {
	fs.readdir(directoryPath, function (err, files) {
		if (err) {
			res.status(500).send({
				message: "Unable to scan files!",
			});
		}

		let fileInfos = [];

		files.forEach((file) => {
			fileInfos.push({
				name: file,
				url: baseUrl + file,
			});
		});

		res.status(200).send(fileInfos);
	});
};

const download = async (req, res) => {
	const fileUuid = req.params.fileUuid;
	let response = await db.asyncQuery(`SELECT * FROM files WHERE uuid = ${db.escape(fileUuid)}`, null);
	let fileName = response.name;

	res.download(directoryPath + fileUuid + "/" + fileName, fileName, (err) => {
		if (err) {
			res.status(500).send({
				message: "Could not download the file. " + err,
			});
		}
	});
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

const removeSync = (req, res) => {
	const fileName = req.params.name;

	try {
		fs.unlinkSync(directoryPath + fileName);

		res.status(200).send({});
	} catch (err) {
		res.status(500).send({
			message: "Could not delete the file. " + err,
		});
	}
};

module.exports = {
	upload,
	removeFile,
	getListFiles,
	getFileSizeLimit,
	cleanUpDeferredFiles,
	download,
	remove,
	removeSync,
};
