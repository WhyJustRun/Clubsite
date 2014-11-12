<header class="page-header">
    <h1>Admin tools</h1>
</header>

<div class="row">
    <div class="col-sm-4">
        <h2>Users</h2>
        <a href="/privileges/" class="btn btn-default">Grant privileges</a><br/><br/>
        <?php if ($allowShowDuplicates) { ?>
            <a href="/users/showDuplicates/" class="btn btn-default">Show duplicates</a><br/></br>
        <?php } ?>
    </div>
    <div class="col-sm-4">
        <h2>Events</h2>
        <a href="/roles/" class="btn btn-default">Define organizer roles</a>
        <br/><br/>
        <a href="<?= Configure::read('Rails.domain') ?>/club/<?= Configure::read('Club.id') ?>/participation_report.csv" class="btn btn-default">Event Participation Information (CSV)</a>
    </div>
    <div class="col-sm-4">
        <h2>Officials</h2>
        <a href="/officials/" class="btn btn-default">Officials certification</a>
    </div>
    <div class="col-sm-4">
        <h2>Series</h2>
        <a href="/series/" class="btn btn-default">Define</a>
    </div>
    
    <div class="col-sm-4">
        <h2>Club</h2>
        <a href="/clubs/edit" class="btn btn-default">Edit club configuration</a><br/><br/>
        <a href="/resources/index" class="btn btn-default">Customize design</a>
    </div>
    <div class="col-sm-4">
        <h2>Maps</h2>
        <a href="/mapStandards/" class="btn btn-default">Define map standards</a>
    </div>
</div>

