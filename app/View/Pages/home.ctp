<?php $this->set('title_for_layout', Configure::read("Club.name")); ?>

<div class="three-column row">
    <article class="col-sm-4 col-sm-push-4">
        <header>
          <h2>Events</h2>
        </header>
        <?php 
        $events = array();
        $events["Ongoing"] = $this->element('Events/box-list', array('filter' => 'ongoing', 'limit' => '-1'));
        $events["Upcoming"] = $this->element('Events/box-list', array('filter' => 'upcoming', 'limit' => '4'));
        $events["Past"] = $this->element('Events/box-list', array('filter' => 'past', 'limit' => '2'));

        foreach($events as $title => $content) {
            if(!empty($content)) {
                echo "<h3>${title}</h3>";
                echo $content;
            }
        }
        ?>

        <?php
        $this->Html->script('event_viewer', array('block' => 'secondaryScripts'));
        ?>
        <div class="event-list" data-event-list-url="<?= Configure::read('Rails.domain') ?>/iof/3.0/clubs/<?= Configure::read('Club.id') ?>/event_list.xml?start=<?= time() ?>&club_events=none&external_significant_events=all">
            <h3 data-bind="visible: events().length > 0">Highlights</h3>
            <?php
            $templateName = 'event-box-template';
            ?>
            <div data-bind="template: { name: '<?= $templateName ?>', foreach: events }"></div>
        </div>
        <?= $this->element('Events/knockout_box', compact('templateName')) ?>
        
        <div style="text-align: center">
            <?= $this->element('Events/add_link'); ?>
        </div>
    </article>
    
    <article class="col-sm-4 col-sm-push-4">
        <header>
        <h2>News</h2>
        </header>
        
        <?php
        $wjrPageId = Configure::read('Facebook.appPageId');
        $fbPageId = Configure::read('Club.facebook_page_id');
        if (!$fbPageId) {
            if ($this->User->canEditClub()) { ?>
            <a class="btn" href="/clubs/edit">Customize Facebook Page source</a>
            <?php
            }
            $fbPageId = Configure::read('Facebook.defaultPageID');
        }
        
        echo $this->FacebookGraph->feed($fbPageId, array('limit' => 5));
        $facebookDomain = "http://www.facebook.com/";
        if ($fbPageId) {
            echo $this->FacebookGraph->like($facebookDomain.$fbPageId);
        }

        if ($wjrPageId != $fbPageId) {
            echo $this->FacebookGraph->like($facebookDomain.$wjrPageId);
        }
        ?>
    </article>
    <article class="col-sm-4 col-sm-pull-8">
        <?= $this->ContentBlock->render('general_information'); ?>
    </article>
</div>

