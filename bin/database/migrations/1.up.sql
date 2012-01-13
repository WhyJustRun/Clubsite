# Create club tables. WILL DELETE ANY EXISTING CLUB TABLES

# Creating/selecting database should be handled by the script running this sql
# CREATE DATABASE IF NOT EXISTS wjr_gvoc;
# USE wjr_gvoc;

# Create users and clubs tables

CREATE TABLE `courses` (
  `id` int(11) NOT NULL auto_increment,
  `event_id` int(11) default NULL,
  `name` tinytext,
  `distance` int(11) default NULL,
  `climb` int(11) default NULL,
  `description` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `events` (
  `id` int(11) NOT NULL auto_increment,
  `name` tinytext,
  `group_id` int(11) default NULL,
  #`club_id` int(11) default NULL,
  `map_id` int(11) default NULL,
  `series_id` int(11) default NULL,
  `date` datetime default NULL,
  `lat` double default NULL,
  `lng` double default NULL,
  `is_ranked` tinyint(4) default NULL,
  `description` text,
  `results_posted` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `forum_messages` (
  `id` int(11) NOT NULL auto_increment,
  `forum_topic_id` int(11) default NULL,
  `user_id` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `forum_topics` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) default NULL,
  `group_id` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `series` (
  `id` int(11) NOT NULL auto_increment,
  #`club_id` int(11) default NULL,
  `acronym` tinytext,
  `name` tinytext,
  `description` text,
  `color` tinytext,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `roles` (
  `id` int(11) NOT NULL auto_increment,
  `name` tinytext,
  `description` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `results` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) default NULL,
  `course_id` int(11) default NULL,
  `time` time default NULL,
  `non_competitive` tinyint(4) NOT NULL default '0',
  `points` int(11) default NULL,
  `needs_ride` tinyint(4) NOT NULL default '0',
  `offering_ride` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `organizers` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) default NULL,
  `event_id` int(11) default NULL,
  `role_id` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `news` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) default NULL,
  #`club_id` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `memberships` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) default NULL,
  #`club_id` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `maps` (
  `id` int(11) NOT NULL auto_increment,
  `name` tinytext,
  #`club_id` int(11) default NULL,
  `map_standard_id` int(11) default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  `scale` tinytext,
  `lat` double default NULL,
  `lng` double default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `map_standards` (
  `id` int(11) NOT NULL auto_increment,
  `name` tinytext,
  `description` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `groups` (
  `id` int(11) NOT NULL auto_increment,
  `name` tinytext,
  `access_level` int(11) default NULL,
  `description` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `clubs` (
  `id` int(11) NOT NULL auto_increment,
  `name` tinytext,
  `acronym` varchar(8) default NULL,
  `location` tinytext,
  `description` text,
  `url` tinytext,
  `lat` double default NULL,
  `lng` double default NULL,
  `timezone` tinytext,
  `visible` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

CREATE TABLE `tokens` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  `token` varchar(32) default NULL,
  `data` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
  `club_id` int(11) default NULL,
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
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1768 DEFAULT CHARSET=utf8;
