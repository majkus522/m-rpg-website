CREATE TABLE `players-sessions`
(
  `id` int(11) NOT NULL,
  `player` int(11) NOT NULL,
  `type` text NOT NULL,
  `key` text NOT NULL,
  `temp` tinyint(1) NOT NULL,
  `date` datetime NOT NULL
);

ALTER TABLE `players-sessions` ADD PRIMARY KEY (`id`);

ALTER TABLE `players-sessions` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;