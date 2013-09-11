<?php
$tz = Configure::read('Club.timezone');
$startDate = new DateTime($event["Event"]["date"], $tz);
$finishDate = $event['Event']['finish_date'] ? new DateTime($event["Event"]["finish_date"], $tz) : null;
if($event["Event"]["finish_date"] != NULL) {
    if($startDate->format('D F jS') === $finishDate->format('D F jS')) {
        $dateFormatted = $startDate->format('F jS Y g:ia') . " - " . $finishDate->format('g:ia');
    } else {
        $dateFormatted = $startDate->format('F jS Y g:ia') . " - " . $finishDate->format('F jS Y g:ia');
    }
} else {
    $dateFormatted = $startDate->format('F jS Y g:ia');
}

$dynamicText = $dateFormatted;
$seriesName = $event['Series']['name'];
if (!empty($seriesName)) {
    $dynamicText .= "
Series: $seriesName";
}


$this->OpenGraph->addTag("og:type", "event");
$this->OpenGraph->addTag("og:url", $this->Html->url($event['Event']['url'], true));
$this->OpenGraph->addTag("og:description", "$dynamicText
Orienteering is an exciting sport for all ages and fitness levels that involves reading a detailed map and using a compass to find checkpoints.");
$this->OpenGraph->addTag("og:title", $event['Event']['name']);
$this->OpenGraph->addTag("og:image", $this->Html->url('/img/orienteering_symbol.png', true));
$tz = Configure::read('Club.timezone');
$this->OpenGraph->addTag("event:start_time", $startDate->format(DateTime::ISO8601));
if ($finishDate) {
    $this->OpenGraph->addTag('event:end_time', $finishDate->format(DateTime::ISO8601));
}
if (!empty($event['Event']['lat'])) {
    $this->OpenGraph->addTag("event:location:latitude", $event['Event']['lat']);
}

if (!empty($event['Event']['lng'])) {
    $this->OpenGraph->addTag("event:location:longitude", $event['Event']['lng']);
}
?>

<header class="page-header">
<div class="pull-right btn-toolbar">
    <div class="btn-group">
        <a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="#">
            <i class="icon-download-alt icon-white"></i> Export
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <?php if($edit) { ?>
            <li><a href="/events/printableEntries/<?= $event['Event']['id'] ?>">Printable List</a></li>
            <?php } ?>
            <li><a href="<?= Configure::read('Rails.domain') ?>/iof/2.0.3/events/<?= $event['Event']['id'] ?>/start_list.xml">Start List (IOF XML 2)</a></li>
            <li><a href="<?= Configure::read('Rails.domain') ?>/iof/2.0.3/events/<?= $event['Event']['id'] ?>/result_list.xml">Result List (IOF XML 2)</a></li>
            <li><a href="<?= Configure::read('Rails.domain') ?>/iof/3.0/events/<?= $event['Event']['id'] ?>/result_list.xml">Result List (IOF XML 3)</a></li>
            <li><a href="<?= Configure::read('Rails.domain') ?>/iof/3.0/events/<?= $event['Event']['id'] ?>/entry_list.xml">Entry List (IOF XML 3)</a></li>
        </ul>
    </div>
    <?php if($edit) { ?>
    <div class="btn-group">
        <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
            <i class="icon-cog icon-white"></i> Edit
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li><a href="/events/edit/<?= $event['Event']['id'] ?>">Event</a></li>
            <li><a href="/events/edit/<?= $event['Event']['id'] ?>">Courses</a></li>
            <li><a href="/events/editResults/<?= $event['Event']['id'] ?>">Registrations/Results</a></li>
            <li><a href="/events/uploadMaps/<?= $event['Event']['id'] ?>">Course Maps</a></li>
        </ul>
    </div>

    <div class="btn-group">
        <a class="btn btn-danger" href="/events/delete/<?= $event['Event']['id'] ?>" onclick='return confirm("Delete this event (including any defined organizers, courses and results)?");'><i class="icon-trash icon-white"></i></a>
    </div>
    <?php } ?>
</div>

<h1 class="series-<?= $event["Series"]["id"]; ?> event-header"><?= $event["Event"]["name"]; ?> <small class="series-<?= $event["Series"]["id"]; ?> event-header"><?= $event["Series"]["name"] ?></small></h1>

<h2 class="event-header"><?php 
    echo $dateFormatted;
    ?></h2>
<? if(!empty($event["Event"]["custom_url"])) {?>
<div class="danger alert alert-danger">
    <h3 class="event-header">This event has an external website: <?= $this->Html->link($event["Event"]["custom_url"])?></h3>
</div>
<? } ?>
</header>

<div class="row">
    <?php if(!$event["Event"]["results_posted"] && !$event['Event']['results_url'] && !$event['Event']['routegadget_url']) { 
    // Show event information
    ?>
    <div class="span8">
        <?php echo $this->element('Events/info', array('event' => $event)); ?>
    </div>

    <?php
    $registrationURL = $event['Event']['registration_url'];
    if($event["Event"]["completed"] === true) { ?>
    <div class="results span4">
        <?php echo $this->element('Courses/course_maps', array('courses' => $event["Course"])); ?>
    </div>
    <?php } else if($registrationURL) { ?>
    <div class="results span4">
        <header>
        <h2>Registration</h2>
        </header>
        <div class="btn-group">
            <a href="<?= $registrationURL ?>" class="btn btn-large btn-success">
                Register at <?= parse_url($registrationURL, PHP_URL_HOST) ?>
            </a>
        </div>
    </div>
    <?php } else if(count($event["Course"]) > 0) { ?>
    <div class="results span4">
        <header>
        <h2>Course Registration</h2>
        </header>
        <div class="courses">
            <?php 
            $userId = $this->Session->read('Auth.User.id');
            $userId = empty($userId) ? 0 : $userId;
            foreach($event["Course"] as $course) { ?>
            <div class="course">
                <div class="course-info">
                    <div class="pull-right">
                        <?php if($course["registered"] === false) { ?>
                        <div class="btn-group">
                            <a class="btn btn-success" href="/courses/register/<?= $course['id'] ?>/<?= $userId ?>"><i class="icon-plus icon-white"></i> Register</a>
                            <a class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="/courses/register/<?= $course['id'] ?>/<?= $userId ?>/needsRide"><i class="icon-user"></i> Register (Need ride)</a></li>
                                <li><a href="/courses/register/<?= $course['id'] ?>/<?= $userId ?>/offeringRide"><i class="icon-road"></i> Register (Offer ride)</a></li>
                            </ul>
                        </div>
                        <?php } ?>
                    </div>

                    <h3><?= $course["name"] ?></h3>
                    <span>
                        <?= $course["description"] ?>
                        <p>
                        <?php
                        if(!empty($course["distance"])) {
                        echo "<br/><strong>Distance</strong>: ${course['distance']}m";
                        }
                        if(!empty($course["climb"])) {
                        echo "<br/><strong>Climb</strong>: ${course['climb']}m";
                        }
                        ?>
                        </p>
                    </span>
                </div>
                <div class="results-list">
                    <?php echo $this->element('Results/list', array('course' => $course, 'results' => $course["Result"])); ?>
                </div>
            </div>
            <?php } ?>
        </div>
        <?php if(!empty($event["Course"])) { ?>
        <h3>Register Others</h3>
        <?php if(empty($userId)) { ?>
        <a href="/users/login" class="btn btn-primary">Sign in</a>
        <?php } else { ?>
        <p>To register someone else, choose the course to register them on, then type their name, and pick the person from the drop down list.</p>
        <p><strong>Families</strong>: you don't need to register an account for every family member, just type the participating people's names below and an account will automatically be created for each person.</p>
        <select id="RegisterOthersCourse">
            <?php foreach($event['Course'] as $course) { ?>
            <option value="<?= $course['id'] ?>"><?= $course['name'] ?></option>
            <?php } ?>
        </select>
        <script type="text/javascript">
            $(function() {
                    $('#RegisterOthersUserId').val(null);

                    orienteerAppPersonPicker('#RegisterOthersUserName', { maintainInput: true, allowNew: true }, function(person) {
                        if(person != null) {
                        $('#RegisterOthersUserId').val(person.id);
                        } else {
                        $('#RegisterOthersUserId').val(null);
                        }
                        });

                    function completeSubmit(courseId, userId) {
                    location.href = "/courses/register/" + courseId + "/" + userId;
                    }

                    $('#RegisterOthersSubmit').click(function() {
                        var userId = $('#RegisterOthersUserId').val();
                        var courseId = $('#RegisterOthersCourse').val();
                        if(!userId) {
                        var userName = $('#RegisterOthersUserName').val();
                        if(userName) {
                        if(userName.indexOf(" ") != -1) {
                        if(confirm("This registration will create a new user in the system. Are you sure " + userName + " isn't already an OrienteerApp user?")) {
                        $.post('/users/add', { userName: userName }, function(data) {
                            completeSubmit(courseId, $.parseJSON(data))
                            });
                        } else {
                        alert("Thanks! Please re-enter the participant's name and choose the matching person from the dropdown.");
                        }
                        } else {
                        alert("Please enter the participant's full name.");
                        }
                        } else {
                        alert("Please enter the participant name");
                        }
                        } else {
                            completeSubmit(courseId, userId);
                        }
                    });
            });
        </script>
        <input type="hidden" id="RegisterOthersUserId" />
        <input placeholder="Participant Name" type="text" id="RegisterOthersUserName" /><br/>
        <button id="RegisterOthersSubmit" class="btn btn-success"><i class="icon-plus icon-white"></i> Register</button>
        <?php } ?>
        <?php } ?>
    </div>
    <?php } ?>
    <?php } else {
    // Show results page
    ?>
    <div class="span6">
        <div class="results">
            <header>
            <h2>Results</h2>
            </header>

            <?php echo $this->element('Events/files', array('id' => $event["Event"]["id"])); ?>
            <?php if ($event['Event']['results_posted']) { ?>
            <div class="results-list">
                <?= $this->Html->script('result_viewer'); ?>                  
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
                                        <th data-bind="visible: isScore">Score Points</th>
                                        <th>Time</th>
                                        <th data-bind="visible: isTimed">Points</th>
                                    </tr>
                                </thead>
                                <tbody data-bind="foreach: results">
                                    <tr>
                                        <td data-bind="text: position || friendlyStatus"></td>
                                        <td><a data-bind="attr: { href: person.profileUrl }"><span data-bind="text: person.givenName + ' ' + person.familyName"></span></a></td>
                                        <td data-bind="visible: $parent.isScore, text: scores['Points']"></td>
                                        <td data-bind="text: time != null ? hours + ':' + minutes + ':' + seconds + ($parent.millisecondTiming ? '.' + milliseconds : '' ) : ''"></td>
                                        <td data-bind="visible: $parent.isTimed, text: scores['WhyJustRun']"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
            <?php
            $urlKeys = array('results_url' => 'Results at ', 'routegadget_url' =>  'RouteGadget at ');
            foreach ($urlKeys as $urlKey => $titlePrefix) {
                if (!empty($event['Event'][$urlKey]) && $url = $event['Event'][$urlKey]) { ?>
                    <div class="btn-group">
                        <a href="<?= $url ?>" class="btn btn-primary"><?= $titlePrefix.parse_url($url, PHP_URL_HOST) ?></a>
                    </div>
                    <br/><br/>
            <?php
                }
            } ?>
            <?= $this->element('Courses/course_maps', array('courses' => $event["Course"])); ?>
        </div>
    </div>
    <div class="span6">
        <?php echo $this->element('Events/info', array('event' => $event)); ?>
    </div>
    <?php } ?>
</div>

<hr>

<div id="flickr-photos-container" class="photos-grid">
    <h2>Photos</h2>
    <p>Photos are from Flickr. To add your photos to this section, tag your Flickr photos with: <span class="label label-success" style="vertical-align: baseline">orienteerapp<?= $event['Event']['id'] ?></span> (all one word)</p>
    <ul id="flickr-photos" class="thumbnails" data-bind="foreach: photos">
        <li class="span3">
        <a data-bind="attr: { href: '#flickrPhoto' + id, onclick: 'loadFlickrImage(\'' + id + '\',\'' + largeUrl + '\')' }" class="thumbnail">
            <div data-bind="style: { backgroundImage: 'url(' + thumbnailUrl + ')' }">

            </div>
        </a>

        <div data-bind="attr: { id: 'flickrPhoto' + id }" style="display:none; ">
            <div class="pull-right">
                Taken: <span data-bind='text: dateTaken'></span><br/>
                By: <span data-bind='text: ownerName'></span>
            </div>
            <a class='btn btn-large btn-primary' data-bind='attr: { href: page }'>Photo on Flickr</a>
            <br/><br/>
            <img src="" />
        </div>
        </li>
    </ul>
</div>

<script type="text/javascript">
    function loadFlickrImage(id, largeUrl) {
        if($("#flickrPhoto" + id + " img").attr('src') == "") {
            $("#flickrPhoto" + id + " img").attr('src', largeUrl);
            $.fancybox.showLoading();

            $("#flickrPhoto" + id + " img").load(function() {
                    $.fancybox.hideLoading();
                    $.fancybox.open("#flickrPhoto" + id);
                    });
        } else {
            $.fancybox.open("#flickrPhoto" + id);
        }
    }

var photoViewModel = {
photos: ko.observableArray()
};

function jsonFlickrApi(results) {
    $(function() {
            if(results.stat == "ok") {
            _.each(results.photos.photo, function(photo) {
                photoViewModel.photos.push({
id: photo.id,
page: "http://www.flickr.com/photos/" + photo.owner + "/" + photo.id,
thumbnailUrl: "http://farm" + photo.farm + ".staticflickr.com/" + photo.server + "/" + photo.id + "_" + photo.secret + ".jpg", 
largeUrl: "http://farm" + photo.farm + ".staticflickr.com/" + photo.server + "/" + photo.id + "_" + photo.secret + "_b.jpg",
ownerName: photo.ownername,
description: photo.description,
dateTaken: photo.datetaken
});
                });
            }
            });
}


$(function() {
        var count = 2;

        $(window).scroll(function() {
            if($(window).scrollTop() == $(document).height() - $(window).height()) {
            loadMorePhotos(count);
            count++;
            }
            });

        function loadMorePhotos(page) {
        var script = document.createElement('script');
        var url = "http://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=<?= Configure::read('Flickr.apiKey') ?>&tags=<?= urlencode("orienteerapp".$event['Event']['id']) ?>&format=json&extras=date_taken,description,owner_name&per_page=30&page=" + page;
        script.type = 'text/javascript';
        script.src = url;
        $("#flickr-photos-container").append(script);
        }

        loadMorePhotos(1);
        ko.applyBindings(photoViewModel, document.getElementById('flickr-photos-container'));
        });

</script>

<script src="" type="text/javascript"></script>

