<?php

// Prim version
if(!$this->replaceInFile('composer.json', [
    ['"jarzon/prim": "0.7.*"', '"jarzon/prim": "0.8.*"']
])) {
    $this->output->writeln("✖ Prim version isn't good.");
    return false;
}

// TODO: add ERROR_MAIL and ERROR_MAIL_FROM constants in config.php