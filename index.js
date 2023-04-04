const dotenv = require("dotenv");
dotenv.config({ path: "./env" });
const express = require("express");
const jwt = require("jsonwebtoken");
const bodyParser = require("body-parser");
const cors = require("cors");
const app = express();
const https = require("https");
const fs = require("fs");
const FileController = require("./src/controller/file.controller.js");
dotenv.config({ path: "./env" });

const db = require("./src/db");

app.use(cors());
app.use(express.json());

global.__basedir = __dirname;
const initRoutes = require("./src/routes/FileApi");
initRoutes(app);

const UserApi = require("./src/routes/UserApi");
app.use("/UserApi", UserApi);

const PostApi = require("./src/routes/PostApi");
app.use("/PostApi", PostApi);

app.use(verifyToken);

app.use(bodyParser.urlencoded({ extented: true }));
app.use(express.urlencoded({ extended: true }));

function verifyToken(req, res, next) {
	if (!req.headers.authorization) {
		return res.status(401).send("Unauthorized request");
	}
	let token = req.headers.authorization.split(" ")[1];
	if (token == null) {
		return res.status(401).send("Unauthorized request");
	}
	let payload = jwt.verify(token, "secretKey");
	if (!payload) {
		return res.status(401).send("Unauthorized request");
	}
	req.userId = payload.subject;
	next();
}

const options = {
	key: fs.readFileSync(process.env.SSL_PRIVKEY),
	cert: fs.readFileSync(process.env.SSL_CERT),
};

https
	.createServer(options, (req, res) => {
		res.writeHead(200);
		res.end("Hello, World!");
	})
	.listen(443);

// var listener = app.listen(process.env.PORT, () => {
// 	console.log("Cleaning up deferred files...");
// 	FileController.cleanUpDeferredFiles();
// 	console.log("Listening on port " + listener.address().port);
// 	db.pool.getConnection(function (err, connection) {
// 		if (err === undefined) {
// 			console.log("An error occurred while connecting to the database!");
// 		} else {
// 			console.log("Successfully connected to the database.");
// 		}
// 	});
// });

/*empty*/
app.get("/", (req, res) => {
	res.status(204).send("please specifies function");
});
app.post("/", (req, res) => {
	res.status(204).send("please specifies function");
});
