<?php
// Params: $userId, $courses
?>
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
<?php $this->append('secondaryScripts'); ?>
<script type="text/javascript">
    $(function() {
            $('#RegisterOthersUserId').val(null);

            orienteerAppPersonPicker('#RegisterOthersUserName', { maintainInput: true, allowNew: true }, function(person) {
                if (person != null) {
                $('#RegisterOthersUserId').val(person.id);
                } else {
                $('#RegisterOthersUserId').val(null);
                }
                });

            function completeSubmit(courseId, userId) {
            location.href = "/courses/register/" + courseId + "/" + userId;
            }

            $('#RegisterOthersSubmit').click(function() {
                var userId = $('#RegisterOthersUserId').val();
                var courseId = $('#RegisterOthersCourse').val();
                if (!userId) {
                var userName = $('#RegisterOthersUserName').val();
                if (userName) {
                if (userName.indexOf(" ") != -1) {
                if (confirm("This registration will create a new user in the system. Are you sure " + userName + " isn't already an OrienteerApp user?")) {
                $.post('/users/add', { userName: userName }, function(data) {
                    completeSubmit(courseId, $.parseJSON(data))
                    });
                } else {
                alert("Thanks! Please re-enter the participant's name and choose the matching person from the dropdown.");
                }
                } else {
                alert("Please enter the participant's full name.");
                }
                } else {
                alert("Please enter the participant name");
                }
                } else {
                    completeSubmit(courseId, userId);
                }
            });
    });
</script>
<?php $this->end(); ?>
<div class="form-group">
    <input type="hidden" id="RegisterOthersUserId" />
    <input class="form-control" placeholder="Participant Name" type="text" id="RegisterOthersUserName" /><br/>
</div>
<button id="RegisterOthersSubmit" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Register</button>
<?php } ?>
