<div class="row">
    <div class="span4">
    <div class="page-header">
    <h1>List of officials</h1>
</div>
    Clubs are strongly encouraged to track Officials' certification levels. This information is useful in many ways, including tracking numbers of qualified officials, recording pre-requisites for official certification, and identifying qualified officials for major events. 
    </div>
    <div class="span8">
        <h3>Add official</h3>
        <?= $this->Form->create('Official', array('class' => 'form-inline', 'action' => 'add')) ?>
        <?= $this->Form->hidden('user_id') ?>
        <?php $this->Form->unlockField('Official.user_id'); ?>
        <input placeholder="Name" type="text" id="UserName" />
        <script type="text/javascript">
        $(function() {
            orienteerAppPersonPicker('#UserName', { maintainInput: true }, function(person) {
                if(person != null) {
                    $("#OfficialUserId").val(person.id);
                }
        	});
        });
        </script>
        <?= $this->Form->input('official_classification_id', array('class' => 'input-medium', 'label'=>false, 'div' => false));?>
        <?= $this->Form->input('date', array('class' => 'input-medium', 'type' => 'text', 'dateFormat'=>'DMY', 'timeFormat'=>'NONE', 'label'=>false, 'div' => false, 'placeholder' => 'Date (YYYY-MM-DD)'));?>
        <button type="submit" class="btn btn-success">
            <i class="icon-plus icon-white"></i>
        </button>
        <?= $this->Form->end(null) ?>

        <h3>Current officials</h3>
<table class="table table-striped table-bordered table-condensed">
    <thead>
        <th>Name</th>
        <th>Classification/Date certified</th>
        <th></th>
    </thead>
    <?
    foreach($officials as $official) {
        $name   = $official["User"]["name"];
        $classification = $official["OfficialClassification"]["name"];
        $desc = $official["OfficialClassification"]["description"];
        if($desc != "") {
           $classification = $classification . " (" . $desc . ")";
        }
        $date = substr($official["Official"]["date"],0,10);
    ?>
    <tr>
        <td><?=$name?></td>
        <td>
            <?= $this->Form->create('Official', array('class' => 'form-inline thin-form', 'action' => 'edit'));?>
            <?php
            echo $this->Form->hidden('user_id', array('value'=> $official["User"]["id"]));
            echo $this->Form->hidden('id', array('value'=> $official["Official"]["id"]));
            echo $this->Form->input('official_classification_id', array('div' => false, 'class' => 'input-medium', 'value'=>$official["OfficialClassification"]["id"], 'label'=>false));
            echo $this->Form->input('date', array('class' => 'input-medium', 'type' => 'text', 'value'=>substr($official["Official"]["date"],0,10), 'dateFormat'=>'DMY', 'timeFormat'=>'NONE', 'label'=>false, 'div' => false, 'placeholder' => 'Date (YYYY-MM-DD)'));
            ?>
            <input type="submit" value="Update" class="btn btn-mini">
            <?= $this->Form->end() ?>
        <td>
            <?php
            echo $this->Form->create('Official', array('action' => 'delete', 'class' => 'thin-form'));
            echo $this->Form->hidden('id', array('value'=> $official["Official"]["id"])); ?>
            <button type="submit" class="btn btn-mini btn-danger">
                <i class="icon-trash icon-white"></i>
            </button>
            <?php
            echo $this->Form->end();
            ?>
        </td>
    </tr>
    <?}?>
</table>
</div>
</div>
