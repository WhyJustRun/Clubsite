<script type="text/javascript">
$(document).ready(function(){
	var wjr = {};

	wjr.Course = function(id, name, distance, climb, event_id, description, results) {
		this.id = id;
		this.name = name;
		this.distance = distance;
		this.climb = climb;
		this.event_id = event_id;
		this.description = description;
		this.results = ko.observableArray(results);
	}

	wjr.Result = function(id, user, course_id, time, non_competitive, points, needs_ride, offering_ride) {
		this.id = id;
		this.user = user;
		this.course_id = course_id;
		this.hours = ko.observable(time ? time.substr(0, 2) : '00');
		this.minutes = ko.observable(time ? time.substr(3, 2) : '00');
		this.seconds = ko.observable(time ? time.substr(6, 2) : '00');
		this.non_competitive = ko.observable(non_competitive);
		this.points = points;
		this.needs_ride = needs_ride;
		this.offering_ride = offering_ride;
		
		this.remove = function() {
			if(this.id) {
				if(!confirm("Are you sure you want to delete this competitor?")) {
					return;
				}
				$.ajax('/Results/delete/' + this.id);
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
		$.getJSON('/Events/view/' + <?= $eventId ?> + '.json', function(data) {
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
					importedResults.push(new wjr.Result(result.id, user, result.course_id, result.time, result.non_competitive, result.points, result.needs_ride, result.offering_ride));
				}

				viewModel.courses.push(new wjr.Course(course.id, course.name, course.distance, course.climb, course.event_id, course.description, importedResults));
			}
		});
	}

	loadObjects();
	ko.applyBindings(viewModel);

	$("#competitorResults").autocomplete({
		source: "/Users/index.json",
		minLength: 2,
		select: function(event, ui) {
			viewModel.addCompetitorToCourse(ui.item.id, ui.item.value);
		},
		close: function(event, ui) {
			$(this).val(null);
		}
	});
});
</script>

<script type="text/x-jquery-tmpl" id="resultTemplate">
	<tr style="height: 18px; font-size: x-small; padding: 0">
		<td>${user.name}</td>
		<td class="results-editing"><input type="text" maxlength="2" size="2" data-bind="value: hours" /></td>
		<td class="results-editing"><input type="text" maxlength="2" size="2" data-bind="value: minutes" /></td>
		<td class="results-editing"><input type="text" maxlength="2" size="2" data-bind="value: seconds" /></td>
		<td class="results-editing"><input type="checkbox" data-bind="checked: non_competitive" /></td>
		<td><div class="unsubmit" style="text-align: right; padding-right: 10px"><input type="submit" data-bind="click: remove" value="Remove" /></div></td>
	</tr>
</script>

<script type="text/x-jquery-tmpl" id="courseTemplate">
	<div class="input">
		<h2>${name}</h2>
		
		<table>
			<thead> 
				<tr>
					<th>Name</th>
					<th>HH</th>
					<th>MM</th>
					<th>SS</th>
					<th>NC</th>
					<th></th>
				</tr>
			</thead>
			<tbody data-bind="template: {name:'resultTemplate', foreach: results}">
			</tbody>
		</table>
	</div>
</script>

<h1 data-bind="text: event() ? event().name : ''"></h1>
<div class="column narrow-form">
    <div class='padded'>
        <div data-bind="template: {name:'courseTemplate', foreach: courses}">
    	</div>
    	<?php
    	echo $this->Form->create('Event', array('url' => "/events/editResults/${eventId}"));
    	echo $this->Form->hidden('event', array('data-bind' => 'value: ko.toJSON(event)'));
    	echo $this->Form->hidden('courses', array('data-bind' => 'value: ko.toJSON(courses)'));
    	echo $this->Form->input('results_posted', array('label' => 'Show Results?', 'checked' => 'checked', 'type' => 'checkbox'));
    	echo $this->Form->end('Save');
    	?>
    </div>
	
</div>

<div class="column last" style="width: 300px; padding: 20px">
	<div>
		<h2>Add Competitor</h2>
		<p>Use this to add a participant that didn't register online before the event. If they don't come up in the list, use the Create User dialog below, then use this form to add the competitor.</p>
		<select data-bind="options: courses, optionsText: 'name', value: selectedCourse, optionsCaption: 'Choose Course..'"></select>
		<?php echo $this->Form->input('competitorResults', array('label' => '', 'placeholder' => 'Competitor Name')); ?>
	</div>
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