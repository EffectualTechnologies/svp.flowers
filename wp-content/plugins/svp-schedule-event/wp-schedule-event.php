<?php
/**
 * Plugin Name: SVP WP Schedule Event
 * Description: Custom plugin to schedul event
 * Version: 0.1
 */

//schedule even
register_activation_hook(__FILE__, 'svp_start_schedule_event');

function svp_start_schedule_event() {
    if (! wp_next_scheduled ( 'svp_daily_subscr_check' )) {
        wp_schedule_event( time(), 'hourly', 'svp_daily_subscr_check');
    }
}

add_action('svp_daily_subscr_check', 'daily_subscr_check_func');

function daily_subscr_check_func() {
    $svp_cron_subscr_restart_time = get_transient( 'svp_cron_subscr_restart_time' );

    $occurance = array_keys( $svp_cron_subscr_restart_time, date( 'd-m-y' ) );

    if ( !empty( $occurance ) ) {
        foreach( $occurance as $key => $sub_id ) {
            WCS_User_Change_Status_Handler::change_users_subscription( wcs_get_subscription( $sub_id ), 'active' );
            unset( $svp_cron_subscr_restart_time[$sub_id] );
        }
    }

    set_transient( 'svp_cron_subscr_restart_time', $svp_cron_subscr_restart_time );
}

register_deactivation_hook(__FILE__, 'svp_start_schedule_event_deactivation');

function svp_start_schedule_event_deactivation() {
    wp_clear_scheduled_hook('svp_daily_subscr_check');
}

