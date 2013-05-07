<script type="text/javascript">

function zeroFill(number, width) {
    width -= number.toString().length;
    if (width > 0) {
      return new Array(width + (/\./.test(number) ? 2 : 1)).join('0') + number;
    }
    return number + "";
}

$(document).ready(function() {
    var wjr = {};

    wjr._statuses = null;
    wjr.statuses = function() {
        if(wjr._statuses == null) {
            map = {
            <? foreach(Configure::read('Result.statuses') as $k => $v) {
            echo "'$k': '$v',\n";
            } ?>
            };
            wjr._statuses = {}
            _.each(map, function(k, v) {
                wjr._statuses[k] = new wjr.ResultStatus(v, k);
            });
        }
       
       return wjr._statuses;
    }
    
    wjr.sorted_statuses = function() {
       return _.values(wjr.statuses()).sort(function (a, b) { return a.name.localeCompare(b.name); });
    }

    wjr.ResultStatus = function(id, name) {
       this.id = id;
       if(name == null) {
           this.name = wjr.statuses()[id];
       } else this.name = name;
    }

    wjr.Course = function(id, name, distance, climb, event_id, description, results, is_score_o) {
        this.id = id;
        this.name = name;
        this.distance = distance;
        this.climb = climb;
        this.event_id = event_id;
        this.description = description;
        this.results = ko.observableArray(results);
        this.is_score_o = ko.observable(is_score_o);
    }

    wjr.Result = function(id, user, course_id, time_seconds, status, points, needs_ride, offering_ride, score_points, is_score_o) {
        this.id = id;
        this.user = user;
        this.course_id = course_id;
        
        var hoursCount = Math.floor(time_seconds / 3600);
        var minutesCount = Math.floor((time_seconds - hoursCount * 3600) / 60);
        var secondsCount = time_seconds - hoursCount * 3600 - minutesCount * 60;
        var millisecondsCount = Math.round((secondsCount % 1) * 1000);
        
        this.hours = ko.observable(time_seconds ? zeroFill(hoursCount, 2) : '00');
        this.minutes = ko.observable(time_seconds ? zeroFill(minutesCount, 2) : '00');
        this.seconds = ko.observable(time_seconds ? zeroFill(Math.floor(secondsCount), 2) : '00');
        this.milliseconds = ko.observable(time_seconds ? zeroFill(millisecondsCount, 3) : '000');
        this.statuses = function() {
            return wjr.sorted_statuses();
        }
        this.status = ko.observable(status || 'ok');
        this.points = points;
        this.needs_ride = needs_ride;
        this.offering_ride = offering_ride;
        this.is_score_o = ko.observable(is_score_o);
        this.score_points = ko.observable(score_points);
        
        this.remove = function() {
            if(this.id) {
                if(!confirm("Are you sure you want to delete this competitor?")) {
                    return;
                }
                $.ajax('/results/delete/' + this.id);
            }

            var courses = viewModel.courses();
            for(var i = 0; i < viewModel.courses().length; i++) {
                courses[i].results.remove(this);
            }
        }
    }

    wjr.User = function(id, name) {
        this.id = id;
        this.name = name;
    }

    wjr.Event = function(id, name, date) {
        this.id = id;
        this.name = name;
        this.date = date;
    }

    var viewModel = {
        courses : ko.observableArray(),
        event : ko.observable(),
        userName: ko.observable(),
        selectedCourse: ko.observable(),
        addCompetitorToCourse: function(id, name) {            
            if (!viewModel.selectedCourse()) {
                alert("Adding competitor failed, because a course wasn't selected.");
                return;
            }
            
            if (id === undefined) {
                // create user asynchronously, then add to the UI once we've go the user ID.
                var options = {
                    userName: name,
                    eventId: viewModel.event().id,
                };
                var successHandler = function (userID) {
                    viewModel.addCompetitorToCourse($.parseJSON(userID), name);
                };
                $.post("/Users/add", options, successHandler);
            } else {
                var user = new wjr.User(id, name);
                viewModel.selectedCourse().results.push(new wjr.Result(undefined, user, viewModel.selectedCourse().id, undefined, undefined, undefined, undefined, undefined, undefined, viewModel.selectedCourse().is_score_o()));
            }
        }
    };

    var loadObjects = function() {
        $.getJSON('/events/view/<?= $eventId ?>.json', function(data) {
            viewModel.event(new wjr.Event(data.Event.id, data.Event.name, data.Event.date));
            var courses = data["Course"];
            for(var i = 0; i < courses.length; i++) {
                var course = courses[i];

                var results = course.Result;
                var importedResults = [];

                for(var j = 0; j < results.length; j++) {
                    var result = results[j];
                    var user = new wjr.User(result.User.id, result.User.name);
                    importedResults.push(new wjr.Result(result.id, user, result.course_id, result.time_seconds, result.status, result.points, result.needs_ride, result.offering_ride, result.score_points, course.is_score_o));
                }

                viewModel.courses.push(new wjr.Course(course.id, course.name, course.distance, course.climb, course.event_id, course.description, importedResults, course.is_score_o));
            }
        });
    }

    loadObjects();
    ko.applyBindings(viewModel);

    orienteerAppPersonPicker('#competitorResults', { maintainInput: false, createNew: true }, function(person) {
        if(person != null) {
            viewModel.addCompetitorToCourse(person.id, person.name);
        }
    });
    
    
});
</script>

<script type="text/html" id="resultTemplate">
    <tr>
        <td class="span4" data-bind="text: user.name"></td>
        <td data-bind="visible: is_score_o"><input type="number" class="thin-control spanning-control" data-bind="value: score_points"></td>
        <td>
            <div class="input-prepend input-append">
                <input type="text" class="thin-control time-segment" maxlength="2" size="2" data-bind="value: hours" pattern="[0-9]{0,2}" />
                <span class="add-on thin-control time-spacer">:</span>
                <input type="text" class="thin-control time-segment" maxlength="2" size="2" data-bind="value: minutes" pattern="[0-9]{0,2}" />
                <span class="add-on thin-control time-spacer">:</span>
                <input type="text" class="thin-control time-segment" maxlength="2" size="2" data-bind="value: seconds" pattern="[0-9]{0,2}" />
                <span class="add-on thin-control time-spacer">.</span>
                <input type="text" class="thin-control millisecond-time-segment" maxlength="3" size="3" data-bind="value: milliseconds" pattern="[0-9]{0,3}"/>
            </div>
        </td>
        <td class="results-editing"><select class="input-medium thin-control" data-bind="options: statuses(), optionsText: 'name', optionsValue: 'id', value: status, optionsCaption: 'Choose...'"></select></td>
        <td><button type="submit" class="btn btn-mini btn-danger" data-bind="click: remove"><i class="icon-trash icon-white"></i></button></td>
    </tr>
</script>

<script type="text/html" id="courseTemplate">
    <h2 data-bind="text: name"></h2>
    <table class="table table-striped table-condensed table-bordered">
        <thead> 
            <tr>
                <th>Name</th>
                <th data-bind="visible: is_score_o">Score Points</th>
                <th>Time (hh:mm:ss)</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody data-bind="template: {name:'resultTemplate', foreach: results}">
        </tbody>
    </table>
</script>

<header class="page-header">
    <h1>Edit Results/Registrations <small data-bind="text: event() ? event().name : ''"></small></h1>
</header>
<div class="row">
    <div class="span8">
        <div data-bind="template: {name:'courseTemplate', foreach: courses}">
        </div>
        <?php
        echo $this->Form->create('Event', array('url' => "/events/editResults/${eventId}"));
        echo $this->Form->hidden('courses', array('data-bind' => 'value: ko.toJSON(courses)'));
        $this->Form->unlockField('Event.courses');
        ?>
        <fieldset class="control-group">
           <div class="controls">
               
               <label for="EventResultsPosted" class="checkbox inline">
               <?= $this->Form->checkbox('results_posted', array('checked' => 'checked')) ?>
               Show Results?
               </label>
           </div>
        </fieldset>
        <?php
        echo $this->Form->end(array('label' => 'Save', 'class' => 'btn btn-primary', 'div' => array('class' => 'form-actions')));
        ?>
    </div>
    
    <div class="span4">
          <h2>Add Competitor</h2>
          <p>Choose the course you would like to add a competitor. <br/>Type in the participant's name and choose the matching person. If they are not already in the system, choose the "Create New User" option.</p>
          <select data-bind="options: courses, optionsText: 'name', value: selectedCourse, optionsCaption: 'Choose Course..'"></select>
          <?php echo $this->Form->input('competitorResults', array('label' => '', 'placeholder' => 'Competitor Name')); ?>
    </div>
</div>

