<?php $this->set('title_for_layout', Configure::read("Club.name")); ?>

<div class="three-column row">
    <div class="not-mobile-pull-right">
        <article class="span4">
            <header>
              <h2>Events</h2>
            </header>
            <?php 
            $events = array();
            $events["Upcoming"] = $this->element('Events/box-list', array('filter' => 'upcoming', 'limit' => '4'));
            $events["Past"] = $this->element('Events/box-list', array('filter' => 'past', 'limit' => '2'));

            foreach($events as $title => $content) {
                if(!empty($content)) {
                    echo "<h3>${title}</h3>";
                    echo $content;
                }
            }
            ?>

            <?= $this->Html->script('event_viewer'); ?>
            <div class="event-list" data-event-list-url="<?= Configure::read('Rails.domain') ?>/iof/3.0/clubs/<?= Configure::read('Club.id') ?>/event_list/significant.xml?start=<?= time() ?>">
            <h3 data-bind="visible: events().length > 0">Highlights</h3>
            <div data-bind="foreach: events">
                <div class="event-box">
                    <a data-bind="attr: { href: url }">
                        <div class="event-box-inner">
                            <div class="event-box-left">
                                <div class="location series-2" data-bind="style: { color: series.color }">
                                    <span data-bind="text: name"></span>
                                    <span class="label label-info event-box-classification" data-bind="text: classification"></span>
                                </div>
                                <div class="date" data-bind="text: date"></div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            
            <div style="text-align: center">
                <?= $this->element('Events/add_link'); ?>
            </div>
        </article>
        
        <article class="span4">
            <header>
            <h2>News</h2>
            </header>
            
            <?= $this->FacebookGraph->feed('news', array('limit' => 5)); ?>
        </article>
    </div>
    <article class="span4 pull-left">
        <?= $this->ContentBlock->render('general_information'); ?>
        <?= $this->FacebookGraph->like(); ?>
    </article>
</div>
