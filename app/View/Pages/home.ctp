<?php $this->set('title_for_layout', Configure::read("Club.name")); ?>

<div class="three-column row">
    <article class="col-sm-4 col-sm-push-4">
        <header>
          <h2>Events</h2>
        </header>
        <?php
        if (Configure::read('Club.layout') === 'series' ) {
            echo $this->element('Events/summary_list_by_series');
        } else {
            echo $this->element('Events/summary_list');
        }
        ?>

        <div class="event-list" data-event-list-url="<?php echo Configure::read('Rails.domain') ?>/iof/3.0/clubs/<?php echo Configure::read('Club.id') ?>/event_list.xml?start=<?php echo time() ?>&club_events=significant&external_significant_events=all">
            <h3 data-bind="visible: events().length > 0">Highlights</h3>
            <?php
            $templateName = 'event-box-template';
            ?>
            <div data-bind="template: { name: '<?php echo $templateName ?>', foreach: events }"></div>
        </div>
        <?php echo $this->element('Events/knockout_box', compact('templateName')) ?>

        <div style="text-align: center">
            <?php echo $this->element('Events/add_link'); ?>
        </div>
    </article>

    <article class="col-sm-4 col-sm-push-4">
        <header>
        <h2>News</h2>
        </header>

        <?php
        $juicerFeedId = Configure::read('Club.juicer_feed_id');
        if ($juicerFeedId) {
            echo $this->JuicerFeed->feed($juicerFeedId);
        } else if ($this->User->canEditClub()) { ?>
            <a class="btn" href="/clubs/edit">Add news source</a>
        <?php
        }

        $fbPageId = Configure::read('Club.facebook_page_id');
        if (!$fbPageId) {
            $fbPageId = Configure::read('Facebook.defaultPageID');
        }

        echo $this->FacebookGraph->like("http://www.facebook.com/".$fbPageId);
        ?>
    </article>
    <article class="col-sm-4 col-sm-pull-8">
        <?php echo $this->ContentBlock->render('general_information'); ?>
    </article>
</div>
