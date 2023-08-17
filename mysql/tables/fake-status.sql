CREATE TABLE `fake-status`
(
  `id` int(11) NOT NULL,
  `player` int(11) NOT NULL,
  `level` int(11) NOT NULL DEFAULT 1,
  `money` float NOT NULL DEFAULT 0,
  `str` int(11) NOT NULL DEFAULT 0,
  `agl` int(11) NOT NULL DEFAULT 0,
  `chr` int(11) NOT NULL DEFAULT 0,
  `intl` int(11) NOT NULL DEFAULT 0
);

ALTER TABLE `fake-status` ADD PRIMARY KEY (`id`);

ALTER TABLE `fake-status` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;