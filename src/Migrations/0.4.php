<?php

/*
 * Migration to PrimGulp
 * */

// Prim version
if(!$this->replaceInFile('composer.json', [
    ['"jarzon/prim": "0.3.*"', '"jarzon/prim": "0.4.*"']
])) {
    $this->output->writeln("✖ Prim version isn't good.");
    return false;
}

// package.json
$this->replaceInFile('package.json', [
    [
        "\"event-stream\": \"^3.3.4\",
    \"del\": \"^2.2.2\",
    \"gulp\": \"^3.9.1\",
    \"gulp-concat\": \"^2.6.1\",
    \"gulp-rename\": \"^1.2.2\",
    \"gulp-merge-json\": \"^1.0.0\"",

    "\"primgulp\": \"^0.5.*\""
    ]
]);

// gulpfile.js
$replace = 'let gulp = require("gulp");
gulp.tasks = require("primgulp").tasks;';

$this->putInFile('gulpfile.js', $replace);

// Assets

$replace = '{
  "js": {
    "files": {
      "main.js": ["src/*/assets/js/main.js"]
    },
    "destination": "public/js"
  },
  "css": {
    "files": {
      "main.css": ["src/*/assets/css/main.css"]
    },
    "destination": "public/css"
  },
  "img": {
    "files": {
      "": ["src/*/assets/img/*.*"]
    },
    "destination": "public/img"
  }
}';

$this->putInFile('src/BasePack/config/assets.json', $replace);

$replace = '
app/config/assets.json';

$this->putInFile('.gitignore', $replace, true);