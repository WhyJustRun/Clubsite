<?php
$date = explode(" ", $date);
$date = $date[0];
?>
<div class="control-group">
    <label class="control-label"><?= $name ?></label>
    <div class="controls">
        <div class="input-append date date-picker" data-date="<?= $date ?>" data-date-format="yyyy-mm-dd">
            <input class="span2" size="16" type="text" id="<?= $id ?>" value="<?= $date ?>"><span class="add-on"><i class="icon-th"></i></span>
        </div>
    </div>
</div>

