CREATE 
    ALGORITHM = UNDEFINED 
    DEFINER = `root`@`127.0.0.1` 
    SQL SECURITY DEFINER
VIEW `wp_cb2_view_locations` AS
    SELECT 
        `p`.`ID` AS `ID`,
        `p`.`post_author` AS `post_author`,
        `p`.`post_date` AS `post_date`,
        `p`.`post_date_gmt` AS `post_date_gmt`,
        `p`.`post_content` AS `post_content`,
        `p`.`post_title` AS `post_title`,
        `p`.`post_excerpt` AS `post_excerpt`,
        `p`.`post_status` AS `post_status`,
        `p`.`comment_status` AS `comment_status`,
        `p`.`ping_status` AS `ping_status`,
        `p`.`post_password` AS `post_password`,
        `p`.`post_name` AS `post_name`,
        `p`.`to_ping` AS `to_ping`,
        `p`.`pinged` AS `pinged`,
        `p`.`post_modified` AS `post_modified`,
        `p`.`post_modified_gmt` AS `post_modified_gmt`,
        `p`.`post_content_filtered` AS `post_content_filtered`,
        `p`.`post_parent` AS `post_parent`,
        `p`.`guid` AS `guid`,
        `p`.`menu_order` AS `menu_order`,
        `p`.`post_type` AS `post_type`,
        `p`.`post_mime_type` AS `post_mime_type`,
        `p`.`comment_count` AS `comment_count`,
        `pm_email`.`meta_value` AS `email`
    FROM
        (`wp_posts` `p`
        LEFT JOIN `wp_postmeta` `pm_email` ON (((`p`.`ID` = `pm_email`.`post_id`)
            AND (`pm_email`.`meta_key` = 'email'))))
    WHERE
        ((`p`.`post_type` = 'location')
            AND (`p`.`post_status` = 'publish'));
