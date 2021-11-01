<?php
// Responsible for loading the club config into CakePHP
App::import('Model', 'ConnectionManager');
App::uses('Sanitize', 'Utility');

function validClubResults($results) {
    return (count($results) === 1) && (!empty($results[0]["clubs"]));
}

function useClubWithId($id) {
    $query = "SELECT * FROM clubs WHERE id = ?";
    $db = ConnectionManager::getDataSource('default');
    $results = $db->fetchAll($query, array($id));
    if (validClubResults($results)) {
        writeClubConfig($results[0]);
    } else {
       die("ERROR: Loading club for $id failed");
    } 
}

function useClubFromHost($host) {
    $query = "SELECT * FROM clubs WHERE domain = ?";
    $db = ConnectionManager::getDataSource('default');
    $results = $db->fetchAll($query, array($host));
    if (validClubResults($results)) {
        writeClubConfig($results[0]);
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

function useAnyClub() {
    $query = "SELECT * FROM clubs LIMIT 1";
    $db = ConnectionManager::getDataSource('default');
    $results = $db->fetchAll($query);
    if (validClubResults($results)) {
        writeClubConfig($results[0]);
    } else {
        die("ERROR: Loading club failed. Please contact support@whyjustrun.ca and include the URL you were requesting.");
    }
}

function writeClubConfig($result) {
    foreach ($result['clubs'] as $key => $value) {
        if ($key === 'timezone') {
            $value = timezone_open($value);
        }
        Configure::write('Club.'.$key, $value);
    }
}

function redirectHostPermanentlyTo($targetHost) {
    header("HTTP/1.1 301 Moved Permanently");
    $requestURL = $_SERVER["REQUEST_URI"];
    header("Location: http://" . $targetHost . $requestURL);
    exit();
}
