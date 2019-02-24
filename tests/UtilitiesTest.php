<?php
declare(strict_types=1);
namespace Tests;

use PHPUnit\Framework\TestCase;

use Prim\Console\Service\Migration;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use Prim\Console\Output;

class MigrationTest extends TestCase
{
    /**
     * @var  vfsStreamDirectory
     */
    private $root;

    public function setUp()
    {
        $routing = <<<'EOD'
<?php
$router->get('/', ['\Project\Controller\Home', 'index']);

$router->get('/projects', ['\Project\Controller\Home', 'projects']);

$router->get('/os', ['\Project\Controller\Os', 'index']);
$router->get('/clock', ['\Project\Controller\Os', 'clock']);

$router->any('/login', ['\Project\Controller\Sentence', 'login']);
EOD;

        $structure = [
            'Prim\Console' => [
                'Migrations' => [
                    '0.1.php' => '',
                ],
            ],
            'ProjectDir' => [
                'app' => [
                    'routing.php' => $routing,
                ],
                'app2' => [
                    'routing.php' => $routing,
                ],
            ]
        ];

        $this->root = vfsStream::setup('root', null, $structure);
    }

    public function testMigration()
    {
        $output = new TestOutput();

        $migration = new Migration($output);

        $this->assertTrue($migration->migration(vfsStream::url('/root/ProjectDir/'), vfsStream::url('/root/Prim\Console/Migrations/0.1.php')),
            '->migration return true when file exist');

        return [$migration, $output];
    }

    /**
     * @depends testMigration
     */
    public function testReplaceRegexInFile($objs)
    {
        $migration = $objs[0];
        $output = $objs[1];

        $migration->replaceRegexInFile('app/routing.php', [
            '$router->get(',
            '$router->post(',
            '$router->any('
        ], [
            '$r->addRoute(\'GET\', ',
            '$r->addRoute(\'POST\', ',
            '$r->addRoute([\'GET\', \'POST\'], '
        ], true);

        $this->assertContains("✔ Migration on file", $output->output, '->replaceRegexInFile() replace text');

        // TODO: Test the file content to see if its been correctly replaced

        $output->reset();
    }
    /**
     * @depends testMigration
     */
    public function testReplaceRegexFilesInFolder($objs)
    {
        $migration = $objs[0];
        $output = $objs[1];

        $migration->replaceRegexFilesInFolder('app2/', [
            '/\$router->get\(/',
        ], [
            '$r->addRoute(\'GET\', ',
        ]);

        // TODO: Test this method again but with capturing (.*)
        // TODO: Test again but with the third arg that auto esc and make sure it does (won't pass for now)

        $this->assertContains("✔ Migration on file", $output->output, '->replaceRegexFilesInFolder() replace text');

        $output->reset();
    }
}

class TestOutput extends Output
{
    public $output = '';

    public function reset()
    {
        $this->output = '';
    }

    public function writeLine($message)
    {
        $this->output .= $message;
    }
}
