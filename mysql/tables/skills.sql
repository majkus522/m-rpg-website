CREATE TABLE `skills`
(
  `id` int(11) NOT NULL,
  `player` int(11) NOT NULL,
  `skill` text NOT NULL,
  `toggle` tinyint(1) NOT NULL DEFAULT 0
);

ALTER TABLE `skills` ADD PRIMARY KEY (`id`);

ALTER TABLE `skills` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;