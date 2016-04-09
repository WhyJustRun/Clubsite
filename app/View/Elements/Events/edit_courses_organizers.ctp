<div class='edit-event-organizers' data-organizer-json-element="#EventOrganizers" data-organizer-input="#organizers">
    <?php echo $organizersInput ?>
    <script type="text/html" id="organizerTemplate">
        <tr>
            <td data-bind="text: name || 'Anonymous'"></td>
            <td>
                <select class="thin-control" data-bind="options: availableRoles, value: role, optionsText: 'name'">
                </select>
            </td>
            <td>
                <button type="submit" class="btn btn-xs btn-danger pull-right" data-bind="click: remove"><span class="glyphicon glyphicon-trash"></span></button>
            </td>
        </tr>
    </script>
    <fieldset class="form-group">
        <label for="organizers" class="col-sm-2 control-label">Event Organizers</label>
        <div class="col-sm-10">
            <input class="form-control" placeholder="Add an organizer" type="text" id="organizers" />
            <br/><br/>

            <table class="table table-striped table-condensed" data-bind="visible: organizers().length > 0">
                <thead><tr><th>Name</th><th>Role</th><th></th></tr></thead>
                <tbody data-bind="template: {name:'organizerTemplate', foreach: organizers}"></tbody>
            </table>
        </div>
    </fieldset>
</div>

<div class='edit-event-courses' data-course-json-element="#EventCourses">
    <?php echo $coursesInput ?>
    <script type="text/html" id="courseTemplate">
        <tr>
            <td class="col-sm-2"><input type="text" class="form-control input-sm" data-validate = "validate(required)" data-bind="value: name, uniqueName: true" required /></td>
            <td class="col-sm-1"><input class="form-control input-sm" type="number" data-bind="value: distance" /></td>
            <td class="col-sm-1"><input class="form-control input-sm" type="number" data-bind="value: climb" /></td>
            <td class="col-sm-1">
                <select class="form-control input-sm" data-bind="value: rankBy">
                    <option value="time">Time</option>
                    <option value="points">Score-O Points</option>
                </select>
            </td>
            <td><input type="text" class="form-control input-sm" data-bind="value: description" /></td>
            <td>
                <button type="submit" class="btn btn-sm btn-danger pull-right" data-bind="click: remove"><span class="glyphicon glyphicon-trash"></span></button>
            </td>
        </tr>
    </script>
    <fieldset class="form-group">
        <label class="col-sm-2 control-label">Courses</label>
        <div class="col-sm-10">
            <button type="submit" class="btn btn-success" data-bind="click: addNewCourse">
                <span class="glyphicon glyphicon-plus"></span> Course
            </button>
            <br/><br/>
            <table class="table table-striped table-condensed" data-bind="visible: courses().length > 0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Dist. (m)</th>
                        <th>Climb (m)</th>
                        <th>Rank by</th>
                        <th>Description</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody data-bind="template: {name:'courseTemplate', foreach: courses}"></tbody>
            </table>
        </div>
    </fieldset>
</div>
