<?php

/*
 * Migration for PrimUtilities
 * */

$projectName = ucfirst($this->project);

$this->replaceInFile('composer.json', [
    [
        ['"jarzon/prim": "%s"', '([^"]+)'],
        '"jarzon/prim": "$1",
    "jarzon/primutilities": "0.4.*"'
    ]
]);

$this->mkdir('src/BasePack/Service/');

$replace = '<?php
namespace Tasks\BasePack\Service;

class View extends \Prim\View
{
    use \PrimUtilities\Localization;
}';

$this->putInFile('src/BasePack/Service/View.php', $replace);

$this->replaceInFile('app/config/container.php', [
    [
        "'view.class'    => 'Prim\View',",
        "'view.class'    => '\\$projectName\\BasePack\\Service\\View',"
    ]
]);