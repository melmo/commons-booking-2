CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`127.0.0.1` SQL SECURITY DEFINER VIEW `wp_cb2_view_calendar_periods` AS select `sq`.`datetime_start` AS `datetime_start`,NULL AS `period_id`,NULL AS `recurrence_index`,cast('00:00:00' as time) AS `time_start`,cast('23:59:59' as time) AS `time_end` from `wp_cb2_view_sequence` `sq` union all select `sq`.`datetime_start` AS `datetime_start`,`pr`.`period_id` AS `period_id`,0 AS `recurrence_index`,cast(`pr`.`datetime_part_period_start` as time) AS `time_start`,cast(`pr`.`datetime_part_period_end` as time) AS `time_end` from (`wp_cb2_view_sequence` `sq` join `wp_cb2_periods` `pr` on((isnull(`pr`.`recurrence_type`) and (`pr`.`datetime_part_period_start` < `sq`.`datetime_end`) and (`pr`.`datetime_part_period_end` > `sq`.`datetime_start`)))) where ((`pr`.`datetime_from` <= `sq`.`datetime_start`) and (isnull(`pr`.`datetime_to`) or (`pr`.`datetime_to` >= `sq`.`datetime_end`))) union all select `sq`.`datetime_start` AS `datetime_start`,`pr`.`period_id` AS `period_id`,(extract(year from `sq`.`datetime_start`) - extract(year from `pr`.`datetime_from`)) AS `recurrence_index`,cast(`pr`.`datetime_part_period_start` as time) AS `time_start`,cast(`pr`.`datetime_part_period_end` as time) AS `time_end` from (`wp_cb2_view_sequence` `sq` join `wp_cb2_periods` `pr` on(((`pr`.`recurrence_type` = 'Y') and ((`pr`.`datetime_part_period_start` - interval extract(year from `pr`.`datetime_part_period_start`) year) < (`sq`.`datetime_end` - interval extract(year from `sq`.`datetime_end`) year)) and ((`pr`.`datetime_part_period_end` - interval extract(year from `pr`.`datetime_part_period_end`) year) > (`sq`.`datetime_start` - interval extract(year from `sq`.`datetime_start`) year))))) where ((`pr`.`datetime_from` <= `sq`.`datetime_start`) and (isnull(`pr`.`datetime_to`) or (`pr`.`datetime_to` >= `sq`.`datetime_end`))) union all select `sq`.`datetime_start` AS `datetime_start`,`pr`.`period_id` AS `period_id`,period_diff(date_format(`sq`.`datetime_start`,'%Y%m'),date_format(`pr`.`datetime_from`,'%Y%m')) AS `recurrence_index`,cast(`pr`.`datetime_part_period_start` as time) AS `time_start`,cast(`pr`.`datetime_part_period_end` as time) AS `time_end` from (`wp_cb2_view_sequence` `sq` join `wp_cb2_periods` `pr` on(((`pr`.`recurrence_type` = 'M') and (((`pr`.`datetime_part_period_start` - interval extract(year from `pr`.`datetime_part_period_start`) year) - interval (extract(month from `pr`.`datetime_part_period_start`) - 1) month) < ((`sq`.`datetime_end` - interval extract(year from `sq`.`datetime_end`) year) - interval (extract(month from `sq`.`datetime_end`) - 1) month)) and (((`pr`.`datetime_part_period_end` - interval extract(year from `pr`.`datetime_part_period_end`) year) - interval (extract(month from `pr`.`datetime_part_period_end`) - 1) month) > ((`sq`.`datetime_start` - interval extract(year from `sq`.`datetime_start`) year) - interval (extract(month from `sq`.`datetime_start`) - 1) month)) and (isnull(`pr`.`recurrence_sequence`) or (`pr`.`recurrence_sequence` & pow(2,(extract(month from `sq`.`datetime_start`) - 1))))))) where ((`pr`.`datetime_from` <= `sq`.`datetime_start`) and (isnull(`pr`.`datetime_to`) or (`pr`.`datetime_to` >= `sq`.`datetime_end`))) union all select `sq`.`datetime_start` AS `datetime_start`,`pr`.`period_id` AS `period_id`,((week(`sq`.`datetime_start`,0) - week(`pr`.`datetime_from`,0)) + ((year(`sq`.`datetime_start`) - year(`pr`.`datetime_from`)) * 52)) AS `recurrence_index`,cast(`pr`.`datetime_part_period_start` as time) AS `time_start`,cast(`pr`.`datetime_part_period_end` as time) AS `time_end` from (`wp_cb2_view_sequence` `sq` join `wp_cb2_periods` `pr` on(((`pr`.`recurrence_type` = 'W') and (dayofweek(`pr`.`datetime_part_period_start`) <= dayofweek(`sq`.`datetime_end`)) and (dayofweek(`pr`.`datetime_part_period_end`) >= dayofweek(`sq`.`datetime_start`)) and (isnull(`pr`.`recurrence_sequence`) or (`pr`.`recurrence_sequence` & pow(2,extract(week from `sq`.`datetime_start`))))))) where ((`pr`.`datetime_from` <= `sq`.`datetime_start`) and (isnull(`pr`.`datetime_to`) or (`pr`.`datetime_to` >= `sq`.`datetime_end`))) union all select `sq`.`datetime_start` AS `datetime_start`,`pr`.`period_id` AS `period_id`,(to_days(`sq`.`datetime_start`) - to_days(`pr`.`datetime_from`)) AS `recurrence_index`,cast(`pr`.`datetime_part_period_start` as time) AS `time_start`,cast(`pr`.`datetime_part_period_end` as time) AS `time_end` from (`wp_cb2_view_sequence` `sq` join `wp_cb2_periods` `pr` on(((`pr`.`recurrence_type` = 'D') and (isnull(`pr`.`recurrence_sequence`) or (`pr`.`recurrence_sequence` & pow(2,(dayofweek(`sq`.`datetime_start`) - 1))))))) where ((`pr`.`datetime_from` <= `sq`.`datetime_start`) and (isnull(`pr`.`datetime_to`) or (`pr`.`datetime_to` >= `sq`.`datetime_end`)));