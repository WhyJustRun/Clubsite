<header class="page-header">
    <h1>Resources</h1>
</header>

<div class="row">
    <div class="span4">
        <h2>Upload Resource</h2>
        <?= $this->Form->create('Resource', array('action' => 'add', 'enctype' => 'multipart/form-data')) ?>
        <?= $this->Form->input('key', array('label' => false)) ?>
        <?= $this->Form->file('file') ?>
        <?= $this->Form->input('caption') ?>
        <?= $this->Form->end('Add') ?>
    </div>
    <div class="span7">
        <h2>Resources</h2>
        <table class="table table-striped table-bordered table-condensed">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>URL</th>
                    <th></th>
              </tr>
            </thead>
            <tbody>
        <?php foreach ($resources as $resource) { ?>
                <tr>
                    <td><?= Configure::read('Resource.Club.'.$resource['Resource']['key'].'.name') ?></td>
                    <td><a href="<?= $resource['Resource']['url'] ?>"><?= $resource['Resource']['url'] ?></a></td>
                    <td><a href="/resources/delete/<?= $resource['Resource']['id'] ?>" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i> Delete</a></td>
                </tr>
        <? } ?>
            </tbody>
        </table>
    </div>
</div>

