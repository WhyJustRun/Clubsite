<header class="page-header">
    <h1>Privileges</h1>
</header>

<div class="row">
    <div class="col-sm-5">
        <h3>Types of privileges</h3>
        <dl class="dl-horizontal">
        <?php foreach($groupList as $group) { ?>
           <dt><?php echo $group["Group"]["name"] ?></dt>
           <dd><?php echo $group["Group"]["description"] ?></dd>
        <?php } ?>
        </dl>
        Those users not listed here can only sign up for events and have no other privileges.
    </div>
    <div class="col-sm-7">
        <h3>Add privilege</h3>
        <?php echo $this->Form->create('Privilege', array('class' => 'form-inline', 'url' => array('action' => 'add'))) ?>
        <?php echo $this->Form->hidden('user_id') ?>
        <?php $this->Form->unlockField('Privilege.user_id'); ?>
        <div class="form-group">
            <input placeholder="Name"
                   type="text" id="UserName"
                   class='form-control simple-person-picker'
                   data-user-id-target="#PrivilegeUserId"
                   data-maintain-input="true"
                   data-allow-fake="false" />
        </div>
        <?php echo $this->Form->input('group_id', array('class' => 'form-control', 'label' => false, 'div' => 'form-group')) ?>
        <button type="submit" class="btn btn-success">
            <span class="glyphicon glyphicon-plus"></span>
        </button>
        <?php echo $this->Form->end(null) ?>

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
                    <td style="vertical-align: middle"><?php echo $privilege["User"]["name"] ?></td>
                    <td>
                        <?php echo $this->Form->create('Privilege', array('class' => 'form-inline', 'url' => array('action' => 'edit')));?>
                        <?php
                        echo $this->Form->hidden('user_id', array('value'=> $privilege["User"]["id"]));
                        echo $this->Form->hidden('id', array('value'=> $privilege["Privilege"]["id"]));
                        echo $this->Form->input('group_id', array('div' => 'form-group', 'class' => 'form-control input-sm', 'value' => $privilege["Group"]["id"], 'label' => false));
                        ?>
                        <input type="submit" value="Save" class="btn btn-primary btn-sm">
                        <?php echo $this->Form->end() ?>
                    </td>
                    <td>
                        <?php
                        echo $this->Form->create('Privilege', array('class' => 'thin-form', 'url' => array('action' => 'delete')));
                        echo $this->Form->hidden('id', array('value'=> $privilege["Privilege"]["id"])); ?>
                        <button type="submit" class="btn btn-sm btn-danger">
                            <span class="glyphicon glyphicon-trash"></span>
                        </button>
                        <?php echo $this->Form->end(); ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
