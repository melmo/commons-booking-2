create or replace view wp_cb2_view_posts as
SELECT 
    cal.period_id * 1000000000 + cal.recurrence_index + 1 as ID,
    ifnull(cal.user_ID, 1) as post_author,
	date as post_date,
	date as post_date_gmt,
	'' as post_content,
	pst.name as post_title,
	'' as post_excerpt,
	'publish' as post_status,
	'closed' as comment_status,
	'closed' as ping_status,
	'' as post_password,
	cal.period_id * 1000000000 + cal.recurrence_index + 1 as post_name,
	'' as to_ping,
	'' as pinged,
	date as post_modified,
	date as post_modified_gmt,
	'' as post_content_filtered,
	cal.period_id * 1000000000 as post_parent,
	'' as guid,
	0 as menu_order,
	'periodoccurrence' as post_type,
	'' as post_mime_type,
	0 as comment_count
FROM
    wp_cb2_view_calendar_period_items cal
        LEFT OUTER JOIN
    wp_cb2_periods p ON cal.period_id = p.period_id
        LEFT OUTER JOIN
    wp_cb2_period_status_types pst ON pst.period_status_type_id = p.period_status_type_id
where not isnull(cal.period_id)

union all 
SELECT 
	p.period_id * 1000000000 as ID,
    1 as post_author,
	datetime_from as post_date,
	datetime_from as post_date_gmt,
	'' as post_content,
	pst.name as post_title,
	'' as post_excerpt,
	'publish' as post_status,
	'closed' as comment_status,
	'closed' as ping_status,
	'' as post_password,
	p.period_id * 1000000000 as post_name,
	'' as to_ping,
	'' as pinged,
	datetime_to as post_modified,
	datetime_to as post_modified_gmt,
	'' as post_content_filtered,
	0 as post_parent,
	'' as guid,
	0 as menu_order,
	'period' as post_type,
	'' as post_mime_type,
	0 as comment_count
from wp_cb2_periods p
inner join wp_cb2_period_status_types pst on p.period_status_type_id = pst.period_status_type_id
