<?php

/*
 * Packs in the vendor
 * Replace fastroute by Prim router
 * */

// TODO: add the container config in app/config/container.php :

// Prim version
if(!$this->replaceInFile('composer.json', [
    ['"jarzon/prim": "0.4.*"', '"jarzon/prim": "0.5.7"']
])) {
    $this->output->writeln("✖ Prim version isn't good.");
    return false;
}

// Routing

$this->replaceInFolder('src/BasePack/', [
    [
        [
            '%s',
            "'\\\([^\\\]+)\\\([^\\\]+)\\\Controller\\\([^']+)'"
        ],
        '\'$2\\\$3\''
    ],
]);

// Container

$this->replaceInFile('app/config/container.php', [
    [
        "];",
        "    'router.class'    => 'Prim\Router',
];"
    ],
]);