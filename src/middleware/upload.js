const util = require("util");
const multer = require("multer");
var fs = require("fs");
var nconf = require("nconf");
const crypto = require("crypto");

const maxSize = nconf.file_size_limit;
global.lastUsedUuid = null;

let storage = multer.diskStorage({
	destination: (req, file, cb) => {
		let fileUuid = crypto.randomUUID();
		global.lastUsedUuid = fileUuid;
		fs.mkdirSync(__basedir + nconf.get("file_base_dir") + "/" + fileUuid + "/");
		cb(null, __basedir + nconf.get("file_base_dir") + "/" + fileUuid + "/");
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
