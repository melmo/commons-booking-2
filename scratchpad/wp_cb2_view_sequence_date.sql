CREATE 
    ALGORITHM = UNDEFINED 
    DEFINER = `root`@`127.0.0.1` 
    SQL SECURITY DEFINER
VIEW `wp_cb2_view_sequence_date` AS
    SELECT 
        ((((`t4`.`num` * 1000) + (`t3`.`num` * 100)) + (`t2`.`num` * 10)) + `t1`.`num`) AS `num`,
        (CAST('2018-01-01' AS DATETIME) + INTERVAL ((((`t4`.`num` * 1000) + (`t3`.`num` * 100)) + (`t2`.`num` * 10)) + `t1`.`num`) DAY) AS `datetime_start`,
        (CAST('2018-01-01' AS DATETIME) + INTERVAL (((((`t4`.`num` * 1000) + (`t3`.`num` * 100)) + (`t2`.`num` * 10)) + `t1`.`num`) + 1) DAY) AS `datetime_end`
    FROM
        (((`wp_cb2_view_sequence_num` `t1`
        JOIN `wp_cb2_view_sequence_num` `t2`)
        JOIN `wp_cb2_view_sequence_num` `t3`)
        JOIN `wp_cb2_view_sequence_num` `t4`)