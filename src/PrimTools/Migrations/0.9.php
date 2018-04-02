<?php

// Prim version
if(!$this->replaceInFile('composer.json', [
    ['"jarzon/prim": "0.8.*"', '"jarzon/prim": "0.9.*"']
])) {
    $this->output->writeln("✖ Prim version isn't good.");
    return false;
}

// TODO: add 'pdo.class' => '\PDO' in container.php config array

// TODO: remove the error controller injection in index.php and add 'errorController.class' => 'PrimPack\Controller\Error' in container.php

// TODO: