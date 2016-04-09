<header class="page-header">
    <div class="pull-right">
       <a href="/roles/edit" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Role</a>
    </div>
    <h1>Roles</h1>
</header>
<table class="table table-striped table-bordered table-condensed">
    <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach($roles as $role) {?>
        <tr>
            <td><?php echo $role["Role"]["name"]?></td>
            <td><?php echo $role["Role"]["description"]?></td>
            <td>
                <a class="btn btn-xs btn-primary" href="/roles/edit/<?php echo $role["Role"]["id"] ?>">
                    <span class="glyphicon glyphicon-cog"></span>
                </a>
            </td>
            <td>
                <?php
                echo $this->Form->create('Role', array('action' => 'delete'));
                echo $this->Form->hidden('id', array('value'=> $role["Role"]["id"])); ?>
                <button type="submit" class="btn btn-xs btn-danger">
                    <span class="glyphicon glyphicon-trash"></span>
                </button>
                <?php
                echo $this->Form->end();
                ?>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>
