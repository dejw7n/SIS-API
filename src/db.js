const mysql = require("mysql");
const dotenv = require("dotenv");
dotenv.config({ path: "./env" });

const pool = mysql.createPool({
	host: process.env.DATABASE_HOST,
	user: process.env.DATABASE_USER,
	password: process.env.DATABASE_PASSWORD,
	database: process.env.DATABASE_NAME,
});

function asyncQuery(query, params) {
	return new Promise((resolve, reject) => {
		pool.query(query, params, (err, result) => {
			if (err) return reject(err);
			resolve(result);
		});
	});
}

module.exports = {
	pool,
	asyncQuery,
};
