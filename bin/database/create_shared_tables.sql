# Create shared tables. WILL DELETE ANY EXISTING SHARED TABLES

CREATE DATABASE IF NOT EXISTS wjr_shared;
USE wjr_shared;

# Create users and clubs tables

DROP TABLE IF EXISTS users;

CREATE TABLE `wjr_users` (
  `id` int(11) NOT NULL auto_increment,
  `name` tinytext,
  `username` tinytext NOT NULL,
  `email` tinytext,
  `password` tinytext,
  `year_of_birth` int(11) default NULL,
  `si_number` int(11) default NULL,
  `last_login` datetime default NULL,
  `last_news` datetime default NULL,
  `time_created` datetime default NULL,
  `referred_from` tinytext,
  `old_id` int(11) default NULL,
  `club_id` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS clubs;

CREATE TABLE `clubs` (
  `id` int(11) NOT NULL auto_increment,
  `name` tinytext,
  `acronym` varchar(8) default NULL,
  `location` tinytext,
  `description` text,
  `lat` double default NULL,
  `lng` double default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

# Table to deal with forgot password reset request tokens
DROP TABLE IF EXISTS tokens;

CREATE TABLE `tokens` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  `token` varchar(32) default NULL,
  `data` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
