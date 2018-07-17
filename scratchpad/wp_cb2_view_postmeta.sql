create or replace view wp_cb2_view_postmeta as
# ----------------------------------------- period
select 
	p.period_id * 1000000000 as meta_id,
	p.period_id * 1000000000 as post_id,
	'recurrence_type' as meta_key,
    p.recurrence_type as meta_value
from wp_cb2_periods p

union all

select 
	p.period_id * 1000000000 as meta_id,
	p.period_id * 1000000000 as post_id,
	'recurrence_frequency' as meta_key,
    p.recurrence_frequency as meta_value
from wp_cb2_periods p

union all

select 
	p.period_id * 1000000000 as meta_id,
	p.period_id * 1000000000 as post_id,
	'recurrence_sequence' as meta_key,
    p.recurrence_sequence as meta_value
from wp_cb2_periods p

union all

# ----------------------------------------- periodoccurrence
select 
	cal.period_id * 1000000000 + cal.recurrence_index + 1 as meta_id,
	cal.period_id * 1000000000 + cal.recurrence_index + 1 as post_id,
	'timeframe_ID' as meta_key,
    cal.timeframe_ID as meta_value
from wp_cb2_view_calendar_period_items cal

union all

select 
	cal.period_id * 1000000000 + cal.recurrence_index + 1 as meta_id,
	cal.period_id * 1000000000 + cal.recurrence_index + 1 as post_id,
	'location_ID' as meta_key,
    cal.location_ID as meta_value
from wp_cb2_view_calendar_period_items cal

union all

select 
	cal.period_id * 1000000000 + cal.recurrence_index + 1 as meta_id,
	cal.period_id * 1000000000 + cal.recurrence_index + 1 as post_id,
	'item_ID' as meta_key,
    cal.item_ID as meta_value
from wp_cb2_view_calendar_period_items cal

union all

select 
	cal.period_id * 1000000000 + cal.recurrence_index + 1 as meta_id,
	cal.period_id * 1000000000 + cal.recurrence_index + 1 as post_id,
	'user_ID' as meta_key,
    cal.user_ID as meta_value
from wp_cb2_view_calendar_period_items cal