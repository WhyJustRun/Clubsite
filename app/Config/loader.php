<?php
// Responsible for loading the club config into CakePHP
App::uses('Sanitize', 'Utility');
function clubFromHost($host) {
    $escapedHost = Sanitize::escape($host, 'default');
    clubForQuery("SELECT * FROM clubs WHERE domain = '$escapedHost' OR domain = 'www.$escapedHost'", false);
}

function clubFromID($id) {
    $id = intval($id);
    clubForQuery("SELECT * FROM clubs WHERE id = $id", true);
}

function validClubResults($results) {
    return (count($results) === 1) && (!empty($results[0]["clubs"]));
}

function clubForQuery($query, $fromID) {
    $db = ConnectionManager::getDataSource('default');
    $results = $db->fetchAll($query);
    if (validClubResults($results)) {
        foreach($results[0]['clubs'] as $key => $value) {
            if ($key === 'timezone') {
                $value = timezone_open($value);
            }
            Configure::write('Club.'.$key, $value);
        }
    } else if ($fromID) {
        die("ERROR: No valid club results.");
    } else {
        clubFromID(1);
    }
}

// Load in club configuration based on the request domain name
if (!empty($_SERVER['HTTP_HOST'])) {
    clubFromHost($_SERVER['HTTP_HOST']);
} else {
    clubFromID(1);
}
?>