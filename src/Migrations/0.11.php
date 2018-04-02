<?php

// Prim version
if(!$this->replaceInFile('composer.json', [
    ['"jarzon/prim": "0.10.*"', '"jarzon/prim": "0.11.*"']
])) {
    $this->output->writeln("✖ Prim version isn't good.");
    return false;
}

// TODO: Remove the use of View Service that are being injected trait (Localization)

// TODO: Create a container class that extend prim\container to config it

// TODO: Replace new ModelClass($this->db) by ($this->container->getPdo())

// TODO: Move code that overwrite Controllers constructor in a build method