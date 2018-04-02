<?php

// Prim version
if(!$this->replaceInFile('composer.json', [
    ['"jarzon/prim": "0.9.*"', '"jarzon/prim": "0.10.*"']
])) {
    $this->output->writeln("✖ Prim version isn't good.");
    return false;
}

// TODO: use the container to init the Application in index.php