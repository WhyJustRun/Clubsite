# Create club tables. WILL DELETE ANY EXISTING CLUB TABLES

# Creating/selecting database should be handled by the script running this sql
# CREATE DATABASE IF NOT EXISTS wjr_gvoc;
# USE wjr_gvoc;

# Create users and clubs tables

DROP TABLE IF EXISTS courses;
DROP TABLE IF EXISTS `events`;
DROP TABLE IF EXISTS forum_messages;
DROP TABLE IF EXISTS forum_topics;
DROP TABLE IF EXISTS series;
DROP TABLE IF EXISTS `roles`;
DROP TABLE IF EXISTS results;
DROP TABLE IF EXISTS pages;
DROP TABLE IF EXISTS organizers;
DROP TABLE IF EXISTS news;
DROP TABLE IF EXISTS memberships;
DROP TABLE IF EXISTS maps;
DROP TABLE IF EXISTS map_standards;
DROP TABLE IF EXISTS `groups`;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS clubs;
DROP TABLE IF EXISTS tokens;
