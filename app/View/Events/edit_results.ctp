<script type="text/javascript">
$(document).ready(function(){
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

	wjr.ResultStatus = function(id, name) {
	   this.id = id;
	   if(name == null) {
	       this.name = wjr.statuses()[id];
	   } else this.name = name;
	}

	wjr.Course = function(id, name, distance, climb, event_id, description, results) {
		this.id = id;
		this.name = name;
		this.distance = distance;
		this.climb = climb;
		this.event_id = event_id;
		this.description = description;
		this.results = ko.observableArray(results);
	}

	wjr.Result = function(id, user, course_id, time, status, points, needs_ride, offering_ride) {
		this.id = id;
		this.user = user;
		this.course_id = course_id;
		this.hours = ko.observable(time ? time.substr(0, 2) : '00');
		this.minutes = ko.observable(time ? time.substr(3, 2) : '00');
		this.seconds = ko.observable(time ? time.substr(6, 2) : '00');
        this.statuses = _.values(wjr.statuses()).sort(function (a, b) { return a.name.localeCompare(b.name); });
		this.status = ko.observable(status || 'ok');
		this.points = points;
		this.needs_ride = needs_ride;
		this.offering_ride = offering_ride;
		
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
			if(!viewModel.selectedCourse()) {
				alert("Adding competitor failed, because a course wasn't selected.");
				return;
			}
			
			var user = new wjr.User(id, name);
			viewModel.selectedCourse().results.push(new wjr.Result(undefined, user, viewModel.selectedCourse().id, undefined, undefined, undefined, undefined, undefined));
		}
	};

	viewModel.addUser = function() {
		if(viewModel.userName() && viewModel.userName() !== '') {
			$.post("/Users/add", { userName: viewModel.userName(), eventId:  viewModel.event().id} );
			viewModel.userName(null);
		}
	}

	var loadObjects = function() {
		$.getJSON('/events/view/' + <?= $eventId ?> + '.json', function(data) {
			viewModel.event(new wjr.Event(data.Event.id, data.Event.name, data.Event.date));
			var courses = data["Course"];
			for(var i = 0; i < courses.length; i++) {
				var course = courses[i];

				var results = course.Result;
				var importedResults = [];

				for(var j = 0; j < results.length; j++) {
					var result = results[j];
					var user = new wjr.User(result.User.id, result.User.name);
					alert
					importedResults.push(new wjr.Result(result.id, user, result.course_id, result.time, result.status, result.points, result.needs_ride, result.offering_ride));
				}

				viewModel.courses.push(new wjr.Course(course.id, course.name, course.distance, course.climb, course.event_id, course.description, importedResults));
			}
		});
	}

	loadObjects();
	ko.applyBindings(viewModel);

    orienteerAppPersonPicker('#competitorResults', { maintainInput: false }, function(person) {
        if(person != null) {
            viewModel.addCompetitorToCourse(person.id, person.name);
        }
	});
});
</script>

<script type="text/html" id="resultTemplate">
	<tr>
		<td class="span4" data-bind="text: user.name"></td>
		<td><input type="text" class="thin-control spanning-control" maxlength="2" size="2" data-bind="value: hours" /></td>
		<td><input type="text" class="thin-control spanning-control" maxlength="2" size="2" data-bind="value: minutes" /></td>
		<td><input type="text" class="thin-control spanning-control" maxlength="2" size="2" data-bind="value: seconds" /></td>
		<td class="results-editing"><select class="input-medium thin-control" data-bind="options: statuses, optionsText: 'name', optionsValue: 'id', value: status, optionsCaption: 'Choose...'"></select></td>
		<td><button type="submit" class="btn btn-mini btn-danger" data-bind="click: remove"><i class="icon-trash icon-white"></i></button></td>
	</tr>
</script>

<script type="text/html" id="courseTemplate">
	<h2 data-bind="text: name"></h2>
	<table class="table table-striped table-condensed table-bordered">
		<thead> 
			<tr>
				<th>Name</th>
				<th>HH</th>
				<th>MM</th>
				<th>SS</th>
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
    	echo $this->Form->hidden('event', array('data-bind' => 'value: ko.toJSON(event)'));
    	echo $this->Form->hidden('courses', array('data-bind' => 'value: ko.toJSON(courses)')); ?>
    	<fieldset class="control-group">
    	   <div class="controls">
    	       <input type="hidden" name="data[Event][results_posted]" id="EventResultsPosted_" value="0">
    	       
    	       <label for="EventResultsPosted" class="checkbox inline">
    	       <input type="checkbox" name="data[Event][results_posted]" checked="checked" value="1" id="EventResultsPosted">
    	       Show Results?
    	       </label>
    	   </div>
        </fieldset>
    	<?php
    	echo $this->Form->end(array('label' => 'Save', 'class' => 'btn btn-primary', 'div' => array('class' => 'form-actions')));
    	?>
    </div>
    
    <div class="span4">
    	<div>
    		<h2>Add Competitor</h2>
    		<p>Use this to add a participant that didn't register online before the event. If they don't come up in the list, use the Create User dialog below, then use this form to add the competitor.</p>
    		<select data-bind="options: courses, optionsText: 'name', value: selectedCourse, optionsCaption: 'Choose Course..'"></select>
    		<?php echo $this->Form->input('competitorResults', array('label' => '', 'placeholder' => 'Competitor Name')); ?>
    	</div>
    	<hr/>
    	<div>
    		<h2>Create User</h2>
    		<p>You can create a user if they participated in the event, but are <b>not registered on the website yet</b>. Then use the add competitor interface for the course you are adding the user to.</p>
    		<form>
    			<div class="input submit">
    				<input type="text" placeholder="Name" data-bind="value: userName" />
    			</div>
    			<div class="input submit">
    				<input type="submit" data-bind="click: addUser" value="Create User" />
    			</div>
    		</form>
    	</div>
    </div>
</div>
