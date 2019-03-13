<h1 class="alignCenter"><?=$_('items')?></h1>

<?php $this->insert('form', '', ['new' => true]); ?>

<div class="box">
    <h2 class="alignCenter"><?=$_('list of')?> <?=strtolower($_('items'))?></h2>
    <table class="table">
        <thead>
        <tr>
            <th><?=$_('name')?></th>
            <th><?=$_('description')?></th>
        </tr>
        </thead>
        <tbody>
        <?php if($items): ?>
            <?php foreach ($items as $item) { ?>
                <tr>
                    <td><a href="/items/edit/<?=$item->id?>"><?=$item->name?></a></td>
                    <td><?=$item->description?></td>
                </tr>
            <?php } ?>
        <?php else :?>
                <tr>
                    <td colspan="2">You don't have any items</td>
                </tr>
        <?php endif ?>
        </tbody>
    </table>
</div>