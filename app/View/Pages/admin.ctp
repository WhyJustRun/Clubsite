<header class="page-header">
    <h1>Admin tools</h1>
</header>

<div class="row">
    <div class="span4">
        <h2>Users</h2>
        <a href="/privileges/" class="btn">Grant privileges</a><br/><br/>
        <?if($allowShowDuplicates) {?>
            <a href="/users/showDuplicates/" class="btn">Show duplicates</a><br/></br>
        <?}?>
        <a href="#" class="btn disabled">Show Users</a><br><br/>
        <a href="#" class="btn disabled">E-mail list</a>
    </div>
    <div class="span4">
        <h2>Events</h2>
        <a href="/roles/" class="btn">Define organizer roles</a>
        <br/><br/>
        <a href="<?= Configure::read('Rails.domain') ?>/club/<?= Configure::read('Club.id') ?>/participation_report.csv" class="btn">Event Participation Information (CSV)</a>
    </div>
    <div class="span4">
        <h2>Officials</h2>
        <a href="/officials/" class="btn">Officials certification</a>
    </div>
    <div class="span4">
        <h2>Series</h2>
        <a href="/series/" class="btn">Define</a>
    </div>
    
    <div class="span4">
        <h2>Club</h2>
        <a href="/clubs/edit" class="btn">Edit club configuration</a><br/><br/>
        <a href="/resources/index" class="btn">Customize design</a>
    </div>
    <div class="span4">
        <h2>Maps</h2>
        <a href="/mapStandards/" class="btn">Define map standards</a>
    </div>
</div>

