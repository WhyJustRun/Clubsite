<header class="page-header">
    <div class="pull-right">
       <a href="/series/edit" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Series</a>
    </div>
    <h1>Series</h1>
</header>
<table class="table table-striped table-bordered table-condensed" style="width: initial">
    <thead>
        <tr>
            <th>Acronym</th>
            <th>Name</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php 
    foreach($series as $serie) {?>
        <tr>
            <td><?= $serie["Series"]["acronym"] ?></td>
            <td><?= $serie["Series"]["name"] ?></td>
            <td>
                <a class="btn btn-xs btn-primary" href="/series/edit/<?= $serie["Series"]["id"] ?>">
                    <span class="glyphicon glyphicon-cog"></span>
                </a>
            </td>
        </tr>
    <? } ?>
    </tbody>
</table>

