<h1>Resources</h1>

<? if(!$pages) { ?>
<h2>Sorry, no resources are posted yet.</h2>
<? } ?>
<? foreach($pages as $page) { ?>
<h2><?= $this->Html->link($page["Page"]["name"], '/pages/'.$page["Page"]["id"]) ?></h2>
<?
}
?>

<?php
if($this->Session->check('Auth.User.id') && $this->Session->read("Club.".Configure::read('Club.id').'.Privilege.Page.edit') === true) {
?>
<div class='padded'>
    <br/>
    <h2>Add page</h2>
    <?php
    echo $this->Form->create('Page', array('action' => 'add'));
    echo $this->Form->input('name', array('data-validate' => 'validate(required)'));
    echo $this->Form->input('content', array('data-validate' => 'validate(required)', 'label' => 'Content (Markdown formatted)'));
    echo $this->Form->end('Add');
    ?>
</div>
<?php } ?>