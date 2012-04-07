<header class="page-header">
    <h1>Merge user accounts</h1>
</header>

<div class="row">
    <div class="span12">
        Merge two accounts. Copy all results, organizers, etc from
        <b>Duplicate account</b> to <b>Primary account</b>. Then delete
        <b>Duplicate account</b>. If <b>Duplicate account</b> has user attributes that are
        NULL in <b>Primary account</b>, then use these. The password of
        <b>Primary account</b> will be used.
    </div>
    <div class="span8">
        <h2>Detected duplicates</h2>
        These accounts were determined to be duplicates. Duplicates exist for accounts
        that have similar names. The algorithm for determining which of the two 
        accounts is the primary account is as follows:
        <ul>
            <li>Check if only one account has a password (i.e. it is a 'real' account)</li>
            <li>Check which account has been logged in with most recent (not used)</li>
            <li>Check which account has been registered for the most recent event</li>
            <li>Check which account was created first</li>
        </ul>
        <table class="table table-striped table-bordered table-condensed">
            <thead>
                <tr>
                    <th colspan='4'>Primary account</th>
                    <th colspan='4'>Duplicate account</th>
                    <th></th>
                </tr>
                <tr>
                    <th>Name</th>
                    <th>Id</th>
                    <th>Real?</th>
                    <th>Most recent event</th>
                    <th>Name</th>
                    <th>Id</th>
                    <th>Real?</th>
                    <th>Most recent event</th>
                    <th></th>
                </tr>
            </thead>
            <?foreach ($dupUsers as $dupUser) {
                $primaryId = $dupUser["primary"]["User"]["id"];
                $duplicateId = $dupUser["duplicate"]["User"]["id"];
                $primaryDate = substr($dupUser["primary"]["most_recent"],0,10);
                $duplicateDate = substr($dupUser["duplicate"]["most_recent"],0,10);
                $real1 = $dupUser["primary"]["User"]["password"]? '&#x2713;' : '&nbsp';
                $real2 = $dupUser["duplicate"]["User"]["password"]? '&#x2713;' : '&nbsp';
                ?>
            <tr>
                <td><?= $dupUser["primary"]["User"]["name"]?></td>
                <td><?= $primaryId?></td>
                <td><?= $real1?></td>
                <td><?= $primaryDate?></td>
                <td><?= $dupUser["duplicate"]["User"]["name"]?></td>
                <td><?= $duplicateId?></td>
                <td><?= $real2?></td>
                <td><?= $duplicateDate?></td>
                <td><?=$this->Html->link('Merge', "/users/merge/$primaryId/$duplicateId", array('class' => 'btn'))?></td>
            </tr>
            <?}?>
        </table>
        </div>
        <div class="span4">
            <h2>Manual merge</h2>
            <?
            echo $this->Form->create('User', array('action' => 'showDuplicates'));
            echo $this->Form->input('User.0.user_id', array('label' => 'Primary account'));
            echo $this->Form->input('User.1.user_id', array('label' => 'Duplicate account'));
            echo $this->Form->end('Merge');

            ?>
        </div>
    </div>
</div>
