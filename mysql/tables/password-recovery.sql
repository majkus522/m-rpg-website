CREATE TABLE `password-recovery`
(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player` int(11) NOT NULL,
  `code` text NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`player`) REFERENCES `players`(id) 
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

ALTER TABLE `password-recovery` AUTO_INCREMENT=0;