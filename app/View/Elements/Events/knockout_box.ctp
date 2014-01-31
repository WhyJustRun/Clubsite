<?php
// Params: $templateName
?>
<script type="text/html" id="<?= $templateName ?>">
    <div class="event-box">
        <a data-bind="attr: { href: url }">
            <div class="event-box-inner">
                <div class="event-box-left">
                    <div class="location" data-bind="style: { color: series.color }, text: name">
                    </div>
                    <span class="date">
                        <span class="date-text" data-bind="text: date"></span>
                        <span class="hidden-sm pull-right">
                            <span class="label label-info event-box-classification" data-bind="text: classification"></span>
                            <span class="label label-success event-box-club-acronym" data-bind="text: clubAcronym"></span>
                        </span>
                    </span>
                </div>
            </div>
        </a>
    </div>
</script>
