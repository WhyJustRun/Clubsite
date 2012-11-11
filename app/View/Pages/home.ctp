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
            
            <!--
<div id="list" class="result-list" data-result-list-url="<?= Configure::read('Rails.domain') ?>/iof/3.0/events/<?= $event['Event']['id'] ?>/result_list.xml">
                        <div data-bind="foreach: courses">
                			<h3 data-bind="text: name"></h3>
                			<div data-bind="if: results().length == 0">
                				<p><b>No results</b></p>
                			</div>
                			<div data-bind="if: results().length > 0">
                				<table class="table table-striped table-bordered table-condensed">
                					<thead>
                						<tr>
                							<th>#</th>
                							<th>Participant</th>
                							<th>Time</th>
                							<th>Points</th>
                						</tr>
                					</thead>
                					<tbody data-bind="foreach: results">
                						<tr>
                							<td data-bind="text: position || friendlyStatus"></td>
                							<td><a data-bind="attr: { href: person.profileUrl }"><span data-bind="text: person.givenName + ' ' + person.familyName"></span></a></td>
                							<td data-bind="text: time != null ? hours + ':' + minutes + ':' + seconds : ''"></td>
                							<td data-bind="text: scores['WhyJustRun']"></td>
                						</tr>
                					</tbody>
                				</table>
                			</div>
                		</div>
                    </div>
-->

            <?= $this->Html->script('event_viewer'); ?>
            <div class="event-list" data-event-list-url="<?= Configure::read('Rails.domain') ?>/iof/3.0/clubs/<?= Configure::read('Club.id') ?>/event_list/significant.xml?start=<?= time() ?>">
            <h3 data-bind="visible: events().length > 0">Highlights</h3>
            <div data-bind="foreach: events">
                <div class="event-box">
                    <a data-bind="attr: { href: url }">
                        <div class="event-box-inner">
                            <div class="event-box-left">
                                <div class="location series-2" data-bind="text: name, style: { color: series.color }"></div>
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
