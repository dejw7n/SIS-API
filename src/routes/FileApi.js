const express = require("express");
const router = express.Router();
const controller = require("../controller/file.controller");

let routes = (app) => {
	router.get("/FileApi/fileSizeLimit", controller.getFileSizeLimit);
	router.post("/FileApi/upload", controller.upload);
	router.get("/FileApi/getFileInfoById/:id", controller.getFileInfoById);
	router.get("/FileApi/getFileByDeferUuid/:deferUuid", controller.getFileByDeferUuid);
	router.get("/FileApi/filesPath", controller.getListFiles);
	router.get("/FileApi/files", controller.getFiles);
	router.get("/FileApi/files/:id", controller.download);
	router.get("/FileApi/downloadUrl/:uuid", controller.downloadUrl);
	router.get("/:uuid", controller.publicDownload);
	router.delete("/FileApi/deleteFileByUuid/:uuid", controller.deleteFileByUuid);

	app.use(router);
};
module.exports = routes;
