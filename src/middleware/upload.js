const util = require("util");
const multer = require("multer");
var fs = require("fs");
const crypto = require("crypto");
const dotenv = require("dotenv");
dotenv.config({ path: "./env" });

const maxSize = process.env.FILE_SIZE_LIMIT;

let storage = multer.diskStorage({
	destination: (req, file, cb) => {
		let fileUuid = crypto.randomUUID();
		global.selectedFilesUuid.push(fileUuid);
		fs.mkdirSync(__basedir + process.env.file_base_dir + "/" + fileUuid + "/");
		cb(null, __basedir + process.env.file_base_dir + "/" + fileUuid + "/");
	},
	filename: (req, file, cb) => {
		cb(null, file.originalname);
	},
});

let uploadFile = multer({
	storage: storage,
	limits: { fileSize: maxSize },
}).single("file");

let uploadFileMiddleware = util.promisify(uploadFile);
module.exports = uploadFileMiddleware;
