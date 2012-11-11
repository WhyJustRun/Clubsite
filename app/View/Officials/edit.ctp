<div class="page-header">
    <h1><?= isset($official) ? "Edit" : "Add" ?> Official</h1>
</div>
<?= $this->Form->create('Official', array('class' => 'form-horizontal', 'action' => 'edit', 'enctype' => 'multipart/form-data')) ?>
<?
echo $this->Form->input('user_id', array('empty' => 'Choose a user'));
echo $this->Form->input('official_classification_id', array('label'=>'Classification'));
echo $this->Form->input('date', array('type' => 'text', 'dateFormat'=>'DMY', 'timeFormat'=>'NONE', 'label'=>'Date certified'));

?>
<?= $this->Form->end(array('label' => 'Save', 'class' => 'btn btn-primary', 'div' => array('class' => 'form-actions'))) ?>

