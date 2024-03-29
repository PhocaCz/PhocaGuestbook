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
  `ip` varchar(50) NOT NULL default '',
  `title` varchar(200) NOT NULL default '',
  `alias` varchar(255) NOT NULL default '',
  `content` text,
  `date` datetime NOT NULL,
  `published` tinyint(1) NOT NULL default '0',
  `checked_out` int(11) NOT NULL default '0',
  `checked_out_time` datetime,
  `params` text,
  `language` char(7) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `published` (`published`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Insert Root for Table `#__phocaguestbook_items`
--
INSERT INTO `#__phocaguestbook_items` (`id`, `catid`, `parent_id`, `lft`, `rgt`, `level`, `path`, `username`, `userid`, `email`, `homesite`, `ip`, `title`, `alias`, `content`, `date`, `published`, `checked_out`, `checked_out_time`, `params`, `language`) VALUES
(1, 0, 0, 0, 1, 0, '', 'ROOT', 0, '', '', '', 'root', 'root', '', '0000-00-00 00:00:00', 1, 0, '0000-00-00 00:00:00', '', '*');



-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `#__phocaguestbook_logging`
--

CREATE TABLE IF NOT EXISTS `#__phocaguestbook_logging` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `catid` int(11) NOT NULL default '0',
  `postid` int(11) NOT NULL default '0',
  `captchaid` smallint(6) NOT NULL default '0',
  `fields` tinyint(4) NOT NULL default '0',
  `hidden_field` tinyint(4) NOT NULL default '0',
  `forbidden_word` tinyint(4) NOT NULL default '0',
  `used_time` int(11) NOT NULL default '0',
  `session` tinyint(4) NOT NULL default '0',
  `incoming_page` varchar(255) NOT NULL default '',
  `ip` varchar(50) NOT NULL default '',
  `ip_list` tinyint(4) NOT NULL default '0',
  `ip_stopforum` tinyint(4) NOT NULL default '0',
  `ip_stopforum_txt` varchar(128) NOT NULL default '',
  `ip_honeypot` tinyint(4) NOT NULL default '0',
  `ip_botscout` tinyint(4) NOT NULL default '0',
  `ip_botscout_txt` varchar(128) NOT NULL default '',
  `content_akismet` tinyint(4) NOT NULL default '0',
  `content_akismet_txt` varchar(128) NOT NULL default '',
  `content_mollom` tinyint(4) NOT NULL default '0',
  `content_mollom_txt` varchar(128) NOT NULL default '',
  `date` datetime NOT NULL,
  `state` tinyint(3) NOT NULL default '0',
  `params` text,
  PRIMARY KEY  (`id`),
  KEY `state` (`state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
