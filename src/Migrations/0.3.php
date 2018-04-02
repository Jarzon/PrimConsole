<?php

/*
 * Adding assets to packs
 * new sections system for the View
 * translation in PrimUtilities
 * */

$projectName = $this->project;

// Prim version
if(!$this->replaceInFile('composer.json', [
    [
        '"jarzon/prim": "0.2.*"',
        '"jarzon/prim": "0.3.*"'
    ]
])) {
    $this->output->writeln("✖ Prim version isn't good.");
    return false;
}

$this->replaceInFile('composer.json', [
    [
        '"require":', "\"config\": {
    \"bin-dir\": \"bin\",
    \"discard-changes\": true
  },
  \"require\":"
    ]
]);

// phinx.yml
$this->replaceInFile('phinx.yml', [
    ['production', 'prod'],
    ['development', 'dev'],
    ['testing', 'test']
]);

// Package.json
$replace = '{
  "name": "'.$projectName.'",
  "repository": {
    "type": "git",
    "url": "git+https://github.com/Jarzon/'.$projectName.'.git"
  },
  "author": "Jarzon",
  "license": "MIT",
  "bugs": {
    "url": "https://github.com/Jarzon/'.$projectName.'/issues"
  },
  "homepage": "https://github.com/Jarzon/'.$projectName.'#readme",
  "dependencies": {

  },
  "devDependencies": {
    "event-stream": "^3.3.4",
    "del": "^2.2.2",
    "gulp": "^3.9.1",
    "gulp-concat": "^2.6.1",
    "gulp-rename": "^1.2.2",
    "gulp-merge-json": "^1.0.0"
  }
}';

$this->putInFile('package.json', $replace);

// gulpfile.json
$replace = 'var es = require(\'event-stream\');
var gulp = require(\'gulp\');
var concat = require(\'gulp-concat\');
var rename = require("gulp-rename");
var merge = require(\'gulp-merge-json\');
var del = require(\'del\');
var config = require(\'./app/config/assets.json\');

gulp.task(\'js-clean\', function () {
    return del([\'public/js/*\']);
});

gulp.task(\'css-clean\', function () {
    return del([\'public/css/*\']);
});

gulp.task(\'img-clean\', function () {
    return del([\'public/img/*\']);
});

gulp.task(\'msg-clean\', function () {
    return del([\'app/config/messages.json\']);
});

gulp.task(\'js-build\', function() {
    var tasks = config.js.files.map(function(file) {
        return gulp.src(file[0])
            .pipe(concat(file[1]))
            .pipe(gulp.dest(config.js.destination));
    });

    return es.concat.apply(null, tasks);
});

gulp.task(\'css-build\', function() {
    var tasks = config.css.files.map(function(file) {
        return gulp.src(file[0])
            .pipe(concat(file[1]))
            .pipe(gulp.dest(config.css.destination));
    });

    return es.concat.apply(null, tasks);
});

gulp.task(\'img-build\', function() {
    var tasks = config.img.files.map(function(file) {
        return gulp.src(file[0])
            .pipe(rename(function (path) {
                path.dirname = file[1];
            }))
            .pipe(gulp.dest(config.img.destination));
    });

    return es.concat.apply(null, tasks);
});

gulp.task(\'msg-build\', function() {
    gulp.src(\'src/**/config/messages.json\')
        .pipe(merge({fileName: \'messages.json\', jsonSpace: \'\'}))
        .pipe(gulp.dest(\'app/config/\'));
});

gulp.task(\'watch\', function() {
    gulp.watch(\'src/**/assets/js/*.js\', [\'js-clean\', \'js-build\']);
    gulp.watch(\'src/**/assets/css/*.css\', [\'css-clean\', \'css-build\']);
    gulp.watch(\'src/**/assets/img/*\', [\'img-clean\', \'img-build\']);
    gulp.watch(\'src/**/config/messages.json\', [\'msg-clean\', \'msg-build\']);
});

gulp.task(\'default\', [\'watch\'], function(){});';

$this->putInFile('gulpfile.js', $replace);

// assets.json
$replace = '{
  "js": {
    "files": [
      ["src/*/assets/js/*.js", "main.js"]
    ],
    "destination": "public/js"
  },
  "css": {
    "files": [
      ["src/*/assets/css/*.css", "main.css"]
    ],
    "destination": "public/css"
  },
  "img": {
    "files": [
      ["src/*/assets/img/*", "/"]
    ],
    "destination": "public/img"
  }
}';

$this->putInFile('app/config/assets.json', $replace);

$this->mkdir('src/BasePack/assets/');

$folders = ['css', 'js', 'img'];

foreach($folders as $folder) {
    $this->gitMove("public/$folder/", "src/BasePack/assets/$folder/");
}

// Recreate frontend assets dirs

foreach($folders as $folder) {
    $this->mkdir("public/$folder/");
}

// Views sections
$this->replaceInFolder('src', [
    ['<?= require APP . \'view/\'.$view.\'.php\'; ?>', '<?= $this->section(\'default\') ?>'],
    ['<?php require "{$this->root}src/$packDirectory/view/$view.php"; ?>', '<?= $this->section(\'default\') ?>']
]);

// Container

$container = $this->extractFromFile('public/index.php', "/Container\(\[([^\]]*)\]\);/");

$this->replaceInFile('public/index.php', [
    [['Container([%s]);', '([^\]]*)'], 'Container(include(APP . \'/config/container.php\'));']
]);

$replace = '<?php
return [' . $container[1] . '];';

$this->putInFile('app/config/container.php', $replace);

$replace = '
node_modules/
public/css/
public/img/
public/js/';

$this->putInFile('.gitignore', $replace, true);