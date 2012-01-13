CREATE TABLE `content_blocks` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `key` varchar(255) default NULL,
  `content` text default NULL,
  `order` int(11) default '1',
  PRIMARY KEY  (`id`),
  KEY `key` (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `content_blocks` (`id`, `key`, `content`, `order`)
VALUES
	(2, 'general_maps_information', '## Maps\n\nPut general information about maps here.', 1),
	(1, 'general_information', '## Welcome\n\nPut general information about your club here.\n\n', 1),
	(4, 'general_maps_information', '### Conditions of Use\n\nPut information about your maps terms of use here.', 3),
	(3, 'general_maps_information', '### Printing Instructions\n\nPut information about how to print your maps here.', 2);
