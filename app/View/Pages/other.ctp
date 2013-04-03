<?php $this->set('title_for_layout', Configure::read("Club.name")); ?>
<div class="page-header">
    <header>
    <h1>Orienteering Canada Database</h1>
        Thank you for using the Orienteering Canada national database (OCNDB). This page allows your club to enter and edit information about your club's events, maps, and officials.
    </header>
</div>
<div class="three-column">
    <div class="span4">
        <h4>Events</h4>
            You can enter your club's events in the OCNDB quickly and easily. This allows you to:
            <ul>
                <li>do event registration</li>
                <li>post results</li>
                <li>post course maps</li>
                <li>expose your event to other clubs</li>
            </ul>

        <h4>Maps</h4>
        You can keep track of your club's maps in the OCNDB. You cann define each map, including scale, mapping standard, and attributes such as date created, last updated, etc . You can also 
        upload images of the map and specify the map location. The map can be linked to events so that competitors can see the image and see where the map is located. There is also an 
        overview that shows the location of all the club's maps.

        <h4>Officials</h4>
        Clubs are strongly encouraged to track Officials' certification levels in the OCNDB. This information is useful in many ways, including tracking numbers of qualified officials, recording 
        pre-requisites for official certification, and identifying qualified officials for major events.

    </div>
    <div class="span4">
        <h4>Export</h4>
        This page show how you can incorporate the information from the database into your club's website.

        <h4>Reports</h4>
        Here you can see basic reporting on club information such as participation counts and officials lists, along with examples of "widgets" you can include in you club's website (see below 
        for more info).

        <h4>Admin</h4>
        Use this page to seet up other users who will be allowed to enter and make changes to your club's information.
        </ul>
    </div>
    <div class="span4">
        <?php
            echo "<h3>Upcoming events</h3>";
            echo $this->element('Events/box-list', array('filter' => 'upcoming', 'limit' => '7'));
        ?>
   </div>
</div>

