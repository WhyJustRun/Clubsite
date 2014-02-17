<header class="page-header">
    <h1>Resources</h1>
</header>

<?php if(!$pages) { ?>
<h3>Sorry, no resources are posted yet.</h3>
<?php } ?>
<?php foreach($pages as $page) { ?>
<h3><?= $this->Html->link($page["Page"]["name"], '/pages/'.$page["Page"]["id"]) ?></h3>
<?php
}
?>
<?php
if($this->User->canEditPages()) {
?>
    <hr>
    <h2>Add page</h2>
    <?php
    echo $this->Form->create('Page', array('action' => 'add', 'class' => 'form-horizontal'));
    echo $this->Form->input('name', array('data-validate' => 'validate(required)')); ?>
    <fieldset class="form-group">
        <label for="PageContent" class="control-label col-sm-2">Content</label>
        <div class="col-sm-10">
            <?= $this->Form->input('content', array('label' => false, 'class' => 'oa-wysiwyg', 'rows' => 20, 'data-validate' => 'validate(required)', 'div' => false)); ?>
        </div>
    </fieldset>
    <?= $this->Form->end(array('label' => 'Add', 'class' => 'btn btn-primary')) ?>
<?php } ?>

