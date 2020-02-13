<?php declare(strict_types=1);
namespace Tests;

use PHPUnit\Framework\TestCase;

use Prim\Console\Input;
use Prim\Console\Output;
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
            'src' => [
                'TestPack' => [
                    'phinx' => [
                        'migrations' => [

                        ]
                    ],
                ],
            ],
            'stdout' => '',
            'stdin' => "Test\r\n
                        Table\r\n
                        create\r\n"
        ];

        $this->root = vfsStream::setup('root', null, $structure);
    }

    public function testPhinx()
    {
        $phinx = new PhinxCommand(['root' => vfsStream::url('root')], (new Input(['bin/prim'], vfsStream::url('root/stdin'))), new Output(vfsStream::url('root/stdout')));

        $phinx->exec();

        $this->assertEquals(
            3,
            count(scandir(vfsStream::url('root/src/TestPack/phinx/migrations/'))),
            'phinx migration file have beec created'
        );

        return $phinx;
    }
}
