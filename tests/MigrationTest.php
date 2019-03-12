<?php
declare(strict_types=1);
namespace Tests;

use PHPUnit\Framework\TestCase;

use Prim\Console\Service\Migration;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

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
            ],
            'stdout' => ''
        ];

        $this->root = vfsStream::setup('root', null, $structure);
    }

    public function testMigration()
    {
        $migration = new Migration();

        $this->assertTrue($migration->migration(vfsStream::url('/root/ProjectDir/'), vfsStream::url('/root/Prim\Console/Migrations/0.1.php')),
            '->migration return true when file exist');

        return $migration;
    }
}
