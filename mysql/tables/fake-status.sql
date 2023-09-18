CREATE TABLE `fake-status`
(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player` int(11) NOT NULL,
  `level` int(11) NOT NULL DEFAULT 1,
  `money` float NOT NULL DEFAULT 0,
  `str` int(11) NOT NULL DEFAULT 0,
  `agl` int(11) NOT NULL DEFAULT 0,
  `chr` int(11) NOT NULL DEFAULT 0,
  `intl` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`player`) REFERENCES `players`(id) 
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

ALTER TABLE `fake-status` AUTO_INCREMENT=0;