-- --------------------------------------------------------
--
-- Tabellenstruktur für Tabelle `#__phocaguestbook_items` (items)
--

CREATE TABLE IF NOT EXISTS `#__phocaguestbook_items` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `catid` int(11) NOT NULL default '0',
  `parent_id` int(11) NOT NULL default '0',
  `lft` int(11) NOT NULL default '0',
  `rgt` int(11) NOT NULL default '0',
  `level` int(11) NOT NULL default '1',
  `path` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL default '',
  `userid` int(11) NOT NULL default '0',
  `email` varchar(50) NOT NULL default '',
  `homesite` varchar(50) NOT NULL default '',
  `ip` varchar(20) NOT NULL default '',
  `title` varchar(200) NOT NULL default '',
  `alias` varchar(255) NOT NULL default '',
  `content` text NOT NULL,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `published` tinyint(1) NOT NULL default '0',
  `checked_out` int(11) NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `params` text NOT NULL,
  `language` char(7) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `published` (`published`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;


-- --------------------------------------------------------
--
-- Insert Root for Table `#__phocaguestbook_items`
--
INSERT INTO `#__phocaguestbook_items` (`id`, `catid`, `parent_id`, `lft`, `rgt`, `level`, `path`, `username`, `userid`, `email`, `homesite`, `ip`, `title`, `alias`, `content`, `date`, `published`, `checked_out`, `checked_out_time`, `params`, `language`) VALUES
(1, 0, 0, 0, 1, 0, '', 'ROOT', 0, '', '', '', 'root', 'root', '', '0000-00-00 00:00:00', 0, 0, '0000-00-00 00:00:00', '', '*');



-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `#__phocaguestbook_logging`
--

CREATE TABLE IF NOT EXISTS `#__phocaguestbook_logging` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `catid` int(11) NOT NULL default '0',
  `postid` int(11) NOT NULL default '0',
  `captchaid` smallint(6) NOT NULL,
  `fields` tinyint(4) NOT NULL,
  `hidden_field` tinyint(4) NOT NULL,
  `forbidden_word` tinyint(4) NOT NULL,
  `used_time` int(11) NOT NULL,
  `session` tinyint(4) NOT NULL,
  `incoming_page` varchar(255) NOT NULL,
  `ip` varchar(20) NOT NULL default '',
  `ip_list` tinyint(4) NOT NULL,
  `ip_stopforum` tinyint(4) NOT NULL,
  `ip_stopforum_txt` varchar(128) NOT NULL,
  `ip_honeypot` tinyint(4) NOT NULL,
  `ip_botscout` tinyint(4) NOT NULL,
  `ip_botscout_txt` varchar(128) NOT NULL,
  `content_akismet` tinyint(4) NOT NULL,
  `content_akismet_txt` varchar(128) NOT NULL,
  `content_mollom` tinyint(4) NOT NULL,
  `content_mollom_txt` varchar(128) NOT NULL,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `state` tinyint(3) NOT NULL default '0',
  `params` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `state` (`state`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
