<script type="text/html" id="resultTemplate">
    <tr>
        <td data-bind="text: user.name" style="vertical-align: middle"><p></p></td>
        <td data-bind="visible: $parent.is_score_o"><input type="number" class="form-control" data-bind="value: score_points" /></td>
        <td style="width: 160px">
            <div class="input-group">
                <input type="text" class="form-control time-segment" maxlength="2" size="2" data-bind="value: hours" pattern="[0-9]{0,2}" />
                <span class="input-group-addon time-spacer">:</span>
                <input type="text" class="form-control time-segment" maxlength="2" size="2" data-bind="value: minutes" pattern="[0-9]{0,2}" />
                <span class="input-group-addon time-spacer">:</span>
                <input type="text" class="form-control time-segment" maxlength="2" size="2" data-bind="value: seconds" pattern="[0-9]{0,2}" />
                <span class="input-group-addon time-spacer">.</span>
                <input type="text" class="form-control millisecond-time-segment" maxlength="3" size="3" data-bind="value: milliseconds" pattern="[0-9]{0,3}"/>
            </div>
        </td>
        <td style="min-width: 130px" class="results-editing">
            <select class="form-control" data-bind="options: statuses(), optionsText: 'name', optionsValue: 'id', value: status, optionsCaption: 'Choose...'"></select>
        </td>
        <td>
            <input type="text" class="form-control" maxlength="255" data-bind="value: official_comment" />
        </td>
        <td style="width: 45px"><button type="submit" class="btn btn-danger pull-right" data-bind="click: remove"><span class="glyphicon glyphicon-trash"></span></button></td>
    </tr>
</script>

<script type="text/html" id="courseTemplate">
    <div>
        <div class="pull-right">
            <div class="form-inline form-group">
            <label>
                Rank by:
                <select style="max-width: 200px" class="form-control" data-bind="value: rankBy">
                    <option value="time">Time</option>
                    <option value="points">Score-O Points</option>
                </select>
            </label>
            </div>
        </div>
        <h2 data-bind="text: name"></h2>
    </div>
    <div class="table-responsive">
        <table style="min-width: 470px" class="table table-striped table-condensed table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th data-bind="visible: is_score_o">Score Points</th>
                    <th>Time (hh:mm:ss)</th>
                    <th>Status</th>
                    <th>Comment</th>
                    <th></th>
                </tr>
            </thead>
            <tbody data-bind="template: {name:'resultTemplate', foreach: results}">
            </tbody>
        </table>
    </div>
</script>

<div class="result-editor" data-event-id="<?php echo $eventId ?>">
    <header class="page-header">
        <h1>Edit Results <small data-bind="text: event() ? event().name : ''"></small></h1>
    </header>
    <div class="row">
        <div class="col-sm-8">
            <div data-bind="template: {name:'courseTemplate', foreach: courses}">
            </div>
            <?php
            echo $this->Form->create('Event', array('url' => "/events/editResults/${eventId}"));
            echo $this->Form->hidden('courses', array('data-bind' => 'value: ko.toJSON(courses)'));
            $this->Form->unlockField('Event.courses');
            ?>
            <fieldset class="form-group">
               <div class="checkbox">
                   <label for="EventResultsPosted">
                       <?php echo $this->Form->checkbox('results_posted', array('checked' => 'checked')) ?>
                       Show Results?
                   </label>
               </div>
            </fieldset>
            <?php
            echo $this->Form->end(array('label' => 'Save', 'class' => 'btn btn-primary'));
            ?>
        </div>

        <div class="col-sm-4">
            <h2>Add Competitor</h2>
            <p>Choose the course you would like to add a competitor. <br/>Type in the participant's name and choose the matching person. If they are not already in the system, choose the "Create New User" option.</p>
            <div class="form-group">
                <select class="form-control" data-bind="options: courses, optionsText: 'name', value: selectedCourse, optionsCaption: 'Choose Course..'"></select>
            </div>
            <?php echo $this->Form->input('competitorResults', array('label' => false, 'placeholder' => 'Competitor Name')); ?>
        </div>
    </div>
</div>
