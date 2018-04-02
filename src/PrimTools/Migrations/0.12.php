<?php

// Prim version
if(!$this->replaceInFile('composer.json', [
    ['"jarzon/prim": "0.11.*"', '"jarzon/prim": "0.12.*"']
])) {
    $this->output->writeln("✖ Prim version isn't good.");
    return false;
}

// TODO: Convert config.php constants to an array

// TODO: update index.php to pass the config.php array to the Container