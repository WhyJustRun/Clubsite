<header class="page-header">
    <div class="pull-right">
        <a href="/mapStandards/edit" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Map Standard</a>
    </div>
    <h1>Map Standards</h1>
</header>
<div class="row">
    <div class="col-sm-4">
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
                foreach($mapStandards as $mapStandard) {?>
                <tr>
                    <td><?= $mapStandard["MapStandard"]["name"] ?></td>
                    <td><?= $mapStandard["MapStandard"]["description"] ?></td>
                    <td>
                        <a class="btn btn-xs btn-primary" href="/mapStandards/edit/<?= $mapStandard["MapStandard"]["id"] ?>">
                            <span class="glyphicon glyphicon-cog"></span>
                        </a>
                    </td>
                    <td>
                        <?php
                        echo $this->Form->create('MapStandard', array('action' => 'delete', 'class' => 'thin-form'));
                        echo $this->Form->hidden('id', array('value'=> $mapStandard["MapStandard"]["id"])); ?>
                        <button type="submit" class="btn btn-xs btn-danger">
                            <span class="glyphicon glyphicon-trash"></span>
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
</div>

