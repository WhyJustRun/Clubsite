<?php
// Params: $userId, $courses
?>
<div class="register-others">
<h3>Register Others</h3>
<?php if (empty($userId)) { ?>
<a href="/users/login" class="btn btn-primary">Sign in / Sign Up</a>
<?php } else { ?>
<p>To register someone else, choose the course to register them on, then type their name, and pick the person from the drop down list.</p>
<p><strong>Families</strong>: you don't need to register an account for every family member, just type the participating people's names below and an account will automatically be created for each person.</p>
<div class="form-group">
    <select class="form-control" id="RegisterOthersCourse">
        <?php foreach($courses as $course) { ?>
        <option value="<?= $course['id'] ?>"><?= $course['name'] ?></option>
        <?php } ?>
    </select>
</div>

<div class="form-group">
    <input type="hidden" id="RegisterOthersUserId" />
    <input class="form-control" placeholder="Participant Name" type="text" id="RegisterOthersUserName" />
</div>
<button id="RegisterOthersSubmit" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Register</button>
<?php } ?>
</div>
