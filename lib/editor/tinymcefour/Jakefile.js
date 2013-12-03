var uglify = require('./tools/BuildTools').uglify;
var less = require('./tools/BuildTools').less;
var jshint = require('./tools/BuildTools').jshint;
var zip = require('./tools/BuildTools').zip;
var compileAmd = require('./tools/BuildTools').compileAmd;
var parseLessDocs = require('./tools/BuildTools').parseLessDocs;
var getReleaseDetails = require('./tools/BuildTools').getReleaseDetails;
var instrumentFile = require('./tools/BuildTools').instrumentFile;
var glob = require("glob");
var path = require("path");
var fs = require("fs");
var exec = require("child_process").exec;

desc("Default build task");
task("default", ["minify", "less", "jshint"], function () {});

desc("Minify all JS files");
task("minify", [
	"minify-core",
	"minify-jquery-core",
	"minify-jquery-plugin",
	"minify-themes",
	"minify-plugins"
], function () {});

desc("Minify core");
task("minify-core", [], function (params) {
	var details = getReleaseDetails("changelog.txt");
	var noui = params && params.indexOf('noui') !== -1;
	var coverage = params && params.indexOf('coverage') !== -1;

	var from = [
		"dom/DomQuery.js",
		"EditorManager.js",
		"LegacyInput.js",
		"util/XHR.js",
		"util/JSONRequest.js",
		"util/JSONP.js",
		"util/LocalStorage.js",
		"Compat.js"
	];

	if (!noui) {
		from.push("ui/*.js");
	}

	var settings = {
		from: from,
		version: details.version,
		releaseDate: details.releaseDate,
		baseDir: "tinymce/classes",
		rootNS: "tinymce",
		outputSource: "tinymce/tinymce.js",
		outputMinified: "tinymce/tinymce.min.js",
		outputDev: "tinymce/tinymce.dev.js",
		verbose: false,
		expose: "public",
		compress: true,
		force: noui
	};

	if (coverage) {
		settings.outputMinified = false;
		settings.outputCoverage = "tinymce/tinymce.min.js";
		settings.coverageId = params.substr(params.indexOf(':') + 1 || params.length);
	}

	compileAmd(settings);
});

desc("Minify jquery-core");
task("minify-jquery-core", [], function (params) {
	var details = getReleaseDetails("changelog.txt");
	var noui = params && params.indexOf('noui') !== -1;

	var from = [
		"EditorManager.js",
		"LegacyInput.js",
		"util/XHR.js",
		"util/JSONRequest.js",
		"util/JSONP.js",
		"util/LocalStorage.js",
		"Compat.js"
	];

	if (!noui) {
		from.push("ui/*.js");
	}

	compileAmd({
		from: from,
		moduleOverrides: {
			"tinymce/dom/Sizzle": "tinymce/classes/dom/Sizzle.jQuery.js"
		},
		version: details.version,
		releaseDate: details.releaseDate,
		baseDir: "tinymce/classes",
		rootNS: "tinymce",
		outputSource: "tinymce/tinymce.jquery.js",
		outputMinified: "tinymce/tinymce.jquery.min.js",
		outputDev: "tinymce/tinymce.jquery.dev.js",
		verbose: false,
		expose: "public",
		compress: true,
		force: noui
	});
});

desc("Minify jquery plugin");
task("minify-jquery-plugin", [], function () {
	uglify({from: "tinymce/classes/jquery.tinymce.js", to: "tinymce/jquery.tinymce.min.js"});
});

desc("Minify plugin JS files");
task("minify-plugins", ["minify-pasteplugin", "minify-tableplugin", "minify-spellcheckerplugin"], function () {
	glob.sync("tinymce/plugins/*/plugin.js").forEach(function(filePath) {
		uglify({from: filePath, to: path.join(path.dirname(filePath), "plugin.min.js")});
	});
});

desc("Minify theme JS files");
task("minify-themes", [], function () {
	glob.sync("tinymce/themes/**/theme.js").forEach(function(filePath) {
		uglify({from: filePath, to: path.join(path.dirname(filePath), "theme.min.js")});
	});
});

task("minify-pasteplugin", [], function() {
	compileAmd({
		from: "Plugin.js",
		baseDir: "tinymce/plugins/paste/classes",
		rootNS: "tinymce.pasteplugin",
		outputSource: "tinymce/plugins/paste/plugin.js",
		outputMinified: "tinymce/plugins/paste/plugin.min.js",
		outputDev: "tinymce/plugins/paste/plugin.dev.js",
		verbose: false,
		expose: "public",
		compress: true
	});
});

task("minify-tableplugin", [], function() {
	compileAmd({
		from: "Plugin.js",
		baseDir: "tinymce/plugins/table/classes",
		rootNS: "tinymce.tableplugin",
		outputSource: "tinymce/plugins/table/plugin.js",
		outputMinified: "tinymce/plugins/table/plugin.min.js",
		outputDev: "tinymce/plugins/table/plugin.dev.js",
		verbose: false,
		expose: "public",
		compress: true
	});
});

task("minify-spellcheckerplugin", [], function() {
	compileAmd({
		from: "Plugin.js",
		baseDir: "tinymce/plugins/spellchecker/classes",
		rootNS: "tinymce.spellcheckerplugin",
		outputSource: "tinymce/plugins/spellchecker/plugin.js",
		outputMinified: "tinymce/plugins/spellchecker/plugin.min.js",
		outputDev: "tinymce/plugins/spellchecker/plugin.dev.js",
		verbose: false,
		expose: "public",
		compress: true
	});
});

desc("Bundles in plugins/themes into a tinymce.full.min.js file");
task("bundle", ["minify"], function(params) {
	var inputFiles, minContent, addPlugins = true;

	function appendAddon(name) {
		if (addPlugins) {
			if (name == '*') {
				glob.sync('tinymce/plugins/*/plugin.min.js').forEach(function(filePath) {
					minContent += "\n;" + fs.readFileSync(filePath).toString();
				});
			} else {
				minContent += "\n;" + fs.readFileSync("tinymce/plugins/" + name + "/plugin.min.js").toString();
			}
		} else {
			if (name == '*') {
				glob.sync('tinymce/themes/*/theme.min.js').forEach(function(filePath) {
					minContent += "\n;" + fs.readFileSync(filePath).toString();
				});
			} else {
				minContent += "\n;" + fs.readFileSync("tinymce/themes/" + name + "/theme.min.js").toString();
			}
		}
	}

	minContent = fs.readFileSync("tinymce/tinymce.min.js").toString();

	if (arguments[0] == '*') {
		arguments = ['themes:*', 'plugins:*'];
	}

	for (var i = 0; i < arguments.length; i++) {
		var args = arguments[i].split(':');

		if (args[0] == 'plugins') {
			addPlugins = true;
		} else if (args[0] == 'themes') {
			addPlugins = false;
		}

		appendAddon(args[1] || args[0]);
	}

	fs.writeFileSync("tinymce/tinymce.full.min.js", minContent);
});

desc("Runs JSHint on core source files");
task("jshint", ["jshint-core", "jshint-plugins", "jshint-themes"], function () {});

desc("Runs JSHint on core source files");
task("jshint-core", [], function () {
	jshint({patterns: ["tinymce/classes/**/*.js"]});
});

desc("Runs JSHint on plugins files");
task("jshint-plugins", [], function () {
	jshint({
		patterns: [
			"tinymce/plugins/**/plugin.js",
			"tinymce/plugins/**/classes/**/*.js"
		],

		exclude: [
			"tinymce/plugins/table/plugin.js",
			"tinymce/plugins/spellchecker/plugin.js",
			"tinymce/plugins/paste/plugin.js"
		]
	});
});

desc("Runs JSHint on theme files");
task("jshint-themes", [], function () {
	jshint({patterns: ["tinymce/themes/**/theme.js", "tinymce/themes/**/classes/**/*.js"]});
});

desc("Compiles LESS skins to CSS");
task("less", [], function () {
	var lessFiles;

	lessFiles = [
		"Reset.less",
		"Variables.less",
		"Mixins.less",
		"Animations.less",
		"TinyMCE.less"
	].concat(parseLessDocs("tinymce/tinymce.js"));

	fs.readdirSync("tinymce/skins").forEach(function(skinName) {
		// Modern browsers
		less({
			baseDir: "tinymce/skins/" + skinName + "",
			from: lessFiles.concat(["Icons.less"]),
			toCss: "tinymce/skins/" + skinName + "/skin.min.css",
			toLess: "tinymce/skins/" + skinName + "/skin.less",
			toLessDev: "tinymce/skins/" + skinName + "/skin.dev.less"
		});

		// IE7
		less({
			baseDir: "tinymce/skins/" + skinName + "",
			from: lessFiles.concat(["Icons.Ie7.less"]),
			toCss: "tinymce/skins/" + skinName + "/skin.ie7.min.css",
			toLess: "tinymce/skins/" + skinName + "/skin.ie7.less"
		});

		// Content CSS
		less({
			from: ["Content.less"],
			toCss: "tinymce/skins/" + skinName + "/content.min.css",
			baseDir: "tinymce/skins/" + skinName + "",
			force: true
		});

		// Content CSS (inline)
		less({
			from: ["Content.Inline.less"],
			toCss: "tinymce/skins/" + skinName + "/content.inline.min.css",
			baseDir: "tinymce/skins/" + skinName + "",
			force: true
		});
	});
});

desc("Builds release packages as zip files");
task("release", ["default", "nuget", "zip-production", "zip-production-jquery", "zip-development"], function (params) {});

task("zip-production", [], function () {
	var details = getReleaseDetails("changelog.txt");

	if (!fs.existsSync("tmp")) {
		fs.mkdirSync("tmp");
	}

	zip({
		baseDir: "tinymce",

		exclude: [
			"tinymce/tinymce.js",
			"tinymce/tinymce.dev.js",
			"tinymce/tinymce.full.min.js",
			"tinymce/tinymce.jquery.js",
			"tinymce/tinymce.jquery.min.js",
			"tinymce/tinymce.jquery.dev.js",
			"tinymce/jquery.tinymce.min.js",
			"tinymce/plugins/visualblocks/img",
			"tinymce/plugins/compat3x",
			"readme.md",
			/(imagemanager|filemanager|moxiemanager)/,
			/plugin\.js|plugin\.dev\.js|theme\.js/,
			/classes/,
			/.+\.less/,
			/\.dev\.svg/
		],

		from: [
			"js",
			"changelog.txt",
			"LICENSE.TXT",
			"readme.md"
		],

		to: "tmp/tinymce_" + details.version + ".zip"
	});
});

task("zip-production-jquery", [], function () {
	var details = getReleaseDetails("changelog.txt");

	if (!fs.existsSync("tmp")) {
		fs.mkdirSync("tmp");
	}

	zip({
		baseDir: "tinymce",

		pathFilter: function(args) {
			if (args.zipFilePath == "tinymce/tinymce.jquery.min.js") {
				args.zipFilePath = "tinymce/tinymce.min.js";
			}
		},

		exclude: [
			"tinymce/tinymce.js",
			"tinymce/tinymce.min.js",
			"tinymce/tinymce.dev.js",
			"tinymce/tinymce.full.min.js",
			"tinymce/tinymce.jquery.js",
			"tinymce/tinymce.jquery.dev.js",
			"tinymce/plugins/visualblocks/img",
			"tinymce/plugins/compat3x",
			"readme.md",
			/(imagemanager|filemanager|moxiemanager)/,
			/plugin\.js|plugin\.dev\.js|theme\.js/,
			/classes/,
			/.+\.less/,
			/\.dev\.svg/
		],

		from: [
			"js",
			"changelog.txt",
			"LICENSE.TXT",
			"readme.md"
		],

		to: "tmp/tinymce_" + details.version + "_jquery.zip"
	});
});

task("zip-development", [], function () {
	var details = getReleaseDetails("changelog.txt");

	if (!fs.existsSync("tmp")) {
		fs.mkdirSync("tmp");
	}

	zip({
		baseDir: "tinymce",

		exclude: [
			"tinymce/tinymce.full.min.js",
			/(imagemanager|filemanager|moxiemanager)/
		],

		from: [
			"js",
			"tests",
			"tools",
			"changelog.txt",
			"LICENSE.TXT",
			"readme.md",
			"Jakefile.js",
			"package.json"
		],

		to: "tmp/tinymce_" + details.version + "_dev.zip"
	});
});

task("nuget", [], function () {
	var details = getReleaseDetails("changelog.txt");

	exec("NuGet.exe pack tools/nuget/TinyMCE.nuspec -Version " + details.version + " -OutputDirectory tmp", function (error, stdout, stderr) {
		if (error !== null) {
			console.log('exec error: ' + error);
		}
	});

	exec("NuGet.exe pack tools/nuget/TinyMCE.jquery.nuspec -Version " + details.version + " -OutputDirectory tmp", function (error, stdout, stderr) {
		if (error !== null) {
			console.log('exec error: ' + error);
		}
	});
});

task("instrument-plugin", [], function(pluginName) {
	if (pluginName) {
		instrumentFile({
			from: "tinymce/plugins/" + pluginName + "/plugin.js",
			to: "tinymce/plugins/" + pluginName + "/plugin.min.js"
		});
	}
});

desc("Cleans the build directories");
task("clean", [], function () {
	[
		"tmp/*",
		"tinymce/tinymce*",
		"tinymce/**/*.min.js",
		"tinymce/**/*.dev.js",
		"tinymce/plugins/table/plugin.js",
		"tinymce/skins/**/*.min.css",
		"tinymce/skins/**/skin.less"
	].forEach(function(pattern) {
		glob.sync(pattern).forEach(function(filePath) {
			fs.unlinkSync(filePath);
		});
	});
});

