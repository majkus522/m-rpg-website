CREATE TABLE `players`
(
  `id` int(11) NOT NULL,
  `username` text NOT NULL,
  `email` text NOT NULL,
  `password` longtext NOT NULL,
  `world` text NOT NULL DEFAULT '',
  `level` int(11) NOT NULL DEFAULT 1,
  `exp` int(11) NOT NULL DEFAULT 0,
  `money` float NOT NULL DEFAULT 0,
  `str` int(11) NOT NULL DEFAULT 0,
  `agl` int(11) NOT NULL DEFAULT 0,
  `chr` int(11) NOT NULL DEFAULT 0,
  `intl` int(11) NOT NULL DEFAULT 0
);

ALTER TABLE `players` ADD PRIMARY KEY (`id`);

ALTER TABLE `players` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;