ALTER TABLE events ADD COLUMN `club_id` int(11) default NULL;
UPDATE events SET club_id = 1;

ALTER TABLE content_blocks ADD COLUMN `club_id` int(11) default NULL;
UPDATE content_blocks SET club_id = 1;

ALTER TABLE groups ADD COLUMN `club_id` int(11) default NULL;
UPDATE groups SET club_id = 1;

ALTER TABLE maps ADD COLUMN `club_id` int(11) default NULL;
UPDATE maps SET club_id = 1;

ALTER TABLE memberships ADD COLUMN `club_id` int(11) default NULL;
UPDATE memberships SET club_id = 1;

ALTER TABLE pages ADD COLUMN `club_id` int(11) default NULL;
UPDATE pages SET club_id = 1;

ALTER TABLE roles ADD COLUMN `club_id` int(11) default NULL;
UPDATE roles SET club_id = 1;

ALTER TABLE series ADD COLUMN `club_id` int(11) default NULL;
UPDATE series SET club_id = 1;
