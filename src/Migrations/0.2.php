<?php

/*
 * Migration for packs that contain only Model, View, Controller and the routing
 * */

$projectName = ucfirst($this->project);

// Prim version
if(!$this->replaceInFile('composer.json', [
    ['"jarzon/prim": "0.1.*"', '"jarzon/prim": "0.2.*"']
])) {
    $this->output->writeln("✖ Prim version isn't good.");
    return false;
}

// Namespace
$folders = ['app', 'src', 'public'];

foreach($folders as $folder) {
    $this->replaceInFolder("$folder/", [
        [$projectName . '\Controller', $projectName . '\BasePack\Controller'],
        [$projectName . '\Model', $projectName . '\BasePack\Model'],
        [$projectName . '\Service', $projectName . '\BasePack\Service'],
    ], true);
}

$this->replaceInFolder('src/view/_templates', [
    ["<?php require ROOT.'src/view/'.\$view.'.php'; ?>", '<?php require "{$this->root}src/$packDirectory/view/$view.php"; ?>'],
    ["<?php require ROOT . 'src/view/'.\$view.'.php'?>", '<?php require "{$this->root}src/$packDirectory/view/$view.php"; ?>'],
]);

// Move everything in a base pack
$this->mkdir('src/BasePack/');

$folders = ['Controller', 'Model', 'view', 'Service'];

foreach($folders as $folder) {
    $this->gitMove("src/$folder/", "src/BasePack/$folder/");
}

// Routing

$this->mkdir('src/BasePack/config/');

$this->gitMove('app/config/routing.php', 'src/BasePack/config/routing.php');

$replace = '<?php
$this->getRoutes(\'BasePack\', \'routing.php\');';

$this->putInFile('app/config/routing.php', $replace);

$this->replaceInFolder('src/BasePack/config/', [
    [
        ['$router->%s;', "([^\(]+)\('([^']+)', \[([^\]]+)\]\)"],
        '$this->$1(\'$2\', $3);'
    ],
    [
        ['$router->addRoute(%s);', "\[([^\]]+)\], '([^']+)', \[([^\]]+)\]"],
        '$this->addRoute([$1], \'$2\', $3);'
    ],
]);

// Phinx
if(!$this->fileExists('phinx.yml')) {
    $replace = 'paths:
    migrations: %%PHINX_CONFIG_DIR%%/src/*/phinx/migrations
    seeds: %%PHINX_CONFIG_DIR%%/src/*/phinx/seeds

environments:
    default_migration_table: phinxlog
    default_database: dev
    prod:
        adapter: mysql
        host: localhost
        name: '.$projectName.'
        user: root
        pass: \'\'
        port: 3306
        charset: utf8mb4
        collation: utf8mb4_unicode_ci

    dev:
        adapter: mysql
        host: localhost
        name: '.$projectName.'
        user: root
        pass: \'\'
        port: 3306
        charset: utf8mb4
        collation: utf8mb4_unicode_ci

    test:
        adapter: mysql
        host: localhost
        name: '.$projectName.'
        user: root
        pass: \'\'
        port: 3306
        charset: utf8mb4
        collation: utf8mb4_unicode_ci';

    $this->putInFile('phinx.yml', $replace);
}

if($this->fileExists('migrations/')) {
    $this->mkdir('src/BasePack/phinx/');

    $this->gitMove('migrations/', 'src/BasePack/phinx/migrations/');
} else {
    // Alternate Phinx location
    if($this->fileExists('app/phinx/')) {
        $this->gitMove('app/phinx/', 'src/BasePack/phinx/');
    } // Phinx isn't used create empty folder anyways
    else {
        $this->mkdir('src/BasePack/phinx/');
        $this->mkdir('src/BasePack/phinx/migrations/');
    }

}