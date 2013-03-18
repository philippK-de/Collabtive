CREATE TABLE IF NOT EXISTS `customer` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(64) NOT NULL,
  `mobile` varchar(64) NOT NULL,
  `url` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `zip` varchar(16) NOT NULL,
  `city` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `customer_assigned` (
  `customer` int(10) unsigned NOT NULL,
  `project` int(10) NOT NULL,
  UNIQUE KEY `customerXproject` (`customer`,`project`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
