const express = require("express");
const jwt = require("jsonwebtoken");
const mysql = require("mysql");
const router = express.Router();
const crypto = require("crypto");

const db = require("../db");
const { use } = require("express/lib/router");

router.get("/", (req, res) => {
	res.status(204).send("please specifies function");
});
router.post("/", (req, res) => {
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
	try {
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
	} catch (err) {
		console.log(err);
		res.statusMessage = "error";
		return res.status(401).send(err);
	}
}
/*empty*/
router.post("/verifyToken", (req, res) => {
	if (verifyToken) {
		res.status(200).send();
	} else {
		res.status(401).send();
	}
});
router.get("/getUser/:id", verifyToken, async (req, res) => {
	const userId = req.params.id;

	const sqlSelect = `SELECT users.id, users.name, users.lname, users.phone, users.email, role.id as role_id, role.role, center.id as center_id, center.name as center_name
                     FROM users
                            JOIN role on users.role_id = role.id
                            JOIN center on center.id = users.center_id
                     WHERE users.id = ${userId}`;
	db.pool.query(sqlSelect, (err, result) => {
		if (err) {
			console.log("/getUser error:" + err);
		}
		console.log(result);
		res.send(result[0]);
	});
});
router.post("/getMyData", verifyToken, (req, res) => {
	token = req.headers.authorization.split(" ")[1];
	try {
		let payload = jwt.verify(token, "secretKey");
		userId = payload.subject;
		const sqlSelect = `SELECT id, name, lname, r.role_id, r.role
                       FROM users
                              JOIN role r on users.role_id = r.role_id
                       WHERE id = ${userId}`;
		db.pool.query(sqlSelect, (err, result) => {
			if (err) {
				console.log("/getMyData error" + err);
			}
			res.status(200).send(result);
		});
	} catch (err) {}
});

router.get("/getAllRoles", verifyToken, async (req, res) => {
	let response = await db.asyncQuery(`SELECT * FROM role`, null);
	res.send(response);
});

router.post("/getAllUsers", verifyToken, async (req, res) => {
	const sqlSelect = `SELECT users.id, users.name, users.lname, users.phone, users.email, role.id as role_id, role.role, center.id as center_id, center.name as center_name
    FROM users
    JOIN role on role.id = users.role_id
    JOIN center on center.id = users.center_id
    ORDER BY users.name`;
	let response = await db.asyncQuery(sqlSelect, null);
	res.status(200).send(response);
});

router.post("/verifyUser", (req, ress) => {
	const user = req.body;
	if (user.email && user.pass) {
		sql = `SELECT users.id, password, salt FROM users where email = "${user.email}"`;
		db.pool.query(sql, (err, res) => {
			if (err) {
				console.log("/verifyUser error:" + err);
			} else {
				try {
					pass = user.pass;
					var resultArray = Object.values(JSON.parse(JSON.stringify(res)));
					salt = resultArray[0].salt.toString();

					var hash = crypto.pbkdf2Sync(pass, salt, 1000, 64, `sha512`).toString(`hex`);

					//if (hash == resultArray[0].password) {
					//FIXME: temporary solution without hashing
					if (pass == resultArray[0].password) {
						const sqlSelect = `SELECT id, name, lname, role_id, center_id FROM users WHERE users.id = ${resultArray[0].id}`;
						db.pool.query(sqlSelect, (err, res) => {
							if (err) {
								console.log("/verifyUser error2:" + err);
							} else {
								let resultArray = Object.values(JSON.parse(JSON.stringify(res)));
								let payload = { subject: resultArray[0].id };
								let token = jwt.sign(payload, "secretKey", { expiresIn: "365d" });
								let data = {
									token: token,
									userData: resultArray,
								};
								ress.status(200).send(data);
							}
						});
					} else {
						ress.status(401).send("Unauthorized");
					}
				} catch (e) {
					if (e instanceof TypeError) {
						ress.status(418).send("data not found");
					}
				}
			}
		});
	} else {
		ress.status(418).send("data not found");
	}
});

router.post("/editUser", verifyToken, (req, res) => {
	let userData = req.body;
	console.log(req.body);
	const sqlSelect = `UPDATE users SET 
						name = '${userData.fnameInput}',
						lname = '${userData.lnameInput}',
						phone = '${userData.phoneInput}',
						email = '${userData.emailInput}',
						role_id = '${userData.roleInput}',
						center_id = '${userData.centerInput}',
						password = '${userData.passwordInput}'
						WHERE id = ${userData.id}`;
	db.pool.query(sqlSelect, (err, result) => {
		if (err) {
			console.log("/getMyData error" + err);
		}
		res.status(200).send(result);
	});
});

/*empty*/
router.get("/", (req, res) => {
	res.status(204).send("please specifies function");
});
router.post("/", (req, res) => {
	res.status(204).send("please specifies function");
});

module.exports = router;
