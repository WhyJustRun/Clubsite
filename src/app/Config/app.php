<?php
$clubDir = Configure::read('Club.dir');
if (!is_dir($clubDir)) {
    mkdir($clubDir, 0777, true);
}

// Application wide constants and configuration (Club.dir will be defined before this is included)

// Append user id to get the actual URL
Configure::write("Rails.profileURL", Configure::read('Rails.domain').'/users/');

Configure::write("Rails.loginURL", Configure::read('Rails.domain').'/users/sign_in_clubsite');
Configure::write("Rails.logoutURL", Configure::read('Rails.domain').'/users/sign_out_clubsite');

Configure::write("Event.numBlankEntries", 5);

Configure::write("Result.statuses", array(
            'ok' => 'Finished',
            'inactive' => 'Inactive',
            'did_not_start' => 'DNS',
            'active' => 'In Progress',
            'finished' => 'Unofficial',
            'mis_punch' => 'MP',
            'did_not_enter' => 'DNE',
            'did_not_finish' => 'DNF',
            'disqualified' => 'DSQ',
            'not_competing' => 'NC',
            'sport_withdrawal' => 'Sport Withdrawal',
            'over_time' => 'Over Time',
            'moved' => 'Moved',
            'moved_up' => 'Moved Up',
            'cancelled' => 'Cancelled'
            ));

Configure::write('Club.nearby.max', 3);

// Minimum access level for adding/editing an event without being an organizer
// IMPORTANT: If you add/change any privilege, make sure you update the corresponding privilege in rails (config/settings.yml)
Configure::write('Privilege.Admin.page', 90);
Configure::write('Privilege.Club.edit', 90);
Configure::write('Privilege.ContentBlock.edit', 80);
Configure::write('Privilege.Event.delete', 90);
Configure::write('Privilege.Event.edit', 80);
Configure::write('Privilege.Event.planning', 80);
Configure::write('Privilege.Map.delete', 90);
Configure::write('Privilege.Map.edit', 80);
Configure::write('Privilege.Map.view_ocad', 0);
Configure::write('Privilege.MapStandard.delete', 100);
Configure::write('Privilege.MapStandard.edit', 100);
Configure::write('Privilege.Membership.delete', 80);
Configure::write('Privilege.Membership.edit', 80);
Configure::write('Privilege.Official.delete', 90);
Configure::write('Privilege.Official.edit', 90);
Configure::write('Privilege.Page.edit', 80);
Configure::write('Privilege.Page.delete', 80);
Configure::write('Privilege.Privilege.delete', 90);
Configure::write('Privilege.Privilege.edit', 90);
Configure::write('Privilege.Resource.delete', 90);
Configure::write('Privilege.Resource.edit', 80);
Configure::write('Privilege.Resource.index', 80);
Configure::write('Privilege.Role.delete', 100);
Configure::write('Privilege.Role.edit', 100);
Configure::write('Privilege.Series.edit', 90);
Configure::write('Privilege.User.edit', 100);
Configure::write('Privilege.User.merge', 90);

// File Resource configuration
Configure::write('Resource.Club.headerImage.allowedExtensions', array('jpg', 'jpeg', 'gif', 'png'));
Configure::write('Resource.Club.headerImage.name', 'Header Image');
Configure::write('Resource.Club.headerImage.description', 'Image that will show at the top of every page. Should be at least 2600px wide.');

Configure::write('Resource.Club.logo.allowedExtensions', array('jpg', 'jpeg', 'gif', 'png'));
Configure::write('Resource.Club.logo.name', 'Logo');
Configure::write('Resource.Club.logo.description', 'Logo graphic');

Configure::write('Resource.Club.style.allowedExtensions', array('css'));
Configure::write('Resource.Club.style.name', 'CSS Style');
Configure::write('Resource.Club.style.description', 'You can override the default WhyJustRun CSS with your own style. Warning: this may break with updates to WhyJustRun.');

// Specify content block defaults (so clubs upgrade cleanly)
Configure::write('ContentBlock.default.general_information.1', '<h2>Welcome!</h2>');
Configure::write('ContentBlock.default.general_maps_information.1', 'No map information has been entered yet.');
Configure::write('ContentBlock.default.contact.1', 'No contact information has been entered yet.');

Configure::write('Event.planner.dateThreshold', new DateTime('-5 months'));
Configure::write('Event.planner.attendanceThreshold', 5);

Configure::write("Map.dir", Configure::read('Club.dir') . "maps/");
Configure::write("Course.dir", Configure::read('Club.dir') . "courses/");
Configure::write("Event.dir", Configure::read('Club.dir') . "events/");

Configure::write('Facebook.appPageId', 'WhyJustRunApp');
