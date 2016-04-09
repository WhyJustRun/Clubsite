<header class="page-header">
    <div class="pull-right">
        <?php echo $this->element('Events/add_link'); ?>
    </div>
    <h1><?php echo $title_for_layout ?></h1>
</header>

<div class="row">
    <div class='col-sm-6'>
        <h2>Which Map?</h2>
        <table class="table table-striped table-bordered table-condensed">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Last Used</th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach($maps as $map) { ?>
                <tr>
                    <td><a href="<?php echo $map['Map']['url']; ?>"><?php echo $map['Map']['name']; ?></a></td>
                    <td>
                        <?php if(!empty($map['Map']['last_used'])) { ?>
                        <a href="<?php echo $map['Event'][0]['url'] ?>" target='_blank'>
                            <?php echo $this->TimePlus->ago($map['Map']['last_used']); ?>
                        </a>
                        <?php } else { ?>   
                        Never used
                        <?php } ?>
                    </td>
                </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
    </div>
    
    <div class='col-sm-6'>
        <h2>Who's Next?</h2>
        <p>A list of people who have attended at least <?php echo Configure::read('Event.planner.attendanceThreshold') ?> events since <?php echo $this->TimePlus->ago(Configure::read('Event.planner.dateThreshold')); ?>, but haven't volunteered as an organizer.</p>
        <table class="table table-striped table-bordered table-condensed">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Attended</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($users as $user) { ?>
                <tr>
                    <td>
                        <a href="<?php echo $user['User']['url']; ?>" target='_blank'><?php echo $user['User']['name']; ?></a>
                    </td>
                    <td>
                        <?php echo $user['User']['attended']; ?> events
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

