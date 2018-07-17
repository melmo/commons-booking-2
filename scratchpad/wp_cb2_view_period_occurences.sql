CREATE 
VIEW `wp_cb2_view_period_occurences` AS
	SELECT 
        `pr`.`period_id` AS `period_id`,
        0 AS `recurrence_index`,
        `pr`.`datetime_part_period_start`,
        `pr`.`datetime_part_period_end`
    FROM
        `wp_cb2_periods` `pr` 
	WHERE ISNULL(`pr`.`recurrence_type`)
    UNION ALL SELECT 
        `pr`.`period_id` AS `period_id`,
        `sq`.`num` AS `recurrence_index`,
        date_add( `pr`.`datetime_part_period_start`, INTERVAL `sq`.`num` YEAR ),
        date_add( `pr`.`datetime_part_period_end`, INTERVAL `sq`.`num` YEAR )
    FROM
        (`wp_cb2_view_sequence` `sq`
        JOIN `wp_cb2_periods` `pr` ON (((`pr`.`recurrence_type` = 'Y')
            AND ((`pr`.`datetime_part_period_start` - INTERVAL EXTRACT(YEAR FROM `pr`.`datetime_part_period_start`) YEAR) < (`sq`.`datetime_end` - INTERVAL EXTRACT(YEAR FROM `sq`.`datetime_end`) YEAR))
            AND ((`pr`.`datetime_part_period_end` - INTERVAL EXTRACT(YEAR FROM `pr`.`datetime_part_period_end`) YEAR) > (`sq`.`datetime_start` - INTERVAL EXTRACT(YEAR FROM `sq`.`datetime_start`) YEAR)))))
    WHERE
        ((`pr`.`datetime_from` <= `sq`.`datetime_start`)
            AND (ISNULL(`pr`.`datetime_to`)
            OR (`pr`.`datetime_to` >= `sq`.`datetime_end`))) 
    UNION ALL SELECT 
        `sq`.`datetime_start` AS `datetime_start`,
        `pr`.`period_id` AS `period_id`,
        PERIOD_DIFF(DATE_FORMAT(`sq`.`datetime_start`, '%Y%m'),
                DATE_FORMAT(`pr`.`datetime_from`, '%Y%m')) AS `recurrence_index`,
        CAST(`pr`.`datetime_part_period_start` AS TIME) AS `time_start`,
        CAST(`pr`.`datetime_part_period_end` AS TIME) AS `time_end`
    FROM
        (`wp_cb2_view_sequence` `sq`
        JOIN `wp_cb2_periods` `pr` ON (((`pr`.`recurrence_type` = 'M')
            AND (((`pr`.`datetime_part_period_start` - INTERVAL EXTRACT(YEAR FROM `pr`.`datetime_part_period_start`) YEAR) - INTERVAL (EXTRACT(MONTH FROM `pr`.`datetime_part_period_start`) - 1) MONTH) < ((`sq`.`datetime_end` - INTERVAL EXTRACT(YEAR FROM `sq`.`datetime_end`) YEAR) - INTERVAL (EXTRACT(MONTH FROM `sq`.`datetime_end`) - 1) MONTH))
            AND (((`pr`.`datetime_part_period_end` - INTERVAL EXTRACT(YEAR FROM `pr`.`datetime_part_period_end`) YEAR) - INTERVAL (EXTRACT(MONTH FROM `pr`.`datetime_part_period_end`) - 1) MONTH) > ((`sq`.`datetime_start` - INTERVAL EXTRACT(YEAR FROM `sq`.`datetime_start`) YEAR) - INTERVAL (EXTRACT(MONTH FROM `sq`.`datetime_start`) - 1) MONTH))
            AND (ISNULL(`pr`.`recurrence_sequence`)
            OR (`pr`.`recurrence_sequence` & POW(2, (EXTRACT(MONTH FROM `sq`.`datetime_start`) - 1)))))))
    WHERE
        ((`pr`.`datetime_from` <= `sq`.`datetime_start`)
            AND (ISNULL(`pr`.`datetime_to`)
            OR (`pr`.`datetime_to` >= `sq`.`datetime_end`))) 
    UNION ALL SELECT 
        `sq`.`datetime_start` AS `datetime_start`,
        `pr`.`period_id` AS `period_id`,
        ((WEEK(`sq`.`datetime_start`, 0) - WEEK(`pr`.`datetime_from`, 0)) + ((YEAR(`sq`.`datetime_start`) - YEAR(`pr`.`datetime_from`)) * 52)) AS `recurrence_index`,
        CAST(`pr`.`datetime_part_period_start` AS TIME) AS `time_start`,
        CAST(`pr`.`datetime_part_period_end` AS TIME) AS `time_end`
    FROM
        (`wp_cb2_view_sequence` `sq`
        JOIN `wp_cb2_periods` `pr` ON (((`pr`.`recurrence_type` = 'W')
            AND (DAYOFWEEK(`pr`.`datetime_part_period_start`) <= DAYOFWEEK(`sq`.`datetime_end`))
            AND (DAYOFWEEK(`pr`.`datetime_part_period_end`) >= DAYOFWEEK(`sq`.`datetime_start`))
            AND (ISNULL(`pr`.`recurrence_sequence`)
            OR (`pr`.`recurrence_sequence` & POW(2, EXTRACT(WEEK FROM `sq`.`datetime_start`)))))))
    WHERE
        ((`pr`.`datetime_from` <= `sq`.`datetime_start`)
            AND (ISNULL(`pr`.`datetime_to`)
            OR (`pr`.`datetime_to` >= `sq`.`datetime_end`))) 
    UNION ALL SELECT 
        `sq`.`datetime_start` AS `datetime_start`,
        `pr`.`period_id` AS `period_id`,
        (TO_DAYS(`sq`.`datetime_start`) - TO_DAYS(`pr`.`datetime_from`)) AS `recurrence_index`,
        CAST(`pr`.`datetime_part_period_start` AS TIME) AS `time_start`,
        CAST(`pr`.`datetime_part_period_end` AS TIME) AS `time_end`
    FROM
        (`wp_cb2_view_sequence` `sq`
        JOIN `wp_cb2_periods` `pr` ON (((`pr`.`recurrence_type` = 'D')
            AND (ISNULL(`pr`.`recurrence_sequence`)
            OR (`pr`.`recurrence_sequence` & POW(2, (DAYOFWEEK(`sq`.`datetime_start`) - 1)))))))
    WHERE
        ((`pr`.`datetime_from` <= `sq`.`datetime_start`)
            AND (ISNULL(`pr`.`datetime_to`)
            OR (`pr`.`datetime_to` >= `sq`.`datetime_end`)));
