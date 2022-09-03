const uploadFile = require("../middleware/upload");
const fs = require("fs");
const baseUrl = "http://localhost:8080/files/";
var nconf = require("nconf");

const getFileSizeLimit = (req, res) => {
	res.status(200).send({
		message: nconf.get("file_size_limit"),
	});
};

const upload = async (req, res) => {
	try {
		await uploadFile(req, res);

		if (req.file == undefined) {
			return res.status(400).send({ message: "Please upload a file!" });
		}

		res.status(200).send({});
	} catch (err) {
		console.log(err);

		if (err.code == "LIMIT_FILE_SIZE") {
			return res.status(500).send({
				message: "File size cannot be larger than " + nconf.get("file_size_limit") + " byte!",
			});
		}

		res.status(500).send({
			message: `${req.file.originalname}: Could not upload the file. ${err}`,
		});
	}
};

const getListFiles = (req, res) => {
	const directoryPath = __basedir + "/resources/static/assets/uploads/";

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

const download = (req, res) => {
	const fileName = req.params.name;
	const directoryPath = __basedir + "/resources/static/assets/uploads/";

	res.download(directoryPath + fileName, fileName, (err) => {
		if (err) {
			res.status(500).send({
				message: "Could not download the file. " + err,
			});
		}
	});
};

const remove = (req, res) => {
	const fileName = req.params.name;
	const directoryPath = __basedir + "/resources/static/assets/uploads/";

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
	const directoryPath = __basedir + "/resources/static/assets/uploads/";

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
	getListFiles,
	getFileSizeLimit,
	download,
	remove,
	removeSync,
};
