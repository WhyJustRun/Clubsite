<div class="wjr-calendar"
     data-calendar-year="<?= $year ?>"
     data-calendar-month="<?php echo(intval($month) - 1); // January = 0 ?>"
     data-calendar-day="<?= $day ?>">
</div>
<?
$icalUrl = Configure::read('Rails.domain') . "/club/" . Configure::read('Club.id') . "/events.ics";
$icalUrl = str_replace('http://', 'webcal://', $icalUrl);
$icalUrl = str_replace('https://', 'webcal://', $icalUrl);
?>
iCal feed: <a href="<?= $icalUrl ?>"><?= $icalUrl ?></a> - you can use this to get club events in your Google Calendar, Calendar app, Outlook, etc.
