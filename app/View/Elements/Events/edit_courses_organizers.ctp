<script type="text/javascript">
var availableRoles = [];

// Load up data from the server
$.getJSON('/roles/index.json', function(data) {
	for(var i = 0; i < data.length; i++) {
		var availableRole = {};
		availableRole.id = data[i].id;
		availableRole.name = data[i].name;
		availableRoles.push(availableRole);
	}

	$(document).ready(function(){
		finishLoadingOrganizers();
	});
});

var roleById = function(id) {
	for(var i = 0; i < availableRoles.length; i++) {
		if(availableRoles[i].id == id) {
			return availableRoles[i];
		}
	}
}

var finishLoadingOrganizers = function() {
	var Course = function(id, name, distance, climb, description) {
		this.id = id;
		this.name = ko.observable(name);
		this.distance = ko.observable(distance);
		this.climb = ko.observable(climb);
		this.description = ko.observable(description);
		this.remove = function() {
			// Only need to confirm for deletion if the course hasn't been created locally
			if(this.id != null) {
				if(confirm("Are you sure you want to delete this course? This will also delete any results associated to the course.")) {
					$.ajax('/courses/delete/' + this.id);
					viewModel.courses.remove(this);
				}
			} else {
				viewModel.courses.remove(this);
			}
		}
	}
		
	var Organizer = function(id, name, roleId) {
		this.id = id;
		this.name = ko.observable(name);
		this.availableRoles = ko.observableArray(availableRoles);
		this.role = ko.observable(roleById(roleId));
		this.remove = function() {
			viewModel.organizers.remove(this);
		}
	}
	var viewModel = {
		organizers: ko.observableArray([]),
		addOrganizer: function(id, name, roleId) {
			this.organizers.push(new Organizer(id, name, roleId));   
		},
		courses: ko.observableArray([]),
		addCourse: function(id, name, distance, climb, description) {
			this.courses.push(new Course(id, name, distance, climb, description));   
		},
		addNewCourse: function() {
			this.courses.push(new Course(null, null, null, null, null));
		}
	}

	var organizerJson = $("#EventOrganizers").val();
	var originalOrganizers = JSON.parse(organizerJson);
	for(var i = 0; i < originalOrganizers.length; i++) {
		var originalOrganizer = originalOrganizers[i];
		viewModel.addOrganizer(originalOrganizer["id"], originalOrganizer["name"], originalOrganizer["role"]["id"]);
	}
		
	var courseJson = $("#EventCourses").val();
	var originalCourses = JSON.parse(courseJson);
	for(var i = 0; i < originalCourses.length; i++) {
		var originalCourse = originalCourses[i];
		viewModel.addCourse(originalCourse["id"], originalCourse["name"], originalCourse["distance"], originalCourse["climb"], originalCourse["description"]);
	}
		
	ko.applyBindings(viewModel);

	$("#organizers").autocomplete({
		source: "/users/index.json",
		minLength: 2,
		select: function(event, ui) {
			viewModel.addOrganizer(ui.item.id, ui.item.value, 1);
		},
		close: function(event, ui) {
			$(this).val(null);
		}
	});
}
</script>
<div id='edit-organizers'>
<script type="text/html" id="organizerTemplate">
<tr>
<td data-bind="text: name || 'Anonymous'"></td>
<td>
<select data-bind="options: availableRoles, value: role, optionsText: 'name'">
</select>
</td>
<td><div class="unsubmit"><input type="submit" data-bind="click: remove" value="Remove" /></div></td>
</tr>
</script>
<form>
<?php echo $this->Form->input('organizers', array('label' => 'Event Organizers', 'placeholder' => 'Add an organizer')); ?>
<div class="input">
<table data-bind="visible: organizers().length > 0">
<thead><tr><th>Name</th><th>Role</th><th></th></tr></thead>
<tbody data-bind="template: {name:'organizerTemplate', foreach: organizers}"></tbody>
</table>
</div>
</form>
</div>

<div id='edit-courses'>
<script type="text/html" id="courseTemplate">
<tr>
<td><input type="text" size="15" data-bind="value: name, uniqueName: true" required /></td>
<td><input type="number" size="7" data-bind="value: distance" /></td>
<td><input type="number" size="5" data-bind="value: climb" /></td>
<td><input type="text" size="50" data-bind="value: description" /></td>
<td><div class="unsubmit"><input type="submit" data-bind="click: remove" value="Remove" /></div></td>
</tr>
</script>
<form>
<div class="input course-editing">
<label>Courses</label>
<table data-bind="visible: courses().length > 0">
<thead><tr><th>Name</th><th>Dist. (m)</th><th>Climb (m)</th><th>Description</th><th></th></tr></thead>
<tbody data-bind="template: {name:'courseTemplate', foreach: courses}"></tbody>
</table>
<div class="submit"><input type="submit" data-bind="click: addNewCourse" value="Add Course" /></div>
</div>
</form>
</div>