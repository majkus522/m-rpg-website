CREATE TABLE `players`
(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `email` text NOT NULL,
  `password` longtext NOT NULL,
  `level` int(11) NOT NULL DEFAULT 1,
  `exp` int(11) NOT NULL DEFAULT 0,
  `money` float NOT NULL DEFAULT 0,
  `clazz` enum('warrior','archer','mage','tank', 'sorcerer', 'warlock') DEFAULT NULL,
  `str` int(11) NOT NULL DEFAULT 0,
  `agl` int(11) NOT NULL DEFAULT 0,
  `chr` int(11) NOT NULL DEFAULT 0,
  `intl` int(11) NOT NULL DEFAULT 0,
  `def` int(11) NOT NULL DEFAULT 0,
  `vtl` int(11) NOT NULL DEFAULT 0,
  `dex` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
);

ALTER TABLE `players` AUTO_INCREMENT=0;