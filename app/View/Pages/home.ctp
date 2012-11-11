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
            $events["Major"] = $this->element('Events/box-list', array('filter' => 'major', 'limit' => '20'));
            
            foreach($events as $title => $content) {
                if(!empty($content)) {
                    echo "<h3>${title}</h3>";
                    echo $content;
                }
            }
            ?>
            
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
