var config = require("nconf");

//always be this value
config.overrides({
	always: "be this value",
});

config.argv().env();
config.file({ file: "config.json" });

config.defaults({
	file_size_limit: 10 * 1024 * 1024,
});

module.exports = config;
