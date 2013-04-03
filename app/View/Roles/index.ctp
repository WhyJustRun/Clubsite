<header class="page-header">
    <div class="pull-right">
       <a href="/roles/edit" class="btn btn-success"><i class="icon-plus icon-white"></i> Role</a>
    </div>
    <h1>Roles</h1>
</header>
<div class="">
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
                <td><?=$role["Role"]["name"]?></td>
                <td><?=$role["Role"]["description"]?></td>
                <td>
                    <a class="btn btn-mini btn-primary" href="/roles/edit/<?= $role["Role"]["id"] ?>">
                        <i class="icon-cog icon-white"></i>
                    </a>
                </td>
                <td>
                    <?php
                    echo $this->Form->create('Role', array('action' => 'delete'));
                    echo $this->Form->hidden('id', array('value'=> $role["Role"]["id"])); ?>
                    <button type="submit" class="btn btn-mini btn-danger">
                        <i class="icon-trash icon-white"></i>
                    </button>
                    <?php
                    echo $this->Form->end();
                    ?>
                </td>
            </tr>
            <? } ?>
        </tbody>
    </table>
</div>

