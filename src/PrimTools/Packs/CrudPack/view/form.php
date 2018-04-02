<?php if(isset($message)):?>
    <div class="<?=$message[0]?>"><?=$_($message[1])?></div>
<?php endif;?>

<form action="<?=($new)? '': "/items/edit/$item->id"?>" method="POST">

    <?php foreach ($forms as $form):?>
        <?php if($form['type'] == 'checkbox' || $form['type'] == 'radio'): ?>
            <?php foreach ($form['html'] as $checkbox):?>
                <label class="topLabel"><?=$checkbox['input']?><br> <?=$_($checkbox['label'])?></label>
            <?php endforeach;?>
        <?php else: ?>
            <label class="topLabel"><?=$_($form['label'])?><br> <?=$form['html']?></label>
        <?php endif; ?>
    <?php endforeach;?>
    <hr class="separator"><br>

    <input type="submit" name="submit_item" value="<?=$_(($new)? 'create': 'update')?>">
</form>