<header class="page-header">
    <h1>Merge user accounts</h1>
</header>

<div class="row">
    <div class="col-sm-12">
        Merge two accounts. Copy all results, organizers, etc from
        <b>Duplicate account</b> to <b>Primary account</b>. Then delete
        <b>Duplicate account</b>. If <b>Duplicate account</b> has user attributes that are
        NULL in <b>Primary account</b>, then use these. The password of
        <b>Primary account</b> will be used.
    </div>
    <div class="col-sm-12">
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
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-condensed">
                <thead>
                    <tr>
                        <th colspan='4'>Primary account</th>
                        <th colspan='4'>Duplicate account</th>
                        <th></th>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <th>Club</th>
                        <th>ID</th>
                        <th>Most recent event (club)</th>
                        <th>Name</th>
                        <th>Club</th>
                        <th>ID</th>
                        <th>Most recent event (club)</th>
                        <th></th>
                    </tr>
                </thead>
                <?php
                function getEventInfo($user) {
                  $eventData = $user['most_recent_event'];
                  $date = substr($eventData['date'], 0, 10);
                  $hasAcronym = !empty($eventData['club_acronym']);
                  return $date.($hasAcronym ? ' ('.$eventData['club_acronym'].')' : '');
                }

                function getUserName($user) {
                  $fakeLabel = ' <span class="label label-default" data-toggle="tooltip" title="This is not an account, just a name associated with event results.">Fake</span>';
                  $realLabel = ' <span class="label label-primary" data-toggle="tooltip" title="This is a user\'s account.">Real</span>';
                  return $user['User']['name'].($user['has_password'] ? $realLabel : $fakeLabel);
                }

                function printCellsForUser($user) {
                ?>
                  <td style="vertical-align:middle"><?php echo getUserName($user); ?></td>
                  <td style="vertical-align:middle"><?php echo $user['Club']['acronym']?></td>
                  <td style="vertical-align:middle"><?php echo $user["User"]["id"]?></td>
                  <td style="vertical-align:middle"><?php echo getEventInfo($user) ?></td>
                <?php
                }

                function userRelatedToClub($user) {
                  $club_id = Configure::read('Club.id');
                  return
                    $user['Club']['id'] == $club_id ||
                    $user['most_recent_event']['club_id'] == $club_id;
                }

                function shouldDisplayMatch($match, $canMergeAnyUser) {
                  return
                    $canMergeAnyUser ||
                    userRelatedToClub($match['primary']) ||
                    userRelatedToClub($match['duplicate']);
                }

                foreach ($dupUsers as $dupUser) {
                    if (!shouldDisplayMatch($dupUser, $canMergeAnyUser)) {
                      continue;
                    }
                    $primaryId = $dupUser["primary"]["User"]["id"];
                    $duplicateId = $dupUser["duplicate"]["User"]["id"];
                    ?>
                <tr>
                    <?php
                    printCellsForUser($dupUser['primary']);
                    printCellsForUser($dupUser['duplicate']);
                    ?>
                    <td style="vertical-align:middle"><?php echo $this->Html->link('Merge', "/users/merge/$primaryId/$duplicateId", array('class' => 'btn btn-default'))?></td>
                </tr>
                <?php
                }
                ?>
            </table>
        </div>
    </div>
    <?php if ($canMergeAnyUser) { ?>
    <div class="col-sm-3">
        <h2>Manual merge</h2>
        <?php
        echo $this->Form->create('User', array('url' => array('action' => 'showDuplicates')));
        echo $this->Form->input('User.0.user_id', array('label' => 'Primary account'));
        echo $this->Form->input('User.1.user_id', array('label' => 'Duplicate account'));
        echo $this->Form->end('Merge');
        ?>
    </div>
    <?php } ?>
</div>
