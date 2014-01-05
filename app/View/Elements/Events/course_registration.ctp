<?php
// Pass in array of $courses with associated results (actually registrations in this case)
// count($courses) should be > 0
assert(count($courses) > 0);
?>
<div class="results col-sm-4">
    <header>
        <h2>Course Registration</h2>
    </header>
    <div class="courses">
        <?php 
        $userId = $this->Session->read('Auth.User.id');
        $userId = empty($userId) ? 0 : $userId;
        foreach($courses as $course) { ?>
        <div class="course">
            <div class="course-info">
                <div class="pull-right">
                    <?php if($course["registered"] === false) { ?>
                    <div class="btn-group">
                        <a class="btn btn-success" href="/courses/register/<?= $course['id'] ?>/<?= $userId ?>"><span class="glyphicon glyphicon-plus"></span> Register</a>
                        <a class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="/courses/register/<?= $course['id'] ?>/<?= $userId ?>/needsRide"><span class="glyphicon glyphicon-user"></span> Register (Need ride)</a></li>
                            <li><a href="/courses/register/<?= $course['id'] ?>/<?= $userId ?>/offeringRide"><span class="glyphicon glyphicon-road"></span> Register (Offer ride)</a></li>
                        </ul>
                    </div>
                    <?php } ?>
                </div>

                <h3><?= $course["name"] ?></h3>
                <span>
                    <?= $course["description"] ?>
                    <p>
                    <?php
                    if(!empty($course["distance"])) {
                    echo "<br/><strong>Distance</strong>: ${course['distance']}m";
                    }
                    if(!empty($course["climb"])) {
                    echo "<br/><strong>Climb</strong>: ${course['climb']}m";
                    }
                    ?>
                    </p>
                </span>
            </div>
            <div class="results-list">
                <?php echo $this->element('Results/list', array('course' => $course, 'results' => $course["Result"])); ?>
            </div>
        </div>
        <?php } ?>
    </div>
    
    <h3>Register Others</h3>
    <?php if(empty($userId)) { ?>
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
    <script type="text/javascript">
        $(function() {
                $('#RegisterOthersUserId').val(null);

                orienteerAppPersonPicker('#RegisterOthersUserName', { maintainInput: true, allowNew: true }, function(person) {
                    if(person != null) {
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
                    if(!userId) {
                    var userName = $('#RegisterOthersUserName').val();
                    if(userName) {
                    if(userName.indexOf(" ") != -1) {
                    if(confirm("This registration will create a new user in the system. Are you sure " + userName + " isn't already an OrienteerApp user?")) {
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
    <div class="form-group">
        <input type="hidden" id="RegisterOthersUserId" />
        <input class="form-control" placeholder="Participant Name" type="text" id="RegisterOthersUserName" /><br/>
    </div>
    <button id="RegisterOthersSubmit" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Register</button>
    <?php } ?>
</div>
