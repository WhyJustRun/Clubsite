<?php
// Responsible for loading the club config into CakePHP
App::import('Model', 'ConnectionManager');
App::uses('Sanitize', 'Utility');

function validClubResults($results) {
    return (count($results) === 1) && (!empty($results[0]["clubs"]));
}

function clubFromHost($host) {
    $query = "SELECT * FROM clubs WHERE domain = ?";
    $db = ConnectionManager::getDataSource('default');
    $results = $db->fetchAll($query, array($host));
    if (validClubResults($results)) {
        foreach ($results[0]['clubs'] as $key => $value) {
            if ($key === 'timezone') {
                $value = timezone_open($value);
            }
            Configure::write('Club.'.$key, $value);
        }
    } else {
        // See if it is a redirect domain
        $redirectQuery = "SELECT * FROM clubs WHERE redirect_domain = ?";
        $results = $db->fetchAll($redirectQuery, array($host));
        if (validClubResults($results)) {
            $targetHost = $results[0]['clubs']['domain'];
            redirectHostPermanentlyTo($targetHost);
        } else {
            die("ERROR: Loading club failed. Please contact support@whyjustrun.ca and include the URL you were requesting.");
        }
    } 
}

function redirectHostPermanentlyTo($targetHost) {
    header("HTTP/1.1 301 Moved Permanently");
    $requestURL = $_SERVER["REQUEST_URI"];
    header("Location: http://" . $targetHost . $requestURL);
    exit();
}

// Load in club configuration based on the request domain name
if (!empty($_SERVER['HTTP_HOST'])) {
    clubFromHost($_SERVER['HTTP_HOST']);
} else {
    // Allow command line scripts to still work
    clubFromHost("iof.whyjustrun.ca");
}
