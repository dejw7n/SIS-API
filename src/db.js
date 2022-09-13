const mysql = require("mysql");

const pool = mysql.createPool({
	host: "91.224.90.56",
	user: "u10_0mYakItBYz",
	password: "8OsjAE.O4H^UKtEDDzlQDUc^",
	database: "s10_spsul_sis",
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
