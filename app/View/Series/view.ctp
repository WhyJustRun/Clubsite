<header class="page-header">
    <div class="pull-right">
        <a class="btn btn-primary" href="/series/edit/<?= $series["Series"]["id"] ?>">
            <i class="icon-cog icon-white"></i> Edit
        </a>
    </div>
	<h1><?= $series["Series"]["name"] ?> <small><?= "Hosted by " . Configure::read("Club.acronym") ?></small></h1>
</header>
<div class="row">
    <div class="span6">
        <h3>General information</h3>
        <?= $series["Series"]["information"] ?>
    </div>
    <div class="span6">
        <div class="results-list">
            <h2>Event list</h2>
            <table class="table table-striped table-bordered table-condensed">
                <thead>
                    <tr><th>Date</th><th>Name</th></tr>
                </thead>
                <tbody>
                    <?php foreach($events as $event) {?>
                    <tr>
                        <td><?= $event["Event"]["date"] ?></td>
                        <td><a href="<?= '/Events/view/'.$event["Event"]["id"]?>"><?=$event["Event"]["name"] ?></a></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
