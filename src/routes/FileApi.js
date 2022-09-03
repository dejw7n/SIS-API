const express = require("express");
const router = express.Router();
const controller = require("../controller/file.controller");

let routes = (app) => {
	router.get("/FileApi/fileSizeLimit", controller.getFileSizeLimit);
	router.post("/FileApi/upload", controller.upload);
	router.get("/FileApi/files", controller.getListFiles);
	router.get("/FileApi/files/:name", controller.download);
	router.delete("/FileApi/files/:name", controller.remove);

	app.use(router);
};

module.exports = routes;
