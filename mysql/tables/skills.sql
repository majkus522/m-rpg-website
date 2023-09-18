CREATE TABLE `skills`
(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player` int(11) NOT NULL,
  `skill` text NOT NULL,
  `toggle` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`player`) REFERENCES `players`(id) 
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

ALTER TABLE `password-recovery` AUTO_INCREMENT=0;