delimiter $$

CREATE TABLE `content` (
  `seq` int(11) NOT NULL AUTO_INCREMENT,
  `post_type` int(11) DEFAULT NULL,
  `nav_tag` int(11) DEFAULT NULL,
  `prodlink` varchar(150) DEFAULT NULL,
  `title` varchar(125) DEFAULT NULL,
  `author` varchar(45) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `wk_spent` int(11) DEFAULT NULL,
  `content` text,
  `yr` int(4) DEFAULT NULL,
  `showdte` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`seq`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1$$

