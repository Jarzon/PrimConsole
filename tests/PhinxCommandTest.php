<?php
declare(strict_types=1);
namespace Tests;

use PHPUnit\Framework\TestCase;

use Prim\Console\PhinxCommand;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

class PhinxCommandTest extends TestCase
{
    /**
     * @var  vfsStreamDirectory
     */
    private $root;

    public function setUp()
    {
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

    public function testPhinx()
    {
        $phinx = new PhinxCommand([]);

        $this->assertTrue(
            $phinx->exec(),
            '->migration return true when file exist'
        );

        return $phinx;
    }
}
