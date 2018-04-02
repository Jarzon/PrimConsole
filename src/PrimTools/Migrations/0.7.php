<?php

// Prim version
if(!$this->replaceInFile('composer.json', [
    ['"jarzon/prim": "0.6.*"', '"jarzon/prim": "0.7.*"']
])) {
    $this->output->writeln("✖ Prim version isn't good.");
    return false;
}

// TODO: Create a cache dir

// TODO: use the new model ->update and ->insert methods