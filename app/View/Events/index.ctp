<div class="page-header">
<div class="pull-right">
    <?= $this->element('Events/add_link') ?>
</div>
    <h1>Calendar</h1>
</div>
<?php $this->Html->script("fullcalendar", array('block' => 'secondaryScripts'));
echo $this->Html->scriptStart(array('block' => 'secondaryScripts'));
?>
$(function() {
    var initialLoad = true;    
    var poppingState = false;
    
    $('#calendar').fullCalendar({
        eventSources: ['<?= Configure::read('Rails.domain'); ?>/club/<?= Configure::read('Club.id') ?>/events.json',
        '<?= Configure::read('Rails.domain') ?>/club/<?= Configure::read('Club.id') ?>/events.json?list_type=significant&prefix_club_acronym=1&only_non_club=1'],
        year: <?= $year ?>,
        month: <?php echo(intval($month) - 1); // January = 0 ?>,
        date: <?= $day ?>,
        timeFormat: 'h:mmtt',
        firstDay: 1,
        // From google code discussion
        viewDisplay : function(view) {
        // IE doesn't support replaceState..
        if (window.history.replaceState) {
                //I had to do a little bit of juggling to get it to only run items when necessary
                //There might be a better way to do this but I couldn't find one
                if (initialLoad) { //Replace the current state to set up state variables.  URL should be identical
                    history.replaceState({ viewMode:view.name, start:view.start }, "Event Calendar", "/events/index/" + $.fullCalendar.formatDate(view.start, 'dd-MM-yyyy'));                    
                    window.onpopstate = function(event) { //set up onpopstate handler
                        if (!initialLoad) { //the browser kept trying to pop the state on intial load
                            var start = event.state.start;
                            if (typeof(start) == 'string') { //even though i stored a date object, it was coming back as a string for some reason
                                start = $.fullCalendar.parseDate(start);
                            }
                            poppingState = true; //don't re-push state
                            $('#calendar').fullCalendar('gotoDate', start);
                            poppingState = true; //don't re-push state
                            $('#calendar').fullCalendar('changeView', event.state.viewMode);
                        }
                        initialLoad = false;
                    };
                } else {
                    if (!poppingState) { 
                        history.pushState({ viewMode:view.name, start:view.start }, "Event Calendar", "/events/index/" +  $.fullCalendar.formatDate(view.start, 'dd-MM-yyyy'));                    
                    } else {
                        poppingState = false;
                    }
                }
            }
        }
    });
});
<?php
$this->Html->scriptEnd();
?>
<div id="calendar">
    
</div>
<?
$icalUrl = "http://whyjustrun.ca/club/".Configure::read('Club.id')."/events.ics";
?>
iCal feed: <a href="<?= $icalUrl ?>"><?= $icalUrl ?></a> - you can use this to get club events in your Google Calendar, Calendar app, Outlook, etc.

