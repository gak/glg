-- MySQL dump 9.11
--
-- Host: localhost    Database: ausource
-- ------------------------------------------------------
-- Server version	4.0.22-log

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS admin;
CREATE TABLE admin (
  id int(10) unsigned NOT NULL auto_increment,
  name varchar(50) NOT NULL default '',
  pass varchar(50) NOT NULL default '',
  access int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=InnoDB;

--
-- Table structure for table `clan`
--

DROP TABLE IF EXISTS clan;
CREATE TABLE clan (
  id int(10) unsigned NOT NULL auto_increment,
  name varchar(100) NOT NULL default '',
  email varchar(100) default NULL,
  pass varchar(50) NOT NULL default '',
  val tinyint(4) NOT NULL default '0',
  image blob,
  imagesmall blob,
  joined int(10) unsigned NOT NULL default '0',
  url varchar(255) NOT NULL default '',
  other varchar(255) NOT NULL default '',
  tag varchar(50) NOT NULL default '',
  tagalign tinyint(4) NOT NULL default '0',
  rank int(10) unsigned NOT NULL default '0',
  rating float NOT NULL default '1000',
  bestrank int(10) unsigned NOT NULL default '0',
  worstrank int(10) unsigned NOT NULL default '0',
  win int(10) unsigned NOT NULL default '0',
  loss int(10) unsigned NOT NULL default '0',
  draw int(10) unsigned NOT NULL default '0',
  games int(10) unsigned NOT NULL default '0',
  streak int(11) NOT NULL default '0',
  beststreak int(11) NOT NULL default '0',
  worststreak int(11) NOT NULL default '0',
  recruiting tinyint(4) unsigned NOT NULL default '0',
  available tinyint(4) unsigned NOT NULL default '1',
  lp int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (id),
  UNIQUE KEY name (email)
) TYPE=InnoDB;

--
-- Table structure for table `content`
--

DROP TABLE IF EXISTS content;
CREATE TABLE content (
  id int(10) unsigned NOT NULL auto_increment,
  name varchar(100) NOT NULL default '',
  content text NOT NULL,
  lastchanged int(10) unsigned NOT NULL default '0',
  whochanged int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (id),
  UNIQUE KEY name (name)
) TYPE=InnoDB;

--
-- Table structure for table `maps`
--

DROP TABLE IF EXISTS maps;
CREATE TABLE maps (
  id int(10) unsigned NOT NULL auto_increment,
  name varchar(100) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=InnoDB;

--
-- Table structure for table `member`
--

DROP TABLE IF EXISTS member;
CREATE TABLE member (
  id int(10) unsigned NOT NULL auto_increment,
  clanid int(10) unsigned NOT NULL default '0',
  acssrid int(10) unsigned default NULL,
  name varchar(255) NOT NULL default '',
  email varchar(255) NOT NULL default '',
  isrep tinyint(4) NOT NULL default '0',
  active tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY clanid (clanid)
) TYPE=InnoDB;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS news;
CREATE TABLE news (
  id int(10) unsigned NOT NULL auto_increment,
  subject varchar(255) NOT NULL default '',
  body text NOT NULL,
  when int(10) unsigned NOT NULL default '0',
  who int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=InnoDB;

--
-- Table structure for table `result`
--

DROP TABLE IF EXISTS result;
CREATE TABLE result (
  id int(10) unsigned NOT NULL auto_increment,
  result tinyint(3) unsigned NOT NULL default '0',
  clan1 int(10) unsigned NOT NULL default '0',
  clan2 int(10) unsigned NOT NULL default '0',
  clan1ratingbefore float NOT NULL default '0',
  clan1ratingafter float NOT NULL default '0',
  clan2ratingbefore float NOT NULL default '0',
  clan2ratingafter float NOT NULL default '0',
  ts int(10) unsigned NOT NULL default '0',
  comment text NOT NULL,
  PRIMARY KEY  (id)
) TYPE=InnoDB COMMENT='result ( 0=c1 lost, 1=draw )';

--
-- Table structure for table `resultmaps`
--

DROP TABLE IF EXISTS resultmaps;
CREATE TABLE resultmaps (
  id int(10) unsigned NOT NULL auto_increment,
  resultid int(10) unsigned NOT NULL default '0',
  mapid int(10) unsigned NOT NULL default '0',
  clan1score int(10) unsigned NOT NULL default '0',
  clan2score int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=InnoDB;

--
-- Table structure for table `session`
--

DROP TABLE IF EXISTS session;
CREATE TABLE session (
  id varchar(100) NOT NULL default '',
  time int(10) unsigned NOT NULL default '0',
  start int(10) unsigned NOT NULL default '0',
  value text NOT NULL,
  page varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=InnoDB;

--
-- Table structure for table `stats`
--

DROP TABLE IF EXISTS stats;
CREATE TABLE stats (
  hits int(10) unsigned NOT NULL default '0'
) TYPE=InnoDB;

