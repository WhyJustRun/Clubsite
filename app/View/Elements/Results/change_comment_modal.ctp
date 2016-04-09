<?php
// Change comment modal
// Params: $modalId, $result
?>
<div class="modal fade" id="<?php echo $modalId ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $modalId ?>-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="<?php echo $modalId ?>-label"><?php echo (empty($result['registrant_comment']) ? 'Add' : 'Change'); ?> Comment</h4>
            </div>
            <form method="post" action="/results/editRegistrantComment">
                <div class="modal-body">
                    <input type="hidden" name="data[Result][id]" value="<?php echo $result['id'] ?>" />
                    <textarea class="form-control" name="data[Result][registrant_comment]" rows="3" maxlength="255"><?php echo $result['registrant_comment'] ?></textarea>
                    <p class="help-block" style="margin-bottom: 0px">Use comments to share information with other participants and the event organizers. For example, you can use this to find/offer a ride to the event. All information shared will be public.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
