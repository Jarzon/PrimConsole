<?php

/*
 * Simple MVC
 *
 * */

// TODO: messages/*.yml => messages/*.json
// TODO: Detect if .gitigniore exist, if no create one that ignore:
// .idea/
// vendor/
// app/config/config.php

// Prim version
if(!$this->replaceInFile('composer.json', [
    ['"jarzon/prim": "dev-master"', '"jarzon/prim": "0.1.*"']
])) {
    $this->output->writeln("✖ Prim version isn't good.");
    return false;
}

$search = <<<'EOD'
if(ENV == 'prod') {
    define('URL_RELATIVE_BASE', $_SERVER['REQUEST_URI']);
    define('URL_BASE', '');
}
else {
    $dirname = str_replace('public', '', dirname($_SERVER['SCRIPT_NAME']));
    define('URL_RELATIVE_BASE', str_replace($dirname, '', $_SERVER['REQUEST_URI']));
    define('URL_BASE', $dirname);
}

define('URL_PROTOCOL', !empty($_SERVER['HTTPS'])?'https://':'http://');
define('URL_DOMAIN', $_SERVER['SERVER_NAME']);

define('URL', URL_PROTOCOL . URL_DOMAIN . URL_BASE);

try {
    $app = new Application($_SERVER['REQUEST_METHOD'], URL_RELATIVE_BASE);
} catch (\Exception $e) {
    $error = new Error();

    echo $error->page404($e);
}
EOD;

$projectName = ucfirst($this->project);

$replace = '
    $container = new Container([
        \'view.class\'    => \'Prim\View\',
    ]);

    $app = new Application($container, $container->getController(\''.$projectName.'\Controller\Error\'));';

$this->replaceInFile('public/index.php', [
    [['use %s\Controller\Error;', '(PrimBase|'.$projectName.')'], 'use Prim\Container;'],
    ['$error = new Error;', '$error = new Error();'],
    ['echo $error->handleError($e);', 'echo $error->page404($e);'],
    ["
// TODO: Move all the defines in Prim
", ''],
    [$search, $replace],
]);

// Translate Routing config from Phroute to Fastroute
// TODO: Comment Phroute related code (group, filter)
$this->replaceInFile('app/config/routing.php', [
    [["\$router->filter('%s', function(){%s});", '(.*)', '((?>.|\n(?!\}\);))*)\n'], '\$$1 = function() {$2};'],
    [["\$router->group(['prefix' => '%s', 'before' => '%s'], function(\$router)", "((?>.|(?!\\'))*)", "((?>.|(?!\\'))*)"], '$router->addGroup(\'$1\', function(\$router) use(\$$2)'],
    [["\$router->group(['prefix' => '%s'],", "((?>.|(?!\\'))*)"], '$router->addGroup($1, '],
]);

$search = <<<'EOD'
        if($e instanceof \Phroute\Phroute\Exception\HttpRouteNotFoundException) {
            header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
        } else if ($e instanceof \Phroute\Phroute\Exception\HttpMethodNotAllowedException) {
            header($_SERVER['SERVER_PROTOCOL'].' 405 Method Not Allowed');
            header($e->getMessage());
        }
EOD;

$replace = <<<'EOD'
        if($e == 404) {
            header(URL_PROTOCOL.' 404 Not Found');
        } else if ($e == 405) {
            header(URL_PROTOCOL.' 405 Method Not Allowed');
            header($allowedMethods);
        }
EOD;

$replace2 = 'public function handleError($e, $allowedMethods = \'\')';

$this->replaceInFile('src/Controller/Error.php', [
    ['public function page404($t)', $replace2],
    ['public function handleError($e)', $replace2],
    ['$this->design(\'error/404\', $t);', '$this->design(\'error/404\');'],
    ['header($_SERVER["SERVER_PROTOCOL"] . \' 404 Not Found\');', $replace],
    [$search, $replace],
]);

$this->replaceInFolder('src/Controller', [
    ['function __construct()', 'function __construct($view)'],
    ['parent::__construct();', 'parent::__construct($view);'],
    [['$t = new Translate($this->_getTranslation());'], ''],
    [['$t = [%s];', '((?>(?!\];).|\n)+)'], function($text) { return $this->replaceRegex($text, [['%s => %s', '(\s*)(.*)', '(.*)(?!\n)(?>\,)?'], '$1\\$this->addVar($2, $3);']); }],
    [['$t[%s] = %s;', '(.*)', '(.*)'], '$this->addVar($1, $2);'],
    [['$t->%s = %s;', '(.*)', '(.*)'], '$this->addVar(\'$1\', $2);'],
    [['$this->design(\'%s\', $t);', '(.*)'], '$this->design(\'$1\');'],
    [['$t = [];'], ''],
]);

$this->replaceInFolder('src/view', [
    [['$t->%s', '(\w+)'], '\$$1'],
    [['$t[%s]', '(?>\'|")(\w+)(?>\'|")'], '\$$1'],
    ['<?=URL_BASE?>', ''],
]);

$this->replaceInFile('composer.json', [
    ['"jarzon/prim": "dev-master",', '"jarzon/prim": "0.1.*",']
]);

$search = ['Prim\Core\\', 'Prim\\'];

$this->replaceInFolder('src/', [
    $search
]);

$this->replaceInFile('public/index.php', [
    $search
]);

$replace = '{
  "languages": ["en", "fr"],
  "Welcome": ["Welcome", "Bienvenue"]
}';

$this->putInFile('app/config/messages.json', $replace);