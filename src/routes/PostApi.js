const { randomUUID } = require("crypto");
const express = require("express");
const jwt = require("jsonwebtoken");
const router = express.Router();

var db = require("../db");

router.get("/", (req, res) => {
	res.status(204).send("please specifies function");
});

function verifyToken(req, res, next) {
	if (!req.headers.authorization) {
		return res.status(401).send("Unauthorized request");
	}
	let token = req.headers.authorization.split(" ")[1];
	if (token == "null") {
		return res.status(401).send("Unauthorized request");
	}
	jwt.verify(token, "secretKey", function (err, decoded) {
		if (err) {
			console.log(err);
			res.statusMessage = "error";
			return res.status(401).send(err);
		}
		if (decoded) {
			req.userId = decoded.subject;
			next();
		}
	});
}
router.post("/createPost", verifyToken, (req, res) => {
	let data = req.body;
	let postUuid = randomUUID();
	sql = `INSERT INTO posts(uuid, title, text, date, user_id, priority, center_id) values ("${postUuid}","${data.TitleInput}","${db.escape(data.TextInput)}",NOW(),"1","${data.PriorityInput}","${data.CenterInput}")`;
	db.query(sql, (err, result) => {
		if (err) {
			console.log("/createPost error:" + err);
		}
	});
	if (data.FilesDefer != "null") {
		try {
			let filesDeferJson = JSON.parse(data.FilesDefer);
			for (var attributename in filesDeferJson) {
				sqlSelect = `SELECT file_uuid FROM files_deferred WHERE defer_uuid = "${filesDeferJson[attributename]}"`;
				let sqlResult = null;
				db.query(sqlSelect, (err, result) => {
					if (err) {
						console.log("/getrAllPosts error:" + err);
					}
					sqlResult = JSON.parse(JSON.stringify(result));
					sqlResult = sqlResult[0];

					sqlInsert = `INSERT INTO posts_files_mapping(post_uuid, file_uuid) values ("${postUuid}","${sqlResult["file_uuid"]}")`;
					db.query(sqlInsert, (err, result) => {
						if (err) {
							console.log("/postsFilesMapping error:" + err);
						}
					});
					sqlDelete = `DELETE FROM files_deferred WHERE defer_uuid="${filesDeferJson[attributename]}"`;
					db.query(sqlDelete, (err, result) => {
						if (err) {
							console.log("/deleteFilesDeferred error:" + err);
						}
					});
				});
			}
		} catch (error) {}
	}
});
router.post("/deletePost", verifyToken, (req, res) => {
	data = req.body;
	sql = `DELETE posts FROM posts WHERE id=${data.id};`;
	db.query(sql, (err, result) => {
		if (err) {
			console.log("/deletePost error:" + err);
		}
	});
});
router.post("/getAllPosts", verifyToken, (req, res) => {
	//FIXME: CreateDate, LastEditUser not found in db
	//const sqlSelect = "SELECT postID, title,text, CreateDate, u.userID as userID, p.id as PriorityID,c.centerID as CenterID, c.name as Center, p.priority as Priority, u.name as UserName, u.lname as UserLname, LastEditDate, u2.userID as LastEditUser from posts join center c on c.centerID = posts.centerID join priority p on p.id = posts.Priority join users u on u.userID = posts.userID left join users u2 on u2.userID = posts.LastEditUser";
	const sqlSelect = "select * from posts";
	db.query(sqlSelect, (err, result) => {
		if (err) {
			console.log("/getrAllPosts error:" + err);
		}
		res.send(result);
	});
});
router.post("/getPriorities", (req, res) => {
	const sqlSelect = "SELECT * FROM priority";
	db.query(sqlSelect, (err, result) => {
		if (err) {
			console.log("/getPriorities error:" + err);
		}
		res.send(result);
	});
});
router.post("/getCenters", (req, res) => {
	const sqlSelect = "SELECT * FROM center";
	db.query(sqlSelect, (err, result) => {
		if (err) {
			console.log("/getCenters error:" + err);
		}
		res.send(result);
	});
});

module.exports = router;
