<?php
/**
 * @var $this \Prim\Router
 */
$this->addGroup('/basepack', function($r) {
    $r->get('/', 'BasePack\Home', 'index');
});