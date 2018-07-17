https://raw.githubusercontent.com/wiki/wielebenwir/commons-booking-2/etc/commons-booking-2-db-tables.sql.txt

we need to restructure the views a bit
wp_cb2_view_calendar_periods is based on day
  it needs to be based on recurrence (no date)
view_calendar_period_items should then link in the RWO

and finaly a last view can then request by day / week etc.

all views need to depend on WP_Post like responses with get_post_meta
advanced wordpress style pre-cache IN(...) requests would be good
