<div class="padded">
    <h1>Upload Course Maps</h1>
<p>Upload one map for each course. The upload process may take some time,
as thumbnails also have to be generated. Uploading will overwrite any existing maps for the particular course.</p>
    <? if (count($courses) > 0) {?>
    <table>
        <thead>
            <tr><th>Course</th><th>Add map</th><th width="50%">Current Map</th></tr>
        </thead>
    <? foreach ($courses as $course) { ?>
        <tr>
            <td><?=$course["Course"]["name"]?></td>
            <td>
                <?=$this->Form->create('Course', array('action' => 'uploadMap/'.$course["Course"]["id"], 'enctype' => 'multipart/form-data'))?>
                <?= $this->Form->file('image', array('style' => 'width: 240px'))?>
                <?= $this->Form->end(array('label' => 'Upload', 'div' => 'right submit padded'))?> </td>
            <td> 
                <?= $this->Media->image('Course', $course['Course']['id'], '100x150'); ?>
            </td>
        </tr>
    <? } ?>
    </table>
    <? } else { ?>
    <p>No courses defined.</p>
    <? } ?>
    <?=$this->Html->link('Back','/events/view/'.$id, array('class' => 'button'));?>
</div>
