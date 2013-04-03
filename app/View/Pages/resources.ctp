<header class="page-header">
    <h1>Resources</h1>
</header>

<?php if(!$pages) { ?>
<h2>Sorry, no resources are posted yet.</h2>
<?php } ?>
<?php foreach($pages as $page) { ?>
<h2><?= $this->Html->link($page["Page"]["name"], '/pages/'.$page["Page"]["id"]) ?></h2>
<?php
}
?>
<?php
if($this->Session->check('Auth.User.id') && $this->Session->read("Club.".Configure::read('Club.id').'.Privilege.Page.edit') === true) {
?>
    <hr>
    <h2>Add page</h2>
    <?php
    echo $this->Form->create('Page', array('action' => 'add', 'class' => 'form-horizontal'));
    echo $this->Form->input('name', array('data-validate' => 'validate(required)')); ?>
    <fieldset class="control-group">
        <label for="PageContent" class="control-label">Content</label>
        <div class="controls">
            <?= $this->Form->input('content', array('label' => false, 'class' => 'input-xxlarge oa-wysiwyg', 'rows' => 20, 'data-validate' => 'validate(required)', 'div' => false)); ?>
        </div>
    </fieldset>
    <?= $this->Form->end(array('label' => 'Add', 'class' => 'btn btn-primary', 'div' => array('class' => 'form-actions'))) ?>
<?php } ?>

