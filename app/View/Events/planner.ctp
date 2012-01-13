<div class="right"><?= $this->Html->link('Add Event', '/events/edit', array('class' => 'button', 'target' => '_blank')); ?></div>
<h1><?= $title_for_layout ?></h1>
<div class='column span-12'>
    <div class='column-box'>
        <h2>Which Map?</h2>
        <?php
        foreach($maps as $map) { ?>
                <h4><a href="<?= $map['Map']['url']; ?>"><?= $map['Map']['name']; ?></a> - 
                <?php if(!empty($map['Map']['last_used'])) { ?>
                <a href="<?= $map['Event'][0]['url'] ?>" target='_blank'>
                    <?= $this->TimePlus->ago($map['Map']['last_used']); ?>
                </a>
                <?php } else { ?>
                Never used
                <?php } ?>
                </h4>
            
        <?php
        }
        ?>
    </div>
</div>

<div class='column span-12 last'>
    <div class='column-box'>
    <h2>Who's Next?</h2>
    <p>A list of people who have attended at least <?= Configure::read('Event.planner.attendanceThreshold') ?> events since <?= $this->TimePlus->ago(Configure::read('Event.planner.dateThreshold')); ?>, but haven't volunteered as an organizer.</p>
    <?php foreach($users as $user) { ?>
        <h4><a href="<?= $user['User']['url']; ?>" target='_blank'><?= $user['User']['name']; ?></a> (attended <?= $user['User']['attended']; ?> events)</h4>
    <?php } ?>
    </div>
</div>
