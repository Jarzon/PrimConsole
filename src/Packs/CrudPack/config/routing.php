<?php
$this->addGroup('/items', function($r) {
    $r->both('/[{page:\d+}]', 'BasePack\Item', 'index');

    $r->both('/edit/{item:\d+}', 'BasePack\Item', 'showItem');

    $r->get('/delete/{item:\d+}', 'BasePack\Item', 'deleteItem');
});