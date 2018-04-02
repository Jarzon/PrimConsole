<?php

/*
 * Nothing to migrate
 * */

// TODO: Prim Utilities needs to be updated at this point

// Prim version
if(!$this->replaceInFile('composer.json', [
    ['"jarzon/prim": "0.5.7"', '"jarzon/prim": "0.6.*"']
])) {
    $this->output->writeln("✖ Prim version isn't good.");
    return false;
}

// TODO: migrate include to another view in a existing view to insert() method
// TODO: Migrate all the addVar to the vars render 3rd param