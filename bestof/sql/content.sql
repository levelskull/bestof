
CREATE  TABLE `bestof`.`content` (
  `seq` INT NOT NULL AUTO_INCREMENT ,
  `post_type` INT NULL ,
  `nav_tag` INT NULL ,
  `prodlink` VARCHAR(150) NULL ,
  `title` VARCHAR(125) NULL ,
  `author` VARCHAR(45) NULL ,
  `release_date` DATE NULL ,
  `wk_spent` INT NULL ,
  `content` TEXT NULL ,
  PRIMARY KEY (`seq`) );
