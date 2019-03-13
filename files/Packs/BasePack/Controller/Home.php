<?php
namespace PrimBase\BasePack\Controller;

use Prim\Controller;

class Home extends Controller
{
    public function build() {
        // $this->setTemplate('design');
    }

    public function index()
    {
        $model = $this->getModel('BaseModel', 'BasePack');

        $this->render('index', ['name' => 'anonymous']);
    }
}
