<header class="page-header">
    <h1>Privileges</h1>
</header>

<div class="row">
    <div class="span5">
        <h3>Types of privileges</h3>
        <dl class="dl-horizontal">
        <?php foreach($groupList as $group) { ?>
           <dt><?= $group["Group"]["name"] ?></dt>
           <dd><?= $group["Group"]["description"] ?></dd>
        <?php } ?>
        </dl>
        Those users not listed here can only sign up for events and have no other privileges.
    </div>
    <div class="span7">
        <h3>Add privilege</h3>
        <?= $this->Form->create('Privilege', array('class' => 'form-inline', 'action' => 'add')) ?>
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
        <?= $this->Form->input('group_id', array('class' => 'input-medium', 'label' => false, 'div' => false)) ?>
        <button type="submit" class="btn btn-success">
            <i class="icon-plus icon-white"></i>
        </button>
        <?= $this->Form->end(null) ?>
        
        <h3>Current privileges</h3>
        <table class="table table-striped table-bordered table-condensed">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Group</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($privileges as $privilege) { ?>
                <tr>
                    <td><?= $privilege["User"]["name"] ?></td>
                    <td>
                        <?= $this->Form->create('Privilege', array('class' => 'form-inline thin-form', 'action' => 'edit'));?>
                        <?php
                        echo $this->Form->hidden('user_id', array('value'=> $privilege["User"]["id"]));
                        echo $this->Form->hidden('id', array('value'=> $privilege["Privilege"]["id"]));
                        echo $this->Form->input('group_id', array('div' => false, 'class' => 'input-medium', 'value'=>$privilege["Group"]["id"], 'label'=>false));
                        ?>
                        <input type="submit" value="Save" class="btn btn-mini">
                        <?= $this->Form->end() ?>
                    </td>
                    <td>
                        <?
                        echo $this->Form->create('Privilege', array('class' => 'thin-form', 'action' => 'delete'));
                        echo $this->Form->hidden('id', array('value'=> $privilege["Privilege"]["id"])); ?>
                        <button type="submit" class="btn btn-mini btn-danger">
                            <i class="icon-trash icon-white"></i>
                        </button>
                        <?= $this->Form->end(); ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

