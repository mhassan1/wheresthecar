CREATE TABLE IF NOT EXISTS `car_aspsuspended` (
  `date` date NOT NULL,
  `why` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `car_aspsuspended` (`date`, `why`) VALUES
('2014-11-04', 'Election Day'),
('2014-11-11', 'Veterans Day'),
('2014-11-27', 'Thanksgiving'),
('2014-12-08', 'Immaculate Conception'),
('2014-12-25', 'Christmas'),
('2015-07-03', 'Independence Day'),
('2015-07-17', 'Eid al-Fitr'),
('2015-09-07', 'Labor Day'),
('2015-09-14', 'Rosh Hashanah'),
('2015-09-15', 'Rosh Hashanah'),
('2015-09-23', 'Yom Kippur'),
('2015-09-24', 'Eid al-Adha'),
('2015-09-25', 'Eid al-Adha'),
('2015-09-28', 'Sukkot'),
('2015-09-29', 'Sukkot'),
('2015-10-05', 'Shemini Atzereth'),
('2015-10-06', 'Simchat Torah'),
('2015-10-12', 'Columbus Day'),
('2015-11-03', 'Election Day'),
('2015-11-11', 'Veterans Day'),
('2015-11-26', 'Thanksgiving'),
('2015-12-08', 'Immaculate Conception'),
('2015-12-25', 'Christmas');

CREATE TABLE IF NOT EXISTS `car_location` (
  `car_location_key` int(11) NOT NULL AUTO_INCREMENT,
  `lat` decimal(11,8) DEFAULT NULL,
  `lng` decimal(11,8) DEFAULT NULL,
  `who` varchar(45) DEFAULT NULL,
  `sign` varchar(45) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `active` varchar(1) DEFAULT 'Y',
  PRIMARY KEY (`car_location_key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=96 ;

CREATE TABLE IF NOT EXISTS `wtc_car_location` (
  `wtc_car_location_key` int(11) NOT NULL AUTO_INCREMENT,
  `wtc_car_key` int(11) NOT NULL,
  `lat` decimal(10,8) NOT NULL,
  `lng` decimal(10,8) NOT NULL,
  `who` varchar(45) DEFAULT NULL,
  `sign` varchar(45) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `active` varchar(1) DEFAULT 'Y',
  PRIMARY KEY (`wtc_car_location_key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=61 ;
