<div class="row">
    <div class="col-sm-4">
        <div class="page-header">
            <h1>List of officials</h1>
        </div>
        Clubs are strongly encouraged to track Officials' certification levels. This information is useful in many ways, including tracking numbers of qualified officials, recording pre-requisites for official certification, and identifying qualified officials for major events. 
    </div>
    <div class="col-sm-8">
        <h3>Add official</h3>
        <?= $this->Form->create('Official', array('class' => 'form-inline', 'action' => 'add')) ?>
        <?= $this->Form->hidden('user_id') ?>
        <?php $this->Form->unlockField('Official.user_id'); ?>
        <div class="form-group">
            <input class="form-control" placeholder="Name" type="text" id="UserName" />
        </div>
        <?php $this->append('secondaryScripts'); ?>
        <script type="text/javascript">
        $(function() {
            orienteerAppPersonPicker('#UserName', { maintainInput: true, allowFake: false }, function(person) {
                if(person != null) {
                    $("#OfficialUserId").val(person.id);
                }
            });
        });
        </script>
        <?php $this->end(); ?>
        <div class="form-group">
            <?= $this->Form->input('official_classification_id', array('class' => 'form-control', 'label'=>false, 'div' => false));?>
        </div>
        <div class="form-group">
            <?= $this->Form->input('date', array('class' => 'form-control', 'type' => 'text', 'dateFormat'=>'DMY', 'timeFormat'=>'NONE', 'label'=>false, 'div' => false, 'placeholder' => 'Date (YYYY-MM-DD)'));?>
        </div>
        <button type="submit" class="btn btn-success">
            <span class="glyphicon glyphicon-plus"></span>
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
            <?= $this->Form->create('Official', array('class' => 'form-inline', 'action' => 'edit'));?>
            <?php
            echo $this->Form->hidden('user_id', array('value'=> $official["User"]["id"]));
            echo $this->Form->hidden('id', array('value'=> $official["Official"]["id"]));
            echo $this->Form->input('official_classification_id', array('div' => 'form-group', 'class' => 'form-control input-sm', 'value'=>$official["OfficialClassification"]["id"], 'label'=>false));
            echo $this->Form->input('date', array('class' => 'form-control input-sm', 'type' => 'text', 'value'=>substr($official["Official"]["date"],0,10), 'dateFormat'=>'DMY', 'timeFormat'=>'NONE', 'label'=>false, 'div' => 'form-group', 'placeholder' => 'Date (YYYY-MM-DD)'));
            ?>
            <input type="submit" value="Update" class="btn btn-default btn-sm">
            <?= $this->Form->end() ?>
        <td>
            <?php
            echo $this->Form->create('Official', array('action' => 'delete'));
            echo $this->Form->hidden('id', array('value'=> $official["Official"]["id"])); ?>
            <button type="submit" class="btn btn-sm btn-danger">
                <span class="glyphicon glyphicon-trash"></span>
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

