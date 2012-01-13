<h2>Resources</h2>
<div class="column-box span-8">
    <h3>Upload Resource</h3>
    <?=$this->Form->create('Resource', array('action' => 'add', 'enctype' => 'multipart/form-data'))?>
    <?=$this->Form->input('key', array('label' => false))?>
    <?=$this->Form->file('file')?>
    <?=$this->Form->input('caption')?>
    <?=$this->Form->end('Add')?>
</div>
<div class="column-box span-14">
    <h3>Resources</h3>
    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th>URL</th>
                <th></th>
          </tr>
        </thead>
        <tbody>
    <? foreach ($resources as $resource) { ?>
            <tr>
                <td><?= Configure::read('Resource.Club.'.$resource['Resource']['key'].'.name') ?></td>
                <td><a href="<?= $resource['Resource']['url'] ?>"><?= $resource['Resource']['url'] ?></a></td>
                <td><a href="/resources/delete/<?= $resource['Resource']['id'] ?>" class="button red">Delete</a></td>
            </tr>
    <? } ?>
        </tbody>
    </table>
</div>
