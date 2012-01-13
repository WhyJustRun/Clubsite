# Create club tables. WILL DELETE ANY EXISTING CLUB TABLES

# Creating/selecting database should be handled by the script running this sql
# CREATE DATABASE IF NOT EXISTS wjr_gvoc;
# USE wjr_gvoc;

# Create users and clubs tables

DROP TABLE IF EXISTS courses;

CREATE TABLE `courses` (
  `id` int(11) NOT NULL auto_increment,
  `event_id` int(11) default NULL,
  `name` tinytext,
  `distance` int(11) default NULL,
  `climb` int(11) default NULL,
  `description` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS events;

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


DROP TABLE IF EXISTS forum_messages;

CREATE TABLE `forum_messages` (
  `id` int(11) NOT NULL auto_increment,
  `forum_topic_id` int(11) default NULL,
  `user_id` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS forum_topics;

CREATE TABLE `forum_topics` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) default NULL,
  `group_id` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS series;

CREATE TABLE `series` (
  `id` int(11) NOT NULL auto_increment,
  #`club_id` int(11) default NULL,
  `acronym` tinytext,
  `name` tinytext,
  `description` text,
  `color` tinytext,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS roles;

CREATE TABLE `roles` (
  `id` int(11) NOT NULL auto_increment,
  `name` tinytext,
  `description` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS results;

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


DROP TABLE IF EXISTS pages;

CREATE TABLE `pages` (
  `id` int(11) NOT NULL auto_increment,
  #`club_id` int(11) default NULL,
  `user_id` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS organizers;

CREATE TABLE `organizers` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) default NULL,
  `event_id` int(11) default NULL,
  `role_id` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS news;

CREATE TABLE `news` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) default NULL,
  #`club_id` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS memberships;

CREATE TABLE `memberships` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) default NULL,
  #`club_id` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS maps;

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


DROP TABLE IF EXISTS map_standards;

CREATE TABLE `map_standards` (
  `id` int(11) NOT NULL auto_increment,
  `name` tinytext,
  `description` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS groups;

CREATE TABLE `groups` (
  `id` int(11) NOT NULL auto_increment,
  `name` tinytext,
  `access_level` int(11) default NULL,
  `description` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;