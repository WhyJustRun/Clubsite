<header class="page-header">
    <h1>Upload Course Maps</h1>
</header>

<p>Upload one map for each course. The upload process may take some time,
as thumbnails also have to be generated. Uploading will overwrite any existing maps for the particular course. Allowed map formats: jpg/jpeg, gif, png, pdf.</p>
<?php if (count($courses) > 0) {?>
<table class="table table-striped table-condensed table-bordered">
    <thead>
        <tr><th>Course</th><th>Add map</th><th width="50%">Current Map</th></tr>
    </thead>
<?php foreach ($courses as $course) { ?>
    <tr>
        <td><?php echo $course["Course"]["name"]?></td>
        <td>
            <?php echo $this->Form->create('Course', array('url' => array('action' => 'uploadMap/'.$course["Course"]["id"]), 'enctype' => 'multipart/form-data'))?>
            <?php echo $this->Form->file('image', array('style' => 'width: 240px'))?>
            <?php echo $this->Form->end(array('label' => 'Upload'))?> </td>
        <td>
            <?php echo $this->Media->image('Course', $course['Course']['id'], '100x150'); ?>
        </td>
    </tr>
<?php } ?>
</table>
<?php } else { ?>
<p>No courses defined.</p>
<?php } ?>
