<?php
// Params: mode (either 'normal', or 'live')
?>
<div data-bind="if: resultList">
    <?php if ($mode == 'live') { ?>
    <p>Results produced on <span data-bind="text: resultList().creationDate"></span></p>
    <?php } ?>
    <div data-bind="foreach: resultList().event().courses">
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
                        <th data-bind="visible: hasWhyJustRunPoints">Points</th>
                        <th data-bind="visible: hasComments" style="width: 35px"></th>
                    </tr>
                </thead>
                <tbody data-bind="foreach: results">
                    <tr>
                        <td>
                            <span data-bind="text: position || friendlyStatus"></span>
                        </td>
                        <td>
                            <span data-bind="visible: person.profileUrl">
                            <a data-bind="attr: { href: person.profileUrl }"><span data-bind="text: person.givenName + ' ' + person.familyName"></span></a>
                            </span>

                            <span data-bind="visible: !person.profileUrl, text: person.givenName + ' ' + person.familyName"></span>
                        </td>
                        <td data-bind="visible: $parent.isScore, text: scores['Points']"></td>
                        <td>
                            <span data-bind="text: time != null ? hours + ':' + minutes + ':' + seconds + ($parent.millisecondTiming ? '.' + milliseconds : '' ) : ''"></span>
                        </td>
                        <td data-bind="visible: $parent.hasWhyJustRunPoints, text: scores['WhyJustRun']"></td>
                        <td data-bind="visible: $parent.hasComments">
                            <div class="btn-group" data-bind="visible: officialComment">
                                <button class="btn btn-xs btn-default" data-bind="tooltip: { title: officialComment, trigger: 'hover' }">
                                  <span class="glyphicon glyphicon-comment"></span>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
