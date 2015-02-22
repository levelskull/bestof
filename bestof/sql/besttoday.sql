
delimiter $$

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `besttoday` AS select `post_type`.`name` AS `post_name`,`nav_tag`.`name` AS `nav_name`,`content`.`prodlink` AS `prodlink`,`content`.`title` AS `title`,`content`.`author` AS `author`,`content`.`release_date` AS `release_date`,`content`.`wk_spent` AS `wk_spent`,`content`.`content` AS `content`,`content`.`yr` AS `yr`,`content`.`showdte` AS `showdte`,`content`.`post_type` AS `post_type`,`content`.`nav_tag` AS `nav_tag` from ((`content` left join `nav_tag` on((`nav_tag`.`seq` = `content`.`nav_tag`))) left join `post_type` on((`post_type`.`seq` = `content`.`post_type`))) where (`post_type`.`name` <> 'Feature') order by `content`.`yr`,`post_type`.`name`$$

