const express = require("express");
const jwt = require("jsonwebtoken");
const bodyParser = require("body-parser");
const cors = require("cors");
const app = express();

const db = require("./src/db");

//config
var nconf = require("nconf");
nconf.argv().env();
nconf.file({ file: "config.json" });
nconf.defaults({
	file_size_limit: 10 * 1024 * 1024,
});

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

var listener = app.listen(30006, () => {
	console.log("Listening on port " + listener.address().port);
	db.getConnection(function (err, connection) {
		if (err === undefined) {
			console.log("An error occurred while connecting to the database!");
		} else {
			console.log("Successfully connected to the database.");
		}
	});
});

/*empty*/
app.get("/", (req, res) => {
	res.status(204).send("please specifies function");
});
app.post("/", (req, res) => {
	res.status(204).send("please specifies function");
});
