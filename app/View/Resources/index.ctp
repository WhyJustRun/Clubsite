<header class="page-header">
    <h1>Resources</h1>
</header>

<div class="row">
    <div class="col-sm-4">
        <h2>Upload Resource</h2>
        <?= $this->Form->create('Resource', array('action' => 'add', 'enctype' => 'multipart/form-data')) ?>
        <?= $this->Form->input('key', array('label' => false)) ?>
        <?= $this->Form->file('file') ?>
        <br />
        <?= $this->Form->input('caption') ?>
        <?= $this->Form->end('Add') ?>
    </div>
    <div class="col-sm-8">
        <h2>Resources</h2>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resources as $resource) { ?>
                    <tr>
                        <td><?= Configure::read('Resource.Club.'.$resource['Resource']['key'].'.name') ?></td>
                        <td><a href="/resources/delete/<?= $resource['Resource']['id'] ?>" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span> Delete</a></td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
