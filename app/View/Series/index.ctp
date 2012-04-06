<header class="page-header">
    <div class="pull-right">
       <a href="/series/edit" class="btn btn-success"><i class="icon-plus icon-white"></i> Series</a>
    </div>
    <h1>Series</h1>
</header>
<table class="table table-striped table-bordered table-condensed">
    <thead>
        <tr>
            <th>Acronym</th>
            <th>Name</th>
            <th>Description</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php 
    foreach($series as $serie) {?>
        <tr>
            <td><?= $serie["Series"]["acronym"] ?></td>
            <td><?= $serie["Series"]["name"] ?></td>
            <td><?= $serie["Series"]["description"] ?></td>
            <td>
                <?= $this->Html->link('View', '/series/view/'.$serie["Series"]["id"], array('class' => 'btn btn-mini')); ?>
            </td>
            <td>
                <a class="btn btn-mini btn-primary" href="/series/edit/<?= $serie["Series"]["id"] ?>">
                    <i class="icon-cog icon-white"></i>
                </a>
            </td>
        </tr>
    <? } ?>
    </tbody>
</table>
