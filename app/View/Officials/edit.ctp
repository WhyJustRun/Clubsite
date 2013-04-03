<div class="page-header">
    <h1><?= isset($official) ? "Edit" : "Add" ?> Official</h1>
</div>
<?= $this->Form->create('Official', array('class' => 'form-horizontal', 'action' => 'edit', 'enctype' => 'multipart/form-data')) ?>
        <?= $this->Form->hidden('user_id') ?>
        <?php $this->Form->unlockField('Privilege.user_id'); ?>
        <input placeholder="Name" type="text" id="UserName" />
        <script type="text/javascript">
        $(function() {
            orienteerAppPersonPicker('#UserName', { maintainInput: true }, function(person) {
                if(person != null) {
                    $("#PrivilegeUserId").val(person.id);
                }
            });
        });
        </script>
<?
echo $this->Form->input('official_classification_id', array('label'=>'Classification'));
echo $this->Form->input('date', array('type' => 'text', 'dateFormat'=>'DMY', 'timeFormat'=>'NONE', 'label'=>'Date certified'));

?>
<?= $this->Form->end(array('label' => 'Save', 'class' => 'btn btn-primary', 'div' => array('class' => 'form-actions'))) ?>


