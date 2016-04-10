<header class="page-header">
    <h1>Resources</h1>
</header>

<div class="row">
    <div class="col-sm-4">
        <h2>Upload Resource</h2>
        <?php echo $this->Form->create('Resource', array('url' => 'add', 'enctype' => 'multipart/form-data')) ?>
        <?php echo $this->Form->input('key', array('label' => false)) ?>
        <?php echo $this->Form->file('file') ?>
        <br />
        <?php echo $this->Form->input('caption') ?>
        <?php echo $this->Form->end('Add') ?>
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
                        <td><?php echo Configure::read('Resource.Club.'.$resource['Resource']['key'].'.name') ?></td>
                        <td><a href="/resources/delete/<?php echo $resource['Resource']['id'] ?>" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span> Delete</a></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
