<?php
// Redirection for pages from the old GVOC website - this doesn't attempt to redirect every single page, but it will do most of them
$section = strtolower($_GET['lookup']);
$id = $_GET['item'];
$article = $_GET['articleID'];
$location = $_GET['location'];
$domain = 'http://gvoc.whyjustrun.ca/';

if($section == 'calendar') {
	if(!empty($id)) {
		$target = 'events/view/'.$id;
	} else {
		$target = 'events/index';
	}
} else if($section == 'information') {
	$target = 'pages/resources';
} else if($section == 'login') {
	if($location == 'register') {
		$target = 'users/register';
	} else if($location == 'userlogin') {
		$target = 'users/login';
	} else if($location == 'forgot') {
		$target = 'users/login';
	}
	
}

if($article == '15') {
	$target = 'pages/contact';
}

if($article='87') {
	$target = 'maps';
}

if(empty($target)) {
	$target = $domain;
} else {
	$target = $domain.$target;
}

header("Location: $target", true, 301);

