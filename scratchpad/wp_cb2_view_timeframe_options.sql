CREATE 
    ALGORITHM = UNDEFINED 
    DEFINER = `root`@`127.0.0.1` 
    SQL SECURITY DEFINER
VIEW `wp_cb2_view_timeframe_options` AS
    SELECT DISTINCT
        `c2to`.`timeframe_id` AS `timeframe_id`,
        (SELECT 
                `wp_cb2_timeframe_options`.`option_value`
            FROM
                `wp_cb2_timeframe_options`
            WHERE
                ((`wp_cb2_timeframe_options`.`timeframe_id` = `c2to`.`timeframe_id`)
                    AND (`wp_cb2_timeframe_options`.`option_name` = 'max-slots'))
            ORDER BY `wp_cb2_timeframe_options`.`option_id` DESC
            LIMIT 1) AS `max-slots`,
        (SELECT 
                `wp_cb2_timeframe_options`.`option_value`
            FROM
                `wp_cb2_timeframe_options`
            WHERE
                ((`wp_cb2_timeframe_options`.`timeframe_id` = `c2to`.`timeframe_id`)
                    AND (`wp_cb2_timeframe_options`.`option_name` = 'closed-days-booking'))
            ORDER BY `wp_cb2_timeframe_options`.`option_id` DESC
            LIMIT 1) AS `closed-days-booking`,
        (SELECT 
                `wp_cb2_timeframe_options`.`option_value`
            FROM
                `wp_cb2_timeframe_options`
            WHERE
                ((`wp_cb2_timeframe_options`.`timeframe_id` = `c2to`.`timeframe_id`)
                    AND (`wp_cb2_timeframe_options`.`option_name` = 'consequtive-slots'))
            ORDER BY `wp_cb2_timeframe_options`.`option_id` DESC
            LIMIT 1) AS `consequtive-slots`,
        (SELECT 
                `wp_cb2_timeframe_options`.`option_value`
            FROM
                `wp_cb2_timeframe_options`
            WHERE
                ((`wp_cb2_timeframe_options`.`timeframe_id` = `c2to`.`timeframe_id`)
                    AND (`wp_cb2_timeframe_options`.`option_name` = 'use-codes'))
            ORDER BY `wp_cb2_timeframe_options`.`option_id` DESC
            LIMIT 1) AS `use-codes`,
        (SELECT 
                `wp_cb2_timeframe_options`.`option_value`
            FROM
                `wp_cb2_timeframe_options`
            WHERE
                ((`wp_cb2_timeframe_options`.`timeframe_id` = `c2to`.`timeframe_id`)
                    AND (`wp_cb2_timeframe_options`.`option_name` = 'limit'))
            ORDER BY `wp_cb2_timeframe_options`.`option_id` DESC
            LIMIT 1) AS `limit`,
        (SELECT 
                `wp_cb2_timeframe_options`.`option_value`
            FROM
                `wp_cb2_timeframe_options`
            WHERE
                ((`wp_cb2_timeframe_options`.`timeframe_id` = `c2to`.`timeframe_id`)
                    AND (`wp_cb2_timeframe_options`.`option_name` = 'holiday_provider'))
            ORDER BY `wp_cb2_timeframe_options`.`option_id` DESC
            LIMIT 1) AS `holiday-provider`
    FROM
        `wp_cb2_timeframe_options` `c2to`