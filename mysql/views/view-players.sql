CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view-players` AS SELECT
    `players`.`id` AS `id`,
    `players`.`username` AS `username`,
    `players`.`email` AS `email`,
    `players`.`level` AS `level`,
    `players`.`exp` AS `exp`,
    `players`.`money` AS `money`,
    `players`.`clazz` AS `clazz`,
    `players`.`str` AS `str`,
    `players`.`agl` AS `agl`,
    `players`.`chr` AS `chr`,
    `players`.`intl` AS `intl`,
    `players`.`def` AS `def`,
    `players`.`vtl` AS `vtl`,
    `players`.`dex` AS `dex`
FROM `players`;