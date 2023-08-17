CREATE TABLE `password-recovery`
(
  `id` int(11) NOT NULL,
  `player` int(11) NOT NULL,
  `code` text NOT NULL,
  `date` datetime NOT NULL
);

ALTER TABLE `password-recovery` ADD PRIMARY KEY (`id`);
  
ALTER TABLE `password-recovery` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;