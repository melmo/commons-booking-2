SELECT 
    wp_cb2_view_perioditem_posts.*
FROM
    wp_cb2_view_perioditem_posts
WHERE
    1 = 1
        AND ((wp_cb2_view_perioditem_posts.post_date > '2018-07-01 00:00:00'
        AND wp_cb2_view_perioditem_posts.post_date < '2018-08-01 00:00:00'))
        AND wp_cb2_view_perioditem_posts.post_type IN ('perioditem-automatic' , 'perioditem-global',
        'perioditem-location',
        'perioditem-timeframe',
        'perioditem-user')
        AND ((wp_cb2_view_perioditem_posts.post_status = 'publish'
        OR wp_cb2_view_perioditem_posts.post_status = 'auto-draft'))
ORDER BY wp_cb2_view_perioditem_posts.post_date ASC