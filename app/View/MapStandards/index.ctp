<header class="page-header">
    <div class="pull-right">
        <a href="/mapStandards/edit" class="btn btn-success"><i class="icon-plus icon-white"></i> Map Standard</a>
    </div>
    <h1>Map Standards</h1>
</header>
<div class="row">
    <div class="span4">
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
                        <a class="btn btn-mini btn-primary" href="/mapStandards/edit/<?= $mapStandard["MapStandard"]["id"] ?>">
                            <i class="icon-cog icon-white"></i>
                        </a>
                    </td>
                    <td>
                        <?php
                        echo $this->Form->create('MapStandard', array('action' => 'delete', 'class' => 'thin-form'));
                        echo $this->Form->hidden('id', array('value'=> $mapStandard["MapStandard"]["id"])); ?>
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
</div>

