<header class="page-header">
    <?php if($this->Session->check('Auth.User.id') && $this->Session->read("Club.".Configure::read('Club.id').'.Privilege.Page.delete') === true) { ?>
    <div class="pull-right">
        <div class="btn-group">
            <a class="btn btn-danger" href="/pages/delete/<?= $page['Page']['id'] ?>" onclick='return confirm("Are you sure you want to delete the page?");'><span class="glyphicon glyphicon-trash"></span></a>
        </div>
    </div>
    <?php } ?>
    <h1 id="page-resource-title-<?= $page['Page']['id'] ?>" class="page-resource-title"><?= $page['Page']['name']; ?></h1>
</header>

<?php
$this->set('needsTextEditor', true);
?>
<div id="page-resource-<?= $page['Page']['id'] ?>" class="page-resource">
<?= $page["Page"]["content"] ?>
</div>

