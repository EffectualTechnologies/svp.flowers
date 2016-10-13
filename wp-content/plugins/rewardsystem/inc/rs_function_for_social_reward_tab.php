<?php

class RSFunctionForSocialRewards {

    public function __construct() {

        add_action('admin_head', array($this, 'reward_system_social_url'));

        if (isset($_GET['tab'])) {
            if ($_GET['tab'] == 'rewardsystem_socialrewards') {
                add_action('admin_head', array($this, 'social_rewards_global_settings_script'));
            }
        }
        add_action('admin_enqueue_scripts', array($this, 'rs_color_picker'));

        add_action('admin_head', array($this, 'rs_validation_of_input_field_in_social_reward'));

        if (get_option('rs_global_position_sumo_social_buttons') == '1') {

            add_action('woocommerce_before_single_product', array($this, 'reward_system_social_likes_buttons'));
        } elseif (get_option('rs_global_position_sumo_social_buttons') == '2') {

            add_action('woocommerce_before_single_product_summary', array($this, 'reward_system_social_likes_buttons'));
        } elseif (get_option('rs_global_position_sumo_social_buttons') == '3') {

            add_action('woocommerce_single_product_summary', array($this, 'reward_system_social_likes_buttons'));
        } elseif (get_option('rs_global_position_sumo_social_buttons') == '4') {

            add_action('woocommerce_after_single_product', array($this, 'reward_system_social_likes_buttons'));
        } elseif (get_option('rs_global_position_sumo_social_buttons') == '6') {

            add_action('woocommerce_product_meta_end', array($this, 'reward_system_social_likes_buttons'));
        } else {

            add_action('woocommerce_after_single_product_summary', array($this, 'reward_system_social_likes_buttons'));
        }

        add_action('wp_head', array($this, 'add_fb_style_hide_comment_box'));

        add_action('wp_ajax_nopriv_rssocialfacebookcallback', array($this, 'update_reward_points_for_facebook_like'));

        add_action('wp_ajax_rssocialfacebookcallback', array($this, 'update_reward_points_for_facebook_like'));


        add_action('wp_ajax_nopriv_rssocialfacebooksharecallback', array($this, 'update_reward_points_for_facebook_share'));

        add_action('wp_ajax_rssocialfacebooksharecallback', array($this, 'update_reward_points_for_facebook_share'));


        add_action('wp_ajax_nopriv_rssocialtwittercallback', array($this, 'update_reward_points_for_twitter_tweet'));

        add_action('wp_ajax_rssocialtwittercallback', array($this, 'update_reward_points_for_twitter_tweet'));

        add_action('wp_ajax_nopriv_rssocialgooglecallback', array($this, 'update_reward_points_for_google_plus_share'));

        add_action('wp_ajax_rssocialgooglecallback', array($this, 'update_reward_points_for_google_plus_share'));

        add_action('wp_ajax_nopriv_rsvkcallback', array($this, 'update_reward_points_for_vk_like'));

        add_action('wp_ajax_rsvkcallback', array($this, 'update_reward_points_for_vk_like'));

        add_shortcode('google_share_reward_points', array($this, 'add_shortcode_for_social_google_share'));

        add_shortcode('vk_reward_points', array($this, 'add_shortcode_for_social_vk_like'));

        add_shortcode('twitter_tweet_reward_points', array($this, 'add_shortcode_for_social_twitter_tweet'));

        add_shortcode('facebook_like_reward_points', array($this, 'add_shortcode_for_social_facebook_like'));

        add_shortcode('facebook_share_reward_points', array($this, 'add_shortcode_for_social_facebook_share'));


        add_action('admin_enqueue_scripts', array($this, 'add_enqueue_jscolor_for_social_messages'));

        add_action('wp_enqueue_scripts', array($this, 'add_enqueue_for_social_messages'));
    }

    public static function reward_system_social_url() {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                if ((jQuery('#rs_global_social_facebook_url').val()) === '2') {
                    jQuery('#rs_global_social_facebook_url_custom').parent().parent().show();
                } else {
                    jQuery('#rs_global_social_facebook_url_custom').parent().parent().hide();
                }
                if ((jQuery('#rs_global_social_twitter_url').val()) === '2') {
                    jQuery('#rs_global_social_twitter_url_custom').parent().parent().show();
                } else {
                    jQuery('#rs_global_social_twitter_url_custom').parent().parent().hide();
                }
                if ((jQuery('#rs_global_social_google_url').val()) === '2') {
                    jQuery('#rs_global_social_google_url_custom').parent().parent().show();
                } else {
                    jQuery('#rs_global_social_google_url_custom').parent().parent().hide();
                }
                jQuery('#rs_global_social_facebook_url').change(function () {
                    jQuery('#rs_global_social_facebook_url_custom').parent().parent().toggle();
                });
                jQuery('#rs_global_social_twitter_url').change(function () {
                    jQuery('#rs_global_social_twitter_url_custom').parent().parent().toggle();
                });
                jQuery('#rs_global_social_google_url').change(function () {
                    jQuery('#rs_global_social_google_url_custom').parent().parent().toggle();
                });
            });
        </script>
        <?php
    }

    public static function social_rewards_global_settings_script() {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                if (jQuery('#rs_global_social_enable_disable_reward').val() == '2') {
                    jQuery('.show_if_social_tab_enable').parent().parent().hide();
                } else {
                    jQuery('.show_if_social_tab_enable').parent().parent().show();

                    /*Facebook Reward Type Validation in jQuery Start*/
                    if ((jQuery('#rs_global_social_reward_type_facebook').val()) === '1') {
                        jQuery('#rs_global_social_facebook_reward_points').parent().parent().show();
                        jQuery('#rs_global_social_facebook_reward_percent').parent().parent().hide();
                    } else {
                        jQuery('#rs_global_social_facebook_reward_points').parent().parent().hide();
                        jQuery('#rs_global_social_facebook_reward_percent').parent().parent().show();
                    }
                    jQuery('#rs_global_social_reward_type_facebook').change(function () {
                        if ((jQuery(this).val()) === '1') {
                            jQuery('#rs_global_social_facebook_reward_points').parent().parent().show();
                            jQuery('#rs_global_social_facebook_reward_percent').parent().parent().hide();
                        } else {
                            jQuery('#rs_global_social_facebook_reward_points').parent().parent().hide();
                            jQuery('#rs_global_social_facebook_reward_percent').parent().parent().show();
                        }
                    });
                    if ((jQuery('#rs_global_social_reward_type_facebook_share').val()) === '1') {
                        jQuery('#rs_global_social_facebook_share_reward_points').parent().parent().show();
                        jQuery('#rs_global_social_facebook_share_reward_percent').parent().parent().hide();
                    } else {
                        jQuery('#rs_global_social_facebook_share_reward_points').parent().parent().hide();
                        jQuery('#rs_global_social_facebook_share_reward_percent').parent().parent().show();
                    }
                    jQuery('#rs_global_social_reward_type_facebook_share').change(function () {
                        if ((jQuery(this).val()) === '1') {
                            jQuery('#rs_global_social_facebook_share_reward_points').parent().parent().show();
                            jQuery('#rs_global_social_facebook_share_reward_percent').parent().parent().hide();
                        } else {
                            jQuery('#rs_global_social_facebook_share_reward_points').parent().parent().hide();
                            jQuery('#rs_global_social_facebook_share_reward_percent').parent().parent().show();
                        }
                    });
                    /*Facebook Reward Type Validation in jQuery Ends*/

                    /*Twitter Reward Type Validation in jQuery Start*/
                    if ((jQuery('#rs_global_social_reward_type_twitter').val()) === '1') {
                        jQuery('#rs_global_social_twitter_reward_points').parent().parent().show();
                        jQuery('#rs_global_social_twitter_reward_percent').parent().parent().hide();
                    } else {
                        jQuery('#rs_global_social_twitter_reward_points').parent().parent().hide();
                        jQuery('#rs_global_social_twitter_reward_percent').parent().parent().show();
                    }
                    jQuery('#rs_global_social_reward_type_twitter').change(function () {
                        if ((jQuery(this).val()) === '1') {
                            jQuery('#rs_global_social_twitter_reward_points').parent().parent().show();
                            jQuery('#rs_global_social_twitter_reward_percent').parent().parent().hide();
                        } else {
                            jQuery('#rs_global_social_twitter_reward_points').parent().parent().hide();
                            jQuery('#rs_global_social_twitter_reward_percent').parent().parent().show();
                        }
                    });
                    /*Twitter Reward Type Validation in jQuery Ends*/

                    /*Google Reward Type Validation in jQuery Start*/
                    if ((jQuery('#rs_global_social_reward_type_google').val()) === '1') {
                        jQuery('#rs_global_social_google_reward_points').parent().parent().show();
                        jQuery('#rs_global_social_google_reward_percent').parent().parent().hide();
                    } else {
                        jQuery('#rs_global_social_google_reward_points').parent().parent().hide();
                        jQuery('#rs_global_social_google_reward_percent').parent().parent().show();
                    }
                    jQuery('#rs_global_social_reward_type_google').change(function () {
                        if ((jQuery(this).val()) === '1') {
                            jQuery('#rs_global_social_google_reward_points').parent().parent().show();
                            jQuery('#rs_global_social_google_reward_percent').parent().parent().hide();
                        } else {
                            jQuery('#rs_global_social_google_reward_points').parent().parent().hide();
                            jQuery('#rs_global_social_google_reward_percent').parent().parent().show();
                        }
                    });
                    /*Google Reward Type Validation in jQuery Ends*/

                    /*VK Reward Type Validation in jQuery Start*/
                    if ((jQuery('#rs_global_social_reward_type_vk').val()) === '1') {
                        jQuery('#rs_global_social_vk_reward_points').parent().parent().show();
                        jQuery('#rs_global_social_vk_reward_percent').parent().parent().hide();
                    } else {
                        jQuery('#rs_global_social_vk_reward_points').parent().parent().hide();
                        jQuery('#rs_global_social_vk_reward_percent').parent().parent().show();
                    }
                    jQuery('#rs_global_social_reward_type_vk').change(function () {
                        if ((jQuery(this).val()) === '1') {
                            jQuery('#rs_global_social_vk_reward_points').parent().parent().show();
                            jQuery('#rs_global_social_vk_reward_percent').parent().parent().hide();
                        } else {
                            jQuery('#rs_global_social_vk_reward_points').parent().parent().hide();
                            jQuery('#rs_global_social_vk_reward_percent').parent().parent().show();
                        }
                    });
                    /*VK Reward Type Validation in jQuery Ends*/

                    if (jQuery('#product-type').val() === 'variable') {
                        jQuery('._social_rewardsystem_options_facebook_field').css('display', 'none');
                        jQuery('._social_rewardsystem_options_facebook_share_field').css('display', 'none');
                        jQuery('._social_rewardsystem_options_twitter_field').css('display', 'none');
                        jQuery('._social_rewardsystem_options_google_field').css('display', 'none');
                    } else {
                        jQuery('._social_rewardsystem_options_facebook_field').css('display', 'block');
                        jQuery('._social_rewardsystem_options_facebook_share_field').css('display', 'block');
                        jQuery('._social_rewardsystem_options_twitter_field').css('display', 'block');
                        jQuery('._social_rewardsystem_options_google_field').css('display', 'block');
                    }
                    jQuery('#product-type').change(function () {
                        if (jQuery(this).val() === 'variable') {
                            jQuery('._social_rewardsystem_options_facebook_field').css('display', 'none');
                            jQuery('._social_rewardsystem_options_facebook_share_field').css('display', 'none');

                            jQuery('._social_rewardsystem_options_twitter_field').css('display', 'none');
                            jQuery('._social_rewardsystem_options_google_field').css('display', 'none');
                        } else {
                            jQuery('._social_rewardsystem_options_facebook_field').css('display', 'block');
                            jQuery('._social_rewardsystem_options_facebook_share_field').css('display', 'block');
                            jQuery('._social_rewardsystem_options_twitter_field').css('display', 'block');
                            jQuery('._social_rewardsystem_options_google_field').css('display', 'block');
                        }
                    });
                }

                jQuery('#rs_global_social_enable_disable_reward').change(function () {
                    if (jQuery('#rs_global_social_enable_disable_reward').val() == '2') {
                        jQuery('.show_if_social_tab_enable').parent().parent().hide();
                    } else {
                        jQuery('.show_if_social_tab_enable').parent().parent().show();

                        /*Facebook Reward Type Validation in jQuery Start*/
                        if ((jQuery('#rs_global_social_reward_type_facebook').val()) === '1') {
                            jQuery('#rs_global_social_facebook_reward_points').parent().parent().show();
                            jQuery('#rs_global_social_facebook_reward_percent').parent().parent().hide();
                        } else {
                            jQuery('#rs_global_social_facebook_reward_points').parent().parent().hide();
                            jQuery('#rs_global_social_facebook_reward_percent').parent().parent().show();
                        }
                        jQuery('#rs_global_social_reward_type_facebook').change(function () {
                            if ((jQuery(this).val()) === '1') {
                                jQuery('#rs_global_social_facebook_reward_points').parent().parent().show();
                                jQuery('#rs_global_social_facebook_reward_percent').parent().parent().hide();
                            } else {
                                jQuery('#rs_global_social_facebook_reward_points').parent().parent().hide();
                                jQuery('#rs_global_social_facebook_reward_percent').parent().parent().show();
                            }
                        });
                        /*Facebook Reward Type Validation in jQuery Ends*/

                        /*Twitter Reward Type Validation in jQuery Start*/
                        if ((jQuery('#rs_global_social_reward_type_twitter').val()) === '1') {
                            jQuery('#rs_global_social_twitter_reward_points').parent().parent().show();
                            jQuery('#rs_global_social_twitter_reward_percent').parent().parent().hide();
                        } else {
                            jQuery('#rs_global_social_twitter_reward_points').parent().parent().hide();
                            jQuery('#rs_global_social_twitter_reward_percent').parent().parent().show();
                        }
                        jQuery('#rs_global_social_reward_type_twitter').change(function () {
                            if ((jQuery(this).val()) === '1') {
                                jQuery('#rs_global_social_twitter_reward_points').parent().parent().show();
                                jQuery('#rs_global_social_twitter_reward_percent').parent().parent().hide();
                            } else {
                                jQuery('#rs_global_social_twitter_reward_points').parent().parent().hide();
                                jQuery('#rs_global_social_twitter_reward_percent').parent().parent().show();
                            }
                        });
                        /*Twitter Reward Type Validation in jQuery Ends*/

                        /*Google Reward Type Validation in jQuery Start*/
                        if ((jQuery('#rs_global_social_reward_type_google').val()) === '1') {
                            jQuery('#rs_global_social_google_reward_points').parent().parent().show();
                            jQuery('#rs_global_social_google_reward_percent').parent().parent().hide();
                        } else {
                            jQuery('#rs_global_social_google_reward_points').parent().parent().hide();
                            jQuery('#rs_global_social_google_reward_percent').parent().parent().show();
                        }
                        jQuery('#rs_global_social_reward_type_google').change(function () {
                            if ((jQuery(this).val()) === '1') {
                                jQuery('#rs_global_social_google_reward_points').parent().parent().show();
                                jQuery('#rs_global_social_google_reward_percent').parent().parent().hide();
                            } else {
                                jQuery('#rs_global_social_google_reward_points').parent().parent().hide();
                                jQuery('#rs_global_social_google_reward_percent').parent().parent().show();
                            }
                        });
                        /*Google Reward Type Validation in jQuery Ends*/

                        /*VK Reward Type Validation in jQuery Start*/
                        if ((jQuery('#rs_global_social_reward_type_vk').val()) === '1') {
                            jQuery('#rs_global_social_vk_reward_points').parent().parent().show();
                            jQuery('#rs_global_social_vk_reward_percent').parent().parent().hide();
                        } else {
                            jQuery('#rs_global_social_vk_reward_points').parent().parent().hide();
                            jQuery('#rs_global_social_vk_reward_percent').parent().parent().show();
                        }
                        jQuery('#rs_global_social_reward_type_vk').change(function () {
                            if ((jQuery(this).val()) === '1') {
                                jQuery('#rs_global_social_vk_reward_points').parent().parent().show();
                                jQuery('#rs_global_social_vk_reward_percent').parent().parent().hide();
                            } else {
                                jQuery('#rs_global_social_vk_reward_points').parent().parent().hide();
                                jQuery('#rs_global_social_vk_reward_percent').parent().parent().show();
                            }
                        });
                        /*VK Reward Type Validation in jQuery Ends*/

                        if (jQuery('#product-type').val() === 'variable') {
                            jQuery('._social_rewardsystem_options_facebook_field').css('display', 'none');
                            jQuery('._social_rewardsystem_options_facebook_share_field').css('display', 'none');
                            jQuery('._social_rewardsystem_options_twitter_field').css('display', 'none');
                            jQuery('._social_rewardsystem_options_google_field').css('display', 'none');
                        } else {
                            jQuery('._social_rewardsystem_options_facebook_field').css('display', 'block');
                            jQuery('._social_rewardsystem_options_facebook_share_field').css('display', 'block');
                            jQuery('._social_rewardsystem_options_twitter_field').css('display', 'block');
                            jQuery('._social_rewardsystem_options_google_field').css('display', 'block');
                        }
                        jQuery('#product-type').change(function () {
                            if (jQuery(this).val() === 'variable') {
                                jQuery('._social_rewardsystem_options_facebook_field').css('display', 'none');
                                jQuery('._social_rewardsystem_options_facebook_share_field').css('display', 'none');
                                jQuery('._social_rewardsystem_options_twitter_field').css('display', 'none');
                                jQuery('._social_rewardsystem_options_google_field').css('display', 'none');
                            } else {
                                jQuery('._social_rewardsystem_options_facebook_field').css('display', 'block');
                                jQuery('._social_rewardsystem_options_facebook_share_field').css('display', 'block');
                                jQuery('._social_rewardsystem_options_twitter_field').css('display', 'block');
                                jQuery('._social_rewardsystem_options_google_field').css('display', 'block');
                            }
                        });
                    }
                });

            });
        </script>
        <?php
    }

    public static function rs_color_picker() {
        wp_register_script('wp_jscolor_rewards', plugins_url('rewardsystem/jscolor/jscolor.js'));
        wp_enqueue_script('wp_jscolor_rewards');
    }

    public static function rs_validation_of_input_field_in_social_reward() {
        ?>

        <script type="text/javascript">
            jQuery(function () {
                jQuery('body').on('blur', '#rs_global_social_facebook_reward_points[type=text],\n\
                                           #rs_global_social_facebook_reward_percent[type=text],\n\
                                           #rs_global_social_twitter_reward_points[type=text],\n\
                                           #rs_global_social_twitter_reward_percent[type=text],\n\
                                           #rs_global_social_google_reward_points[type=text],\n\
                                           #rs_global_social_google_reward_percent[type=text],\n\
                                           #rs_global_social_vk_reward_points[type=text],\n\
                                           #rs_global_social_vk_reward_percent[type=text],\n\
                                           #rs_global_social_facebook_reward_points_individual[type=text],\n\
                                           #rs_global_social_facebook_reward_percent_individual[type=text],\n\
                                           #rs_global_social_twitter_reward_points_individual[type=text],\n\
                                           #rs_global_social_twitter_reward_percent_individual[type=text],\n\
                                           #rs_global_social_google_reward_points_individual[type=text],\n\
                                           #rs_global_social_google_reward_percent_individual[type=text],\n\
                                           #rs_global_social_vk_reward_points_individual[type=text],\n\
                                           #rs_global_social_vk_reward_percent_individual[type=text]', function () {
                    jQuery('.wc_error_tip').fadeOut('100', function () {
                        jQuery(this).remove();
                    });

                    return this;
                });

                jQuery('body').on('keyup change', '#rs_global_social_facebook_reward_points[type=text],\n\
                                           #rs_global_social_facebook_reward_percent[type=text],\n\
                                           #rs_global_social_twitter_reward_points[type=text],\n\
                                           #rs_global_social_twitter_reward_percent[type=text],\n\
                                           #rs_global_social_google_reward_points[type=text],\n\
                                           #rs_global_social_google_reward_percent[type=text],\n\
                                           #rs_global_social_vk_reward_points[type=text],\n\
                                           #rs_global_social_vk_reward_percent[type=text],\n\
                                           #rs_global_social_facebook_reward_points_individual[type=text],\n\
                                           #rs_global_social_facebook_reward_percent_individual[type=text],\n\
                                           #rs_global_social_twitter_reward_points_individual[type=text],\n\
                                           #rs_global_social_twitter_reward_percent_individual[type=text],\n\
                                           #rs_global_social_google_reward_points_individual[type=text],\n\
                                           #rs_global_social_google_reward_percent_individual[type=text],\n\
                                           #rs_global_social_vk_reward_points_individual[type=text],\n\
                                           #rs_global_social_vk_reward_percent_individual[type=text]', function () {
                    var value = jQuery(this).val();
                    var regex = new RegExp("[^\+0-9\%.\\" + woocommerce_admin.mon_decimal_point + "]+", "gi");
                    var newvalue = value.replace(regex, '');

                    if (value !== newvalue) {
                        jQuery(this).val(newvalue);
                        if (jQuery(this).parent().find('.wc_error_tip').size() == 0) {
                            var offset = jQuery(this).position();
                            jQuery(this).after('<div class="wc_error_tip">' + woocommerce_admin.i18n_mon_decimal_error + " Negative Values are not allowed" + '</div>');
                            jQuery('.wc_error_tip')
                                    .css('left', offset.left + jQuery(this).width() - (jQuery(this).width() / 2) - (jQuery('.wc_error_tip').width() / 2))
                                    .css('top', offset.top + jQuery(this).height())
                                    .fadeIn('100');
                        }
                    }



                    return this;
                });



                jQuery("body").click(function () {
                    jQuery('.wc_error_tip').fadeOut('100', function () {
                        jQuery(this).remove();
                    });

                });
            });
        </script>
        <?php
    }

    public static function reward_system_social_likes_buttons() {
        global $woocommerce;
        global $post;
        $post_title = $post->post_title;
        $post_description = $post->post_content;
        $product_url = $post->guid;
        $product_caption = $post->post_excerpt;
        $gallery = get_post_gallery_images($post);
        $plugins_url = plugins_url();
        $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID));
        if (is_user_logged_in()) {
            if (get_post_meta($post->ID, '_socialrewardsystemcheckboxvalue', true) == 'yes') {
                if (get_option('rs_facebook_application_id') != '') {
                    ?>
                    <div id="fb-root"></div>
                    <script type="text/javascript">
                        window.fbAsyncInit = function () {
                            FB.init({
                                appId: "<?php echo get_option('rs_facebook_application_id'); ?>",
                                xfbml: true,
                                version: 'v2.6'
                            });
                        };
                        console.log('loaded script . . . . . ');
                        (function (d, s, id) {
                            var js, fjs = d.getElementsByTagName(s)[0];
                            if (d.getElementById(id)) {
                                return;
                            }
                            js = d.createElement(s);
                            js.id = id;
                    <?php if (get_option('rs_language_selection_for_button') == 1) { ?>
                                js.src = "https://connect.facebook.net/en_US/sdk.js";
                        <?php
                    } else {
                        if (get_option('WPLANG') == '') {
                            ?>
                                    js.src = "https://connect.facebook.net/en_US/sdk.js";
                        <?php } else { ?>
                                    js.src = "https://connect.facebook.net/<?php echo get_option('WPLANG'); ?>/sdk.js";
                            <?php
                        }
                    }
                    ?>
                            fjs.parentNode.insertBefore(js, fjs);
                        }(document, 'script', 'facebook-jssdk'));
                        console.log('script loaded');


                    </script>




                <?php } ?>
                <script>!function (d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
                        if (!d.getElementById(id)) {
                            js = d.createElement(s);
                            js.id = id;
                            js.src = p + '://platform.twitter.com/widgets.js';
                            fjs.parentNode.insertBefore(js, fjs);
                        }
                    }(document, 'script', 'twitter-wjs');</script>

                <script>
                    var originalCallback = function (o) {
                        console.log(o);
                        console.log('original callback - ' + o.state);
                        var state = o.state;
                <?php
                $google_success_mesage = do_shortcode(get_option('rs_succcess_message_for_google_share'));
                $google_unsuccess_mesage = get_option('rs_unsucccess_message_for_google_unshare');
                ?>
                        var dataparam = ({
                            action: 'rssocialgooglecallback',
                            state: state,
                            postid: '<?php echo $post->ID; ?>',
                            currentuserid: '<?php echo get_current_user_id(); ?>',
                        });
                        jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                                function (response) {
                                    if (response == "You already Shared this post on Goole+1Ajax Call Successfully Triggered") {
                                        jQuery('<p><?php echo addslashes($google_unsuccess_mesage); ?></p>').appendTo('.social_promotion_success_message').fadeIn().delay(5000).fadeOut();
                                    } else {
                                        jQuery('<p><?php echo addslashes($google_success_mesage); ?></p>').appendTo('.social_promotion_success_message').fadeIn().delay(5000).fadeOut();
                                    }
                                });
                        return false;
                    };</script>
                <script>
                    window.___gcfg = {
                        lang: '<?php echo get_option('WPLANG') == '' ? 'en_US' : get_option('WPLANG'); ?>',
                        parsetags: 'onload'
                    }
                </script>
                <style type="text/css">
                    .gc-bubbleDefault, .pls-container {
                        display: none;
                    }
                </style>
                <script type="text/javascript" src="https://apis.google.com/js/plusone.js">

                </script>
                <?php
                if (get_option('rs_vk_application_id') != '') {
                    ?>
                    <script type="text/javascript" src="//vk.com/js/api/openapi.js?116"></script>

                    <script type="text/javascript">
                    VK.init({
                        apiId: "<?php echo get_option('rs_vk_application_id') ?>",
                        onlyWidgets: true

                    });
                    </script>

                    <script type="text/javascript">
                        jQuery(window).load(function () {
                            VK.Widgets.Like("vk_like", {type: "button"});

                            VK.Observer.subscribe("widgets.like.liked", function f() {

                                var vklikecallback = ({
                                    action: 'rsvkcallback',
                                    state: 'on',
                                    postid: '<?php echo $post->ID; ?>',
                                    currentuserid: '<?php echo get_current_user_id(); ?>',
                                });
                    <?php
                    $vk_success_mesage = do_shortcode(get_option('rs_succcess_message_for_vk'));
                    $vk_unlike_mesage = get_option('rs_unsucccess_message_for_vk');
                    ?>


                                jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", vklikecallback,
                                        function (response) {
                                            if (response == "You have already liked this post on VK.ComAjax Call Successfully Triggered") {
                                                jQuery('<p><?php echo addslashes($vk_unlike_mesage); ?></p>').appendTo('.social_promotion_success_message').fadeIn().delay(5000).fadeOut();
                                            } else {
                                                jQuery('<p><?php echo addslashes($vk_success_mesage); ?></p>').appendTo('.social_promotion_success_message').fadeIn().delay(5000).fadeOut();
                                            }
                                        });
                                return false;

                            });

                            VK.Observer.subscribe("widgets.like.unliked", function f1() {

                                var vkunlikecallback = ({
                                    action: 'rsvkcallback',
                                    state: 'off',
                                    postid: '<?php echo $post->ID; ?>',
                                    currentuserid: '<?php echo get_current_user_id(); ?>',
                                });
                                jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", vkunlikecallback,
                                        function (response) {

                                        });
                                return false;
                            });
                        });

                    </script>
                    <style type="text/css">
                        .vk-like{
                            width:88px !important;
                        }
                    </style>
                    <?php
                }
                ?>
                <style type="text/css">
                    .fb_iframe_widget {
                        display:inline-flex !important;
                    }
                    .twitter-share-button {
                        width:88px !important;
                    }

                </style>
                <?php
                $enablerewards = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($post->ID, '_socialrewardsystemcheckboxvalue');
                if ($enablerewards == 'yes') {
                    ?>
                    <style type="text/css">
                    <?php echo get_option('rs_social_custom_css'); ?>
                    </style>
                    <table class="rs_social_sharing_buttons" style="display:<?php echo get_option('rs_social_button_position_troubleshoot'); ?>">
                        <tr>

                            <?php if (get_option('rs_global_show_hide_facebook_like_button') == '1') { ?>
                                <td> <div class="fb-like" data-href="<?php echo get_option('rs_global_social_facebook_url') == '1' ? get_permalink() : get_option('rs_global_social_facebook_url_custom'); ?>" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div></td>

                            <?php } ?>
                            <?php if (get_option('rs_global_show_hide_facebook_share_button') == '1') { ?>
                                <td>    <div class="share_wrapper1">
                                        <img class='fb_share_img' src="<?php echo $plugins_url ?>/rewardsystem/img/icon1.png"> <span class="label">Share </span>
                                    </div> </td>

                            <?php } ?>
                            <?php if (get_option('rs_global_show_hide_twitter_tweet_button') == '1') { ?>
                                <td><div class="rstwitter-button-msg"><a href="https://twitter.com/share" class="twitter-share-button" id="twitter-share-button" data-url="<?php echo get_option('rs_global_social_twitter_url') == '1' ? get_permalink() : get_option('rs_global_social_twitter_url_custom'); ?>"></a></div></td>
                            <?php } ?>
                            <?php if (get_option('rs_global_show_hide_vk_button') == '1') { ?>
                                <td><div id="vk_like" class='vk-like' ></div></td>
                            <?php } ?>
                            <?php if (get_option('rs_global_show_hide_google_plus_button') == '1') { ?>
                                <td>
                                    <div id="google-plus-one"><g:plusone annotation="bubble" callback="originalCallback" class="google-plus-one" href='<?php echo get_option('rs_global_social_google_url') == '1' ? get_permalink() : get_option('rs_global_social_google_url_custom'); ?>'></g:plusone></div>
                                </td>
                            <?php } ?>
                            <td>

                            </td>
                        </tr>
                    </table>

                    <?php if (get_option('rs_global_show_hide_facebook_share_button') == '1') { ?>
                        <style>
                            .share_wrapper1{
                                margin-top: -12px;    
                                background-color:#3b5998;
                                /*padding:2px;*/
                                color:#fff;
                                cursor:pointer;
                                font-size:12px;
                                font-weight:bold;
                                border: 1px solid transparent;
                                border-radius: 2px ;
                                width:59px;
                                height:23px;
                            }
                            .fb_share_img{
                                margin-top: -3px; 
                                margin-left: 3px;
                                margin-right: 3px;
                            }
                        </style>
                    <?php } ?>
                    <div class="social_promotion_success_message"></div>
                <?php } ?>
                <?php
                if (get_option('rs_global_show_hide_facebook_share_button') == '1') {
                    if (get_option('rs_facebook_application_id') != '') {
                        ?>

                        <script type='text/javascript'>
                            function postToFeed() {
                                var product_name = "<?php echo $post_title; ?>";
                                var description = "<?php echo $post_title; ?>";
                                var share_image = "<?php echo $image[0]; ?>";
                                var share_url = "<?php echo get_permalink(); ?>";
                                var share_capt = "<?php echo $product_caption; ?>";


                                var obj = {
                                    method: 'feed',
                                    name: product_name,
                                    link: share_url,
                                    picture: share_image,
                                    caption: share_capt,
                                    description: description

                                };
                                function callback(response) {
                                    if (response != null) {
                                        alert('sucessfully posted');
                                        var dataparam = ({
                                            action: 'rssocialfacebooksharecallback',
                                            state: 'on',
                                            postid: '<?php echo $post->ID; ?>',
                                            currentuserid: '<?php echo get_current_user_id(); ?>',
                                        });
                                        jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam, function (response) {
                                        });

                                    } else {
                                        alert('cancel');
                                    }

                                }
                                FB.ui(obj, callback);
                            }
                            var fbShareBtn = document.querySelector('.share_wrapper1');
                            fbShareBtn.addEventListener('click', function (e) {
                                e.preventDefault();
                                postToFeed();

                                return false;
                            });
                        </script>     
                        <?php
                    }
                }
                ?>
                <script type='text/javascript'>
                    jQuery(window).load(function () {
                        /* This is for facebook which is been like or not */
                <?php if (get_option('rs_facebook_application_id') != '') { ?>
                            var page_like_callback = function (url, html_element) {
                    <?php
                    $facebook_success_mesage = do_shortcode(get_option('rs_succcess_message_for_facebook_like'));
                    $facebook_unsuccess_mesage = get_option('rs_unsucccess_message_for_facebook_unlike');
                    ?>

                                console.log("page_like");
                                console.log(url);
                                console.log(html_element);
                                var dataparam = ({
                                    action: 'rssocialfacebookcallback',
                                    state: 'on',
                                    postid: '<?php echo $post->ID; ?>',
                                    currentuserid: '<?php echo get_current_user_id(); ?>',
                                });
                                jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                                        function (response) {

                                            if (response == "You already liked this postAjax Call Successfully Triggered") {
                                                jQuery('<p><?php echo addslashes($facebook_unsuccess_mesage); ?></p>').appendTo('.social_promotion_success_message').fadeIn().delay(5000).fadeOut();
                                            } else {
                                                jQuery('<p><?php echo addslashes($facebook_success_mesage); ?></p>').appendTo('.social_promotion_success_message').fadeIn().delay(5000).fadeOut();
                                            }

                                        });
                                return false;
                            }

                            var page_unlike_callback = function (url, html_element) {
                    <?php
                    $facebook_success_mesage = do_shortcode(get_option('rs_succcess_message_for_facebook_like'));
                    $facebook_unsuccess_mesage = get_option('rs_unsucccess_message_for_facebook_unlike');
                    ?>
                                console.log('page_unlike');
                                console.log(url);
                                console.log(html_element);
                                var dataparam = ({
                                    action: 'rssocialfacebookcallback',
                                    state: 'off',
                                    postid: '<?php echo $post->ID; ?>',
                                    currentuserid: '<?php echo get_current_user_id(); ?>'
                                });
                                jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                                        function (response) {

                                            if (response == "You already liked this postAjax Call Successfully Triggered") {
                                                jQuery('<p><?php echo addslashes($facebook_unsuccess_mesage); ?></p>').appendTo('.social_promotion_success_message').fadeIn().delay(5000).fadeOut();
                                            } else {
                                                jQuery('<p><?php echo addslashes($facebook_success_mesage); ?></p>').appendTo('.social_promotion_success_message').fadeIn().delay(5000).fadeOut();
                                            }
                                        });
                                return false;
                            }
                            // Detect Like or Unlike using Event Subscribe of Facebook
                            FB.Event.subscribe('edge.create', page_like_callback);
                            FB.Event.subscribe('edge.remove', page_unlike_callback);
                <?php } ?>
                        // This below code is for Twitter Tweet
                        twttr.events.bind('tweet', function (ev) {
                <?php
                $twitter_success_mesage = do_shortcode(get_option('rs_succcess_message_for_twitter_share'));
                $twitter_unsuccess_mesage = get_option('rs_unsucccess_message_for_twitter_unshare');
                ?>
                            console.log('You Tweet Successfully');
                            var dataparam = ({
                                action: 'rssocialtwittercallback',
                                state: 'on',
                                postid: '<?php echo $post->ID; ?>',
                                currentuserid: '<?php echo get_current_user_id(); ?>',
                            });
                            jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                                    function (response) {

                                        if (response == "You already Tweet this postAjax Call Successfully Triggered") {
                                            jQuery('<p><?php echo addslashes($twitter_unsuccess_mesage); ?></p>').appendTo('.social_promotion_success_message').fadeIn().delay(5000).fadeOut();
                                        } else {
                                            jQuery('<p><?php echo addslashes($twitter_success_mesage); ?></p>').appendTo('.social_promotion_success_message').fadeIn().delay(5000).fadeOut();
                                        }

                                    });
                            return false;
                        });
                    });</script>
                <?php
            }
        }
    }

    public static function add_fb_style_hide_comment_box() {
        global $post;
        if (get_post_meta(@$post->ID, '_socialrewardsystemcheckboxvalue', true) == 'yes') {
            ?>
            <style type="text/css">
                .fb_edge_widget_with_comment span.fb_edge_comment_widget iframe.fb_ltr {
                    display: none !important;
                }
                .fb-like{
                    height: 20px !important;
                    overflow: hidden !important;
                }
                .tipsy-inner {
                    background-color:#<?php echo get_option('rs_social_tooltip_bg_color'); ?>;

                    color:#<?php echo get_option('rs_social_tooltip_text_color'); ?>;
                }
                .tipsy-arrow-s { border-top-color: #<?php echo get_option('rs_social_tooltip_bg_color'); ?>; }
            </style>




            <?php if (get_option('rs_reward_point_enable_tipsy_social_rewards') == '1') { ?>
                <?php if (get_option('rs_global_show_hide_social_tooltip_for_facebook') == '1') { ?>
                    <script type="text/javascript">
                        jQuery(window).load(function () {

                            //var originaltitle = jQuery('.twitter-share-button').removeAttr('title');

                    <?php
                    $userid = get_current_user_id();
                    $banning_type = FPRewardSystem::check_banning_type($userid);
                    if ($banning_type != 'earningonly' && $banning_type != 'both') {
                        ?>
                        <?php
                        $fb_info = get_user_meta($userid, '_rsfacebooklikes', true);
                        $postid = $post->ID;
                        if (!in_array($postid, (array) $fb_info)) {
                            ?>
                                    jQuery('.fb-like').tipsy({gravity: 's', live: 'true', fallback: '<?php echo do_shortcode(get_option('rs_social_message_for_facebook')); ?>'});
                        <?php } ?>
                        <?php
                    }
                    ?>

                        });
                    </script>

                    <?php
                }
            }
            ?>
            <?php if (get_option('rs_reward_point_enable_tipsy_social_rewards') == '1') { ?>
                <?php if (get_option('rs_global_show_hide_social_tooltip_for_facebook_share') == '1') { ?>
                    <script type="text/javascript">
                        jQuery(window).load(function () {



                    <?php
                    $userid = get_current_user_id();
                    $banning_type = FPRewardSystem::check_banning_type($userid);
                    if ($banning_type != 'earningonly' && $banning_type != 'both') {
                        ?>
                        <?php
                        $fb_info = get_user_meta($userid, '_rsfacebookshare', true);
                        $postid = $post->ID;
                        if (!in_array($postid, (array) $fb_info)) {
                            ?>
                                    jQuery('.share_wrapper1').tipsy({gravity: 's', live: 'true', fallback: '<?php echo do_shortcode(get_option('rs_social_message_for_facebook_share')); ?>'});
                        <?php } ?>
                        <?php
                    }
                    ?>

                        });
                    </script>

                    <?php
                }
            }
            ?>




            <?php if (get_option('rs_reward_point_enable_tipsy_social_rewards') == '1') { ?>
                <?php if (get_option('rs_global_show_hide_social_tooltip_for_twitter') == '1') { ?>
                    <script type="text/javascript">
                        jQuery(window).load(function () {

                            //var originaltitle = jQuery('.twitter-share-button').removeAttr('title');

                    <?php
                    $userid = get_current_user_id();
                    $banning_type = FPRewardSystem::check_banning_type($userid);
                    if ($banning_type != 'earningonly' && $banning_type != 'both') {
                        ?>
                        <?php
                        $twitter_info = get_user_meta($userid, '_rstwittertweet', true);
                        $postid = $post->ID;
                        if (!in_array($postid, (array) $twitter_info)) {
                            ?>
                                    jQuery('.rstwitter-button-msg').tipsy({gravity: 's', live: 'true', fallback: '<?php echo do_shortcode(get_option('rs_social_message_for_twitter')); ?>'});
                        <?php } ?>
                        <?php
                    }
                    ?>

                        });
                    </script>

                    <?php
                }
            }
            ?>



            <?php if (get_option('rs_reward_point_enable_tipsy_social_rewards') == '1') { ?>
                <?php if (get_option('rs_global_show_hide_social_tooltip_for_google') == '1') { ?>
                    <script type="text/javascript">
                        jQuery(window).load(function () {

                            //var originaltitle = jQuery('.twitter-share-button').removeAttr('title');

                    <?php
                    $userid = get_current_user_id();
                    $banning_type = FPRewardSystem::check_banning_type($userid);
                    if ($banning_type != 'earningonly' && $banning_type != 'both') {
                        ?>
                        <?php
                        $google_info = get_user_meta($userid, '_rsgoogleshares', true);
                        $postid = $post->ID;
                        if (!in_array($postid, (array) $google_info)) {
                            ?>
                                    jQuery('#google-plus-one').tipsy({gravity: 's', live: 'true', fallback: '<?php echo do_shortcode(get_option('rs_social_message_for_google_plus')); ?>'});
                        <?php } ?>
                        <?php
                    }
                    ?>

                        });
                    </script>

                    <?php
                }
            }
            ?>




            <?php if (get_option('rs_reward_point_enable_tipsy_social_rewards') == '1') { ?>
                <?php if (get_option('rs_global_show_hide_social_tooltip_for_vk') == '1') { ?>
                    <script type="text/javascript">
                        jQuery(window).load(function () {

                            //var originaltitle = jQuery('.twitter-share-button').removeAttr('title');

                    <?php
                    $userid = get_current_user_id();
                    $banning_type = FPRewardSystem::check_banning_type($userid);
                    if ($banning_type != 'earningonly' && $banning_type != 'both') {
                        ?>
                        <?php
                        $vk_info = get_user_meta($userid, '_rsvklike', true);
                        $postid = $post->ID;
                        if (!in_array($postid, (array) $vk_info)) {
                            ?>
                                    jQuery('.vk-like').tipsy({gravity: 's', live: 'true', fallback: '<?php echo do_shortcode(get_option('rs_social_message_for_vk')); ?>'});
                        <?php } ?>
                        <?php
                    }
                    ?>

                        });
                    </script>

                    <?php
                }
            }
            ?>
            <?php
        }
    }

    /* Function to insert fb like reward points */

    public static function update_reward_points_for_facebook_share() {
        $userid = get_current_user_id();
        $banning_type = FPRewardSystem::check_banning_type($userid);
        if ($banning_type != 'earningonly' && $banning_type != 'both') {


            if (isset($_POST['state']) && ($_POST['postid']) && ($_POST['currentuserid'])) {
                if (get_option('rs_set_price_to_calculate_rewardpoints_by_percentage') == '1') {
                    $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($_POST['postid'], '_regular_price');
                    if ($getregularprice == '') {
                        $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($_POST['postid'], '_price');
                    }
                } else {
                    $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($_POST['postid'], '_price');
                    if ($getregularprice == '') {
                        $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($_POST['postid'], '_regular_price');
                    }
                }
                $postid = $_POST['postid'];
                $currentuserid = $_POST['currentuserid'];
                $getarrayids[] = $_POST['postid'];
                $oldoption = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($_POST['currentuserid'], '_rsfacebookshare');
                if (!empty($oldoption)) {
                    if (!in_array($_POST['postid'], $oldoption)) {
                        $mergedata = array_merge((array) $oldoption, $getarrayids);
                        RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($_POST['currentuserid'], '_rsfacebookshare', $mergedata);
                        if ($_POST['state'] == 'on') {
                            $checklevel = self::checklevel_for_facebook_share($postid);
                            $orderid = '0';
                            $totalearnedpoints = '0';
                            $totalredeempoints = '0';
                            $pointsredeemed = '0';
                            $reasonindetail = '';
                            self::rs_insert_facebook_share_points($pointsredeemed, $getregularprice, $postid, $checklevel, $currentuserid, $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        }
                    } else {
                        _e('You already share this post', 'rewardsystem');
                    }
                } else {
                    update_user_meta($_POST['currentuserid'], '_rsfacebookshare', $getarrayids);
                    if ($_POST['state'] == 'on') {
                        $checklevel = self::checklevel_for_facebook_share($postid);
                        $orderid = '0';
                        $totalearnedpoints = '0';
                        $totalredeempoints = '0';
                        $pointsredeemed = '0';
                        $reasonindetail = '';
                        self::rs_insert_facebook_share_points($pointsredeemed, $getregularprice, $postid, $checklevel, $currentuserid, $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                    }
                }


                echo "Ajax Call Successfully Triggered";
            }

            exit();
        }
    }

    public static function update_reward_points_for_facebook_like() {
        $userid = get_current_user_id();
        $banning_type = FPRewardSystem::check_banning_type($userid);
        if ($banning_type != 'earningonly' && $banning_type != 'both') {


            if (isset($_POST['state']) && ($_POST['postid']) && ($_POST['currentuserid'])) {
                if (get_option('rs_set_price_to_calculate_rewardpoints_by_percentage') == '1') {
                    $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($_POST['postid'], '_regular_price');
                    if ($getregularprice == '') {
                        $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($_POST['postid'], '_price');
                    }
                } else {
                    $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($_POST['postid'], '_price');
                    if ($getregularprice == '') {
                        $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($_POST['postid'], '_regular_price');
                    }
                }
                $postid = $_POST['postid'];
                $currentuserid = $_POST['currentuserid'];
                $getarrayids[] = $_POST['postid'];
                $oldoption = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($_POST['currentuserid'], '_rsfacebooklikes');
                if (!empty($oldoption)) {
                    if (!in_array($_POST['postid'], $oldoption)) {
                        $mergedata = array_merge((array) $oldoption, $getarrayids);
                        RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($_POST['currentuserid'], '_rsfacebooklikes', $mergedata);
                        if ($_POST['state'] == 'on') {
                            $checklevel = self::checklevel_for_facebook_like($postid);
                            $orderid = '0';
                            $totalearnedpoints = '0';
                            $totalredeempoints = '0';
                            $pointsredeemed = '0';
                            $reasonindetail = '';
                            self::rs_insert_facebook_like_points($pointsredeemed, $getregularprice, $postid, $checklevel, $currentuserid, $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        }
                    } else {
                        _e('You already liked this post', 'rewardsystem');
                    }
                } else {
                    update_user_meta($_POST['currentuserid'], '_rsfacebooklikes', $getarrayids);
                    if ($_POST['state'] == 'on') {
                        $checklevel = self::checklevel_for_facebook_like($postid);
                        $orderid = '0';
                        $totalearnedpoints = '0';
                        $totalredeempoints = '0';
                        $pointsredeemed = '0';
                        $reasonindetail = '';
                        self::rs_insert_facebook_like_points($pointsredeemed, $getregularprice, $postid, $checklevel, $currentuserid, $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                    }
                }

                if ($_POST['state'] == 'off') {
                    $getarrayunlikeids[] = $_POST['postid'];
                    $oldunlikeoption = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($_POST['currentuserid'], '_rsfacebookunlikes');
                    if (!empty($oldunlikeoption)) {
                        if (!in_array($_POST['postid'], $oldunlikeoption)) {
                            $mergedunlikedata = array_merge((array) $oldunlikeoption, $getarrayunlikeids);
                            RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($_POST['currentuserid'], '_rsfacebookunlikes', $mergedunlikedata);
                            $checklevel = self::checklevel_for_facebook_like($postid);
                            $orderid = '0';
                            $totalearnedpoints = '0';
                            $totalredeempoints = '0';
                            $pointsredeemed = '0';
                            $reasonindetail = '';
                            self::rs_insert_facebook_like_revised_points($pointsredeemed, $getregularprice, $postid, $checklevel, $currentuserid, $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        }
                    } else {
                        RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($_POST['currentuserid'], '_rsfacebookunlikes', $getarrayunlikeids);
                        $checklevel = self::checklevel_for_facebook_like($postid);
                        $orderid = '0';
                        $totalearnedpoints = '0';
                        $totalredeempoints = '0';
                        $pointsredeemed = '0';
                        $reasonindetail = '';
                        self::rs_insert_facebook_like_revised_points($pointsredeemed, $getregularprice, $postid, $checklevel, $currentuserid, $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                    }
                }

                echo "Ajax Call Successfully Triggered";
            }
            do_action('fp_reward_point_for_facebook_like');
            exit();
        }
    }

    /* Function to check the level of the points from where it is awraded. */

    public static function checklevel_for_facebook_like($postid) {

        //Product Level
        $enablerewards = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_socialrewardsystemcheckboxvalue');
        $gettype = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_social_rewardsystem_options_facebook');
        $getpoints = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_socialrewardsystempoints_facebook');
        $getpercent = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_socialrewardsystempercent_facebook');

        //Category Level
        $categorylist = wp_get_post_terms($postid, 'product_cat');
        $getcount = count($categorylist);
        $term = get_the_terms($postid, 'product_cat');
        if (is_array($term)) {
            foreach ($term as $terms) {
                $termid = $terms->term_id;

                $categorylevel = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'enable_social_reward_system_category');
                $categorylevelrewardtype = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_facebook_enable_rs_rule');
                $categorylevelrewardpoints = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_facebook_rs_category_points');
                $categorylevelrewardpercents = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_facebook_rs_category_percent');
            }
        }
        //Global Level
        $global_enable = get_option('rs_global_social_enable_disable_reward');
        $global_reward_type = get_option('rs_global_social_reward_type_facebook');
        $global_reward_points = get_option('rs_global_social_facebook_reward_points');
        $global_reward_percent = get_option('rs_global_social_facebook_reward_percent');

        if ($enablerewards == 'yes') {
            if ($gettype == '1') {
                if ($getpoints != '') {
                    return '1';
                } else {
                    if ($getcount >= '1') {
                        if (($categorylevel == 'yes') || ($categorylevel != '')) {
                            if ($categorylevelrewardtype == '1') {
                                if ($categorylevelrewardpoints != '') {
                                    return '2';
                                } else {
                                    if ($global_enable == '1') {
                                        if ($global_reward_type == '1') {
                                            if ($global_reward_points != '') {
                                                return '2';
                                            }
                                        } else {
                                            if ($global_reward_type == '2') {
                                                if ($global_reward_percent != '') {
                                                    return '2';
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($categorylevelrewardtype == '2') {
                                    if ($categorylevelrewardpercents != '') {
                                        return '2';
                                    } else {
                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                if ($global_reward_points != '') {
                                                    return '2';
                                                }
                                            } else {
                                                if ($global_reward_type == '2') {
                                                    if ($global_reward_percent != '') {
                                                        return '2';
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($global_enable == '1') {
                                if ($global_reward_type == '1') {
                                    if ($global_reward_points != '') {
                                        return '3';
                                    }
                                } else {
                                    if ($global_reward_type == '2') {
                                        if ($global_reward_percent != '') {
                                            return '3';
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        if ($global_enable == '1') {
                            if ($global_reward_type == '1') {
                                if ($global_reward_points != '') {
                                    return '3';
                                }
                            } else {
                                if ($global_reward_type == '2') {
                                    if ($global_reward_percent != '') {
                                        return '3';
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                if ($getpercent != '') {
                    return '1';
                } else {
                    if ($getcount >= '1') {
                        if (($categorylevel == 'yes') || ($categorylevel != '')) {
                            if ($categorylevelrewardtype == '1') {
                                if ($categorylevelrewardpoints != '') {
                                    return '2';
                                } else {
                                    if ($global_enable == '1') {
                                        if ($global_reward_type == '1') {
                                            if ($global_reward_points != '') {
                                                return '2';
                                            }
                                        } else {
                                            if ($global_reward_type == '2') {
                                                if ($global_reward_percent != '') {
                                                    return '2';
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($categorylevelrewardtype == '2') {
                                    if ($categorylevelrewardpercents != '') {
                                        return '2';
                                    } else {
                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                if ($global_reward_points != '') {
                                                    return '2';
                                                }
                                            } else {
                                                if ($global_reward_type == '2') {
                                                    if ($global_reward_percent != '') {
                                                        return '2';
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($global_enable == '1') {
                                if ($global_reward_type == '1') {
                                    if ($global_reward_points != '') {
                                        return '3';
                                    }
                                } else {
                                    if ($global_reward_type == '2') {
                                        if ($global_reward_percent != '') {
                                            return '3';
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        if ($global_enable == '1') {
                            if ($global_reward_type == '1') {
                                if ($global_reward_points != '') {
                                    return '3';
                                }
                            } else {
                                if ($global_reward_type == '2') {
                                    if ($global_reward_percent != '') {
                                        return '3';
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public static function checklevel_for_facebook_share($postid) {

        //Product Level
        $enablerewards = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_socialrewardsystemcheckboxvalue');
        $gettype = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_social_rewardsystem_options_facebook_share');
        $getpoints = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_socialrewardsystempoints_facebook_share');
        $getpercent = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_socialrewardsystempercent_facebook_share');

        //Category Level
        $categorylist = wp_get_post_terms($postid, 'product_cat');
        $getcount = count($categorylist);
        $term = get_the_terms($postid, 'product_cat');
        if (is_array($term)) {
            foreach ($term as $terms) {
                $termid = $terms->term_id;

                $categorylevel = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'enable_social_reward_system_category');
                $categorylevelrewardtype = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_facebook_share_enable_rs_rule');
                $categorylevelrewardpoints = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_facebook_share_rs_category_points');
                $categorylevelrewardpercents = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_facebook_share_rs_category_percent');
            }
        }
        //Global Level
        $global_enable = get_option('rs_global_social_enable_disable_reward');
        $global_reward_type = get_option('rs_global_social_reward_type_facebook_share');
        $global_reward_points = get_option('rs_global_social_facebook_share_reward_points');
        $global_reward_percent = get_option('rs_global_social_facebook_share_reward_percent');

        if ($enablerewards == 'yes') {
            if ($gettype == '1') {
                if ($getpoints != '') {
                    return '1';
                } else {
                    if ($getcount >= '1') {
                        if (($categorylevel == 'yes') || ($categorylevel != '')) {
                            if ($categorylevelrewardtype == '1') {
                                if ($categorylevelrewardpoints != '') {
                                    return '2';
                                } else {
                                    if ($global_enable == '1') {
                                        if ($global_reward_type == '1') {
                                            if ($global_reward_points != '') {
                                                return '3';
                                            }
                                        } else {
                                            if ($global_reward_type == '2') {
                                                if ($global_reward_percent != '') {
                                                    return '3';
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($categorylevelrewardtype == '2') {
                                    if ($categorylevelrewardpercents != '') {
                                        return '2';
                                    } else {
                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                if ($global_reward_points != '') {
                                                    return '3';
                                                }
                                            } else {
                                                if ($global_reward_type == '2') {
                                                    if ($global_reward_percent != '') {
                                                        return '3';
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($global_enable == '1') {
                                if ($global_reward_type == '1') {
                                    if ($global_reward_points != '') {
                                        return '3';
                                    }
                                } else {
                                    if ($global_reward_type == '2') {
                                        if ($global_reward_percent != '') {
                                            return '3';
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        if ($global_enable == '1') {
                            if ($global_reward_type == '1') {
                                if ($global_reward_points != '') {
                                    return '3';
                                }
                            } else {
                                if ($global_reward_type == '2') {
                                    if ($global_reward_percent != '') {
                                        return '3';
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                if ($getpercent != '') {
                    return '1';
                } else {
                    if ($getcount >= '1') {
                        if (($categorylevel == 'yes') || ($categorylevel != '')) {
                            if ($categorylevelrewardtype == '1') {
                                if ($categorylevelrewardpoints != '') {
                                    return '2';
                                } else {
                                    if ($global_enable == '1') {
                                        if ($global_reward_type == '1') {
                                            if ($global_reward_points != '') {
                                                return '3';
                                            }
                                        } else {
                                            if ($global_reward_type == '2') {
                                                if ($global_reward_percent != '') {
                                                    return '3';
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($categorylevelrewardtype == '2') {
                                    if ($categorylevelrewardpercents != '') {
                                        return '2';
                                    } else {
                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                if ($global_reward_points != '') {
                                                    return '3';
                                                }
                                            } else {
                                                if ($global_reward_type == '2') {
                                                    if ($global_reward_percent != '') {
                                                        return '3';
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($global_enable == '1') {
                                if ($global_reward_type == '1') {
                                    if ($global_reward_points != '') {
                                        return '3';
                                    }
                                } else {
                                    if ($global_reward_type == '2') {
                                        if ($global_reward_percent != '') {
                                            return '3';
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        if ($global_enable == '1') {
                            if ($global_reward_type == '1') {
                                if ($global_reward_points != '') {
                                    return '3';
                                }
                            } else {
                                if ($global_reward_type == '2') {
                                    if ($global_reward_percent != '') {
                                        return '3';
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /* Function to insert the fb like points to db. */

    public static function rs_insert_facebook_like_points($pointsredeemed, $getregularprice, $postid, $level, $currentuserid, $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail) {

        $rewardpoints = array('0');
        $rewardpercents = array('0');

        //Product Level Points and Percent
        $gettype = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_social_rewardsystem_options_facebook');
        $getpoints = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_socialrewardsystempoints_facebook');
        $getpercent = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_socialrewardsystempercent_facebook');

        $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
        
        $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
        $getaverage = $getpercent / 100;
        $getaveragepoints = $getaverage * $getregularprice;
        $pointswithvalue = $getaveragepoints * $pointconversion;
        $rewardpercentsforproductlevel = $pointswithvalue / $pointconversionvalue;

        //Category Level Points and Percent
        $categorylist = wp_get_post_terms($postid, 'product_cat');
        $getcount = count($categorylist);
        $term = get_the_terms($postid, 'product_cat');
        if (is_array($term)) {
            foreach ($term as $terms) {
                $termid = $terms->term_id;
                $categorylevelrewardtype = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_facebook_enable_rs_rule');
                $categorylevelrewardpoints = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_facebook_rs_category_points');
                $categorylevelrewardpercents = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_facebook_rs_category_percent');
                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                
                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                $getaverage = $categorylevelrewardpercents / 100;
                $getaveragepoints = $getaverage * $getregularprice;
                $pointswithvalue = $getaveragepoints * $pointconversion;
                $rewardpercentsforcategorylevel = $pointswithvalue / $pointconversionvalue;

                //Global Level Points and Percent
                $global_reward_type = get_option('rs_global_social_reward_type_facebook');
                $global_reward_points = get_option('rs_global_social_facebook_reward_points');
                $global_reward_percent = get_option('rs_global_social_facebook_reward_percent');
                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                
                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                $getaverage = $global_reward_percent / 100;
                $getaveragepoints = $getaverage * $getregularprice;
                $pointswithvalue = $getaveragepoints * $pointconversion;
                $rewardpercentsforgloballevel = $pointswithvalue / $pointconversionvalue;

                if ($getcount > 1) {
                    if ($categorylevelrewardpoints == '') {
                        $rewardpoints[] = $global_reward_points;
                    } else {
                        $rewardpoints[] = $categorylevelrewardpoints;
                    }

                    if ($categorylevelrewardpercents == '') {
                        $rewardpercents[] = $rewardpercentsforgloballevel;
                    } else {
                        $rewardpercents[] = $rewardpercentsforcategorylevel;
                    }
                } else {
                    if ($categorylevelrewardpoints == '') {
                        $rewardpoints[] = $global_reward_points;
                    } else {
                        $rewardpoints[] = $categorylevelrewardpoints;
                    }

                    if ($categorylevelrewardpercents == '') {
                        $rewardpercents[] = $rewardpercentsforgloballevel;
                    } else {
                        $rewardpercents[] = $rewardpercentsforcategorylevel;
                    }
                }
            }
        } else {
            $global_reward_type = get_option('rs_global_social_reward_type_facebook');
            $global_reward_points = get_option('rs_global_social_facebook_reward_points');
            $global_reward_percent = get_option('rs_global_social_facebook_reward_percent');
            $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
            
            $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
            $getaverage = $global_reward_percent / 100;
            $getaveragepoints = $getaverage * $getregularprice;
            $pointswithvalue = $getaveragepoints * $pointconversion;
            $rewardpercentsforgloballevel = $pointswithvalue / $pointconversionvalue;
        }

        $getcategorypoints = max($rewardpoints);
        $getcategorypercent = max($rewardpercents);
        $order_id = '0';
        $variationid = '0';
        $refuserid = '0';
        $equredeemamt = '0';
        $pointsredeemed = '0';
        $totalredeempoints = '0';
        $reasonindetail = '';
        $restrictuserpoints = get_option('rs_max_earning_points_for_user');
        $enabledisablemaxpoints = get_option('rs_enable_disable_max_earning_points_for_user');
        $noofdays = get_option('rs_point_to_be_expire');

        if (($noofdays != '0') && ($noofdays != '')) {
            $date = time() + ($noofdays * 24 * 60 * 60);
        } else {
            $date = '999999999999';
        }
        switch ($level) {
            case '1':
                if ($gettype == '1') {
                    if ($enabledisablemaxpoints == 'yes') {
                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            if ($getoldpoints <= $restrictuserpoints) {
                                $totalpointss = $getoldpoints + $getpoints;
                                if ($totalpointss <= $restrictuserpoints) {
                                    $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getpoints);
                                    RSPointExpiry::insert_earning_points($currentuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'RPFL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'RPFL', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                } else {
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $postid, '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                RSPointExpiry::insert_earning_points($currentuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                RSPointExpiry::record_the_points($currentuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                            }
                        } else {
                            $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getpoints);
                            RSPointExpiry::insert_earning_points($currentuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'RPFL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                            $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            RSPointExpiry::record_the_points($currentuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'RPFL', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                        }
                    } else {
                        $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getpoints);
                        RSPointExpiry::insert_earning_points($currentuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'RPFL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                        RSPointExpiry::record_the_points($currentuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'RPFL', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                    }
                } else {

                    if ($enabledisablemaxpoints == 'yes') {
                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            if ($getoldpoints <= $restrictuserpoints) {
                                $totalpointss = $getoldpoints + $rewardpercentsforproductlevel;
                                if ($totalpointss <= $restrictuserpoints) {
                                    $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforproductlevel);
                                    RSPointExpiry::insert_earning_points($currentuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'RPFL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'RPFL', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                } else {
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $postid, '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                RSPointExpiry::insert_earning_points($currentuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                RSPointExpiry::record_the_points($currentuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                            }
                        } else {
                            $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforproductlevel);
                            RSPointExpiry::insert_earning_points($currentuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'RPFL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                            $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            RSPointExpiry::record_the_points($currentuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'RPFL', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                        }
                    } else {
                        $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforproductlevel);
                        RSPointExpiry::insert_earning_points($currentuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'RPFL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                        RSPointExpiry::record_the_points($currentuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'RPFL', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                    }
                }
                break;
            case '2':
                if ($categorylevelrewardtype == '1') {
                    if ($enabledisablemaxpoints == 'yes') {
                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            if ($getoldpoints <= $restrictuserpoints) {
                                $totalpointss = $getoldpoints + $getcategorypoints;
                                if ($totalpointss <= $restrictuserpoints) {
                                    $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypoints);
                                    RSPointExpiry::insert_earning_points($currentuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'RPFL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'RPFL', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                } else {
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $postid, '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                RSPointExpiry::insert_earning_points($currentuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                RSPointExpiry::record_the_points($currentuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                            }
                        } else {
                            $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypoints);
                            RSPointExpiry::insert_earning_points($currentuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'RPFL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                            $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            RSPointExpiry::record_the_points($currentuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'RPFL', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                        }
                    } else {
                        $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypoints);
                        RSPointExpiry::insert_earning_points($currentuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'RPFL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                        RSPointExpiry::record_the_points($currentuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'RPFL', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                    }
                } else {
                    if ($enabledisablemaxpoints == 'yes') {
                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            if ($getoldpoints <= $restrictuserpoints) {
                                $totalpointss = $getoldpoints + $getcategorypercent;
                                if ($totalpointss <= $restrictuserpoints) {
                                    $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypercent);
                                    RSPointExpiry::insert_earning_points($currentuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'RPFL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'RPFL', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                } else {
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $postid, '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                RSPointExpiry::insert_earning_points($currentuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                RSPointExpiry::record_the_points($currentuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                            }
                        } else {
                            $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypercent);
                            RSPointExpiry::insert_earning_points($currentuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'RPFL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                            $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            RSPointExpiry::record_the_points($currentuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'RPFL', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                        }
                    } else {
                        $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypercent);
                        RSPointExpiry::insert_earning_points($currentuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'RPFL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                        RSPointExpiry::record_the_points($currentuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'RPFL', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                    }
                }
                break;
            case '3':
                if ($global_reward_type == '1') {
                    if ($enabledisablemaxpoints == 'yes') {
                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            if ($getoldpoints <= $restrictuserpoints) {
                                $totalpointss = $getoldpoints + $global_reward_points;
                                if ($totalpointss <= $restrictuserpoints) {
                                    $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $global_reward_points);
                                    RSPointExpiry::insert_earning_points($currentuserid, $global_rewardpointss, $pointsredeemed, $date, 'RPFL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $global_rewardpointss, $pointsredeemed, $date, 'RPFL', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                } else {
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $postid, '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                RSPointExpiry::insert_earning_points($currentuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                RSPointExpiry::record_the_points($currentuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                            }
                        } else {
                            $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $global_reward_points);
                            RSPointExpiry::insert_earning_points($currentuserid, $global_rewardpointss, $pointsredeemed, $date, 'RPFL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                            $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            RSPointExpiry::record_the_points($currentuserid, $global_rewardpointss, $pointsredeemed, $date, 'RPFL', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                        }
                    } else {
                        $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $global_reward_points);
                        RSPointExpiry::insert_earning_points($currentuserid, $global_rewardpointss, $pointsredeemed, $date, 'RPFL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                        RSPointExpiry::record_the_points($currentuserid, $global_rewardpointss, $pointsredeemed, $date, 'RPFL', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                    }
                } else {
                    if ($enabledisablemaxpoints == 'yes') {
                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            if ($getoldpoints <= $restrictuserpoints) {
                                $totalpointss = $getoldpoints + $rewardpercentsforgloballevel;
                                if ($totalpointss <= $restrictuserpoints) {
                                    $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforgloballevel);
                                    RSPointExpiry::insert_earning_points($currentuserid, $global_rewardpercents, $pointsredeemed, $date, 'RPFL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $global_rewardpercents, $pointsredeemed, $date, 'RPFL', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                } else {
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $postid, '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                RSPointExpiry::insert_earning_points($currentuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                RSPointExpiry::record_the_points($currentuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                            }
                        } else {
                            $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforgloballevel);
                            RSPointExpiry::insert_earning_points($currentuserid, $global_rewardpercents, $pointsredeemed, $date, 'RPFL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                            $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            RSPointExpiry::record_the_points($currentuserid, $global_rewardpercents, $pointsredeemed, $date, 'RPFL', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                        }
                    } else {
                        $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforgloballevel);
                        RSPointExpiry::insert_earning_points($currentuserid, $global_rewardpercents, $pointsredeemed, $date, 'RPFL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                        RSPointExpiry::record_the_points($currentuserid, $global_rewardpercents, $pointsredeemed, $date, 'RPFL', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                    }
                }
                break;
        }
    }

    public static function rs_insert_facebook_share_points($pointsredeemed, $getregularprice, $postid, $level, $currentuserid, $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail) {

        $rewardpoints = array('0');
        $rewardpercents = array('0');

        //Product Level Points and Percent
        $gettype = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_social_rewardsystem_options_facebook_share');
        $getpoints = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_socialrewardsystempoints_facebook_share');
        $getpercent = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_socialrewardsystempercent_facebook_share');
        $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
        
        $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
        $getaverage = $getpercent / 100;
        $getaveragepoints = $getaverage * $getregularprice;
        $pointswithvalue = $getaveragepoints * $pointconversion;
        $rewardpercentsforproductlevel = $pointswithvalue / $pointconversionvalue;

        //Category Level Points and Percent
        $categorylist = wp_get_post_terms($postid, 'product_cat');
        $getcount = count($categorylist);
        $term = get_the_terms($postid, 'product_cat');
        if (is_array($term)) {
            foreach ($term as $terms) {
                $termid = $terms->term_id;
                $categorylevelrewardtype = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_facebook_share_enable_rs_rule');
                $categorylevelrewardpoints = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_facebook_share_rs_category_points');
                $categorylevelrewardpercents = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_facebook_share_rs_category_percent');
                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                
                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                $getaverage = $categorylevelrewardpercents / 100;
                $getaveragepoints = $getaverage * $getregularprice;
                $pointswithvalue = $getaveragepoints * $pointconversion;
                $rewardpercentsforcategorylevel = $pointswithvalue / $pointconversionvalue;

                //Global Level Points and Percent
                $global_reward_type = get_option('rs_global_social_reward_type_facebook_share');
                $global_reward_points = get_option('rs_global_social_facebook_share_reward_points');
                $global_reward_percent = get_option('rs_global_social_facebook_share_reward_percent');
                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                
                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                $getaverage = $global_reward_percent / 100;
                $getaveragepoints = $getaverage * $getregularprice;
                $pointswithvalue = $getaveragepoints * $pointconversion;
                $rewardpercentsforgloballevel = $pointswithvalue / $pointconversionvalue;

                if ($getcount > 1) {
                    if ($categorylevelrewardpoints == '') {
                        $rewardpoints[] = $global_reward_points;
                    } else {
                        $rewardpoints[] = $categorylevelrewardpoints;
                    }

                    if ($categorylevelrewardpercents == '') {
                        $rewardpercents[] = $rewardpercentsforgloballevel;
                    } else {
                        $rewardpercents[] = $rewardpercentsforcategorylevel;
                    }
                } else {
                    if ($categorylevelrewardpoints == '') {
                        $rewardpoints[] = $global_reward_points;
                    } else {
                        $rewardpoints[] = $categorylevelrewardpoints;
                    }

                    if ($categorylevelrewardpercents == '') {
                        $rewardpercents[] = $rewardpercentsforgloballevel;
                    } else {
                        $rewardpercents[] = $rewardpercentsforcategorylevel;
                    }
                }
            }
        } else {
            $global_reward_type = get_option('rs_global_social_reward_type_facebook_share');
            $global_reward_points = get_option('rs_global_social_facebook_share_reward_points');
            $global_reward_percent = get_option('rs_global_social_facebook_share_reward_percent');
            $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
            
            $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
            $getaverage = $global_reward_percent / 100;
            $getaveragepoints = $getaverage * $getregularprice;
            $pointswithvalue = $getaveragepoints * $pointconversion;
            $rewardpercentsforgloballevel = $pointswithvalue / $pointconversionvalue;
        }

        $getcategorypoints = max($rewardpoints);
        $getcategorypercent = max($rewardpercents);
        $order_id = '0';
        $variationid = '0';
        $refuserid = '0';
        $equredeemamt = '0';
        $pointsredeemed = '0';
        $totalredeempoints = '0';
        $reasonindetail = '';
        $restrictuserpoints = get_option('rs_max_earning_points_for_user');
        $enabledisablemaxpoints = get_option('rs_enable_disable_max_earning_points_for_user');
        $noofdays = get_option('rs_point_to_be_expire');

        if (($noofdays != '0') && ($noofdays != '')) {
            $date = time() + ($noofdays * 24 * 60 * 60);
        } else {
            $date = '999999999999';
        }
        switch ($level) {
            case '1':
                if ($gettype == '1') {
                    if ($enabledisablemaxpoints == 'yes') {
                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            if ($getoldpoints <= $restrictuserpoints) {
                                $totalpointss = $getoldpoints + $getpoints;
                                if ($totalpointss <= $restrictuserpoints) {
                                    $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getpoints);
                                    RSPointExpiry::insert_earning_points($currentuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'RPFS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'RPFS', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                } else {
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $postid, '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                RSPointExpiry::insert_earning_points($currentuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                RSPointExpiry::record_the_points($currentuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                            }
                        } else {
                            $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getpoints);
                            RSPointExpiry::insert_earning_points($currentuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'RPFS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                            $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            RSPointExpiry::record_the_points($currentuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'RPFS', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                        }
                    } else {
                        $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getpoints);
                        RSPointExpiry::insert_earning_points($currentuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'RPFS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                        RSPointExpiry::record_the_points($currentuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'RPFS', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                    }
                } else {

                    if ($enabledisablemaxpoints == 'yes') {
                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            if ($getoldpoints <= $restrictuserpoints) {
                                $totalpointss = $getoldpoints + $rewardpercentsforproductlevel;
                                if ($totalpointss <= $restrictuserpoints) {
                                    $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforproductlevel);
                                    RSPointExpiry::insert_earning_points($currentuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'RPFS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'RPFS', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                } else {
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $postid, '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                RSPointExpiry::insert_earning_points($currentuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                RSPointExpiry::record_the_points($currentuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                            }
                        } else {
                            $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforproductlevel);
                            RSPointExpiry::insert_earning_points($currentuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'RPFS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                            $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            RSPointExpiry::record_the_points($currentuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'RPFS', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                        }
                    } else {
                        $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforproductlevel);
                        RSPointExpiry::insert_earning_points($currentuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'RPFS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                        RSPointExpiry::record_the_points($currentuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'RPFS', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                    }
                }
                break;
            case '2':
                if ($categorylevelrewardtype == '1') {
                    if ($enabledisablemaxpoints == 'yes') {
                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            if ($getoldpoints <= $restrictuserpoints) {
                                $totalpointss = $getoldpoints + $getcategorypoints;
                                if ($totalpointss <= $restrictuserpoints) {
                                    $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypoints);
                                    RSPointExpiry::insert_earning_points($currentuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'RPFS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'RPFS', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                } else {
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $postid, '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                RSPointExpiry::insert_earning_points($currentuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                RSPointExpiry::record_the_points($currentuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                            }
                        } else {
                            $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypoints);
                            RSPointExpiry::insert_earning_points($currentuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'RPFS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                            $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            RSPointExpiry::record_the_points($currentuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'RPFS', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                        }
                    } else {
                        $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypoints);
                        RSPointExpiry::insert_earning_points($currentuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'RPFS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                        RSPointExpiry::record_the_points($currentuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'RPFS', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                    }
                } else {
                    if ($enabledisablemaxpoints == 'yes') {
                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            if ($getoldpoints <= $restrictuserpoints) {
                                $totalpointss = $getoldpoints + $getcategorypercent;
                                if ($totalpointss <= $restrictuserpoints) {
                                    $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypercent);
                                    RSPointExpiry::insert_earning_points($currentuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'RPFS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'RPFS', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                } else {
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $postid, '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                RSPointExpiry::insert_earning_points($currentuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                RSPointExpiry::record_the_points($currentuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                            }
                        } else {
                            $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypercent);
                            RSPointExpiry::insert_earning_points($currentuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'RPFS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                            $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            RSPointExpiry::record_the_points($currentuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'RPFS', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                        }
                    } else {
                        $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypercent);
                        RSPointExpiry::insert_earning_points($currentuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'RPFS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                        RSPointExpiry::record_the_points($currentuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'RPFS', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                    }
                }
                break;
            case '3':
                if ($global_reward_type == '1') {
                    if ($enabledisablemaxpoints == 'yes') {
                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            if ($getoldpoints <= $restrictuserpoints) {
                                $totalpointss = $getoldpoints + $global_reward_points;
                                if ($totalpointss <= $restrictuserpoints) {
                                    $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $global_reward_points);
                                    RSPointExpiry::insert_earning_points($currentuserid, $global_rewardpointss, $pointsredeemed, $date, 'RPFS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $global_rewardpointss, $pointsredeemed, $date, 'RPFS', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                } else {
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $postid, '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                RSPointExpiry::insert_earning_points($currentuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                RSPointExpiry::record_the_points($currentuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                            }
                        } else {
                            $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $global_reward_points);
                            RSPointExpiry::insert_earning_points($currentuserid, $global_rewardpointss, $pointsredeemed, $date, 'RPFS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                            $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            RSPointExpiry::record_the_points($currentuserid, $global_rewardpointss, $pointsredeemed, $date, 'RPFS', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                        }
                    } else {
                        $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $global_reward_points);
                        RSPointExpiry::insert_earning_points($currentuserid, $global_rewardpointss, $pointsredeemed, $date, 'RPFS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                        RSPointExpiry::record_the_points($currentuserid, $global_rewardpointss, $pointsredeemed, $date, 'RPFS', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                    }
                } else {
                    if ($enabledisablemaxpoints == 'yes') {
                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            if ($getoldpoints <= $restrictuserpoints) {
                                $totalpointss = $getoldpoints + $rewardpercentsforgloballevel;
                                if ($totalpointss <= $restrictuserpoints) {
                                    $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforgloballevel);
                                    RSPointExpiry::insert_earning_points($currentuserid, $global_rewardpercents, $pointsredeemed, $date, 'RPFS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $global_rewardpercents, $pointsredeemed, $date, 'RPFS', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                } else {
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $postid, '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                RSPointExpiry::insert_earning_points($currentuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                RSPointExpiry::record_the_points($currentuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                            }
                        } else {
                            $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforgloballevel);
                            RSPointExpiry::insert_earning_points($currentuserid, $global_rewardpercents, $pointsredeemed, $date, 'RPFS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                            $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            RSPointExpiry::record_the_points($currentuserid, $global_rewardpercents, $pointsredeemed, $date, 'RPFS', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                        }
                    } else {
                        $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforgloballevel);
                        RSPointExpiry::insert_earning_points($currentuserid, $global_rewardpercents, $pointsredeemed, $date, 'RPFS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                        RSPointExpiry::record_the_points($currentuserid, $global_rewardpercents, $pointsredeemed, $date, 'RPFS', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                    }
                }
                break;
        }
    }

    public static function rs_insert_facebook_like_revised_points($pointsredeemed, $getregularprice, $postid, $level, $currentuserid, $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail) {

        $rewardpoints = array('0');
        $rewardpercents = array('0');

        //Product Level Points and Percent
        $gettype = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_social_rewardsystem_options_facebook');
        $getpoints = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_socialrewardsystempoints_facebook');
        $getpercent = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_socialrewardsystempercent_facebook');
        $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
        
        $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
        $getaverage = $getpercent / 100;
        $getaveragepoints = $getaverage * $getregularprice;
        $pointswithvalue = $getaveragepoints * $pointconversion;
        $rewardpercentsforproductlevel = $pointswithvalue / $pointconversionvalue;

        //Category Level Points and Percent
        $categorylist = wp_get_post_terms($postid, 'product_cat');
        $getcount = count($categorylist);
        $term = get_the_terms($postid, 'product_cat');
        if (is_array($term)) {
            foreach ($term as $terms) {
                $termid = $terms->term_id;
                $categorylevelrewardtype = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_facebook_enable_rs_rule');
                $categorylevelrewardpoints = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_facebook_rs_category_points');
                $categorylevelrewardpercents = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_facebook_rs_category_percent');
                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                
                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                $getaverage = $getpercent / 100;
                $getaveragepoints = $categorylevelrewardpercents * $getregularprice;
                $pointswithvalue = $getaveragepoints * $pointconversion;
                $rewardpercentsforcategorylevel = $pointswithvalue / $pointconversionvalue;

                //Global Level Points and Percent
                $global_reward_type = get_option('rs_global_social_reward_type_facebook');
                $global_reward_points = get_option('rs_global_social_facebook_reward_points');
                $global_reward_percent = get_option('rs_global_social_facebook_reward_percent');
                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                
                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                $getaverage = $getpercent / 100;
                $getaveragepoints = $global_reward_percent * $getregularprice;
                $pointswithvalue = $getaveragepoints * $pointconversion;
                $rewardpercentsforgloballevel = $pointswithvalue / $pointconversionvalue;

                if ($getcount > 1) {
                    if ($categorylevelrewardpoints == '') {
                        $rewardpoints[] = $global_reward_points;
                    } else {
                        $rewardpoints[] = $categorylevelrewardpoints;
                    }

                    if ($categorylevelrewardpercents == '') {
                        $rewardpercents[] = $rewardpercentsforgloballevel;
                    } else {
                        $rewardpercents[] = $rewardpercentsforcategorylevel;
                    }
                } else {
                    if ($categorylevelrewardpoints == '') {
                        $rewardpoints[] = $global_reward_points;
                    } else {
                        $rewardpoints[] = $categorylevelrewardpoints;
                    }

                    if ($categorylevelrewardpercents == '') {
                        $rewardpercents[] = $rewardpercentsforgloballevel;
                    } else {
                        $rewardpercents[] = $rewardpercentsforcategorylevel;
                    }
                }
            }
        } else {
            $global_reward_type = get_option('rs_global_social_reward_type_facebook');
            $global_reward_points = get_option('rs_global_social_facebook_reward_points');
            $global_reward_percent = get_option('rs_global_social_facebook_reward_percent');
            $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
            
            $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
            $getaverage = $global_reward_percent / 100;
            $getaveragepoints = $global_reward_percent * $getregularprice;
            $pointswithvalue = $getaveragepoints * $pointconversion;
            $rewardpercentsforgloballevel = $pointswithvalue / $pointconversionvalue;
        }

        $getcategorypoints = max($rewardpoints);
        $getcategorypercent = max($rewardpercents);
        $noofdays = get_option('rs_point_to_be_expire');

        if (($noofdays != '0') && ($noofdays != '')) {
            $date = time() + ($noofdays * 24 * 60 * 60);
        } else {
            $date = '999999999999';
        }
        switch ($level) {
            case '1':
                if ($gettype == '1') {
                    $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getpoints);
                    RSPointExpiry::insert_earning_points($currentuserid, $pointsredeemed, $productlevelrewardpointss, $date, 'RVPFRPFL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                    $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                    $order_id = '0';
                    $variationid = '0';
                    $refuserid = '0';
                    $equredeemamt = '0';
                    $pointsredeemed = '0';
                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                    RSPointExpiry::record_the_points($currentuserid, $pointsredeemed, $productlevelrewardpointss, $date, 'RVPFRPFL', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                } else {
                    $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforproductlevel);
                    RSPointExpiry::insert_earning_points($currentuserid, $pointsredeemed, $productlevelrewardpercentss, $date, 'RVPFRPFL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                    $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                    $order_id = '0';
                    $variationid = '0';
                    $refuserid = '0';
                    $equredeemamt = '0';
                    $pointsredeemed = '0';
                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                    RSPointExpiry::record_the_points($currentuserid, $pointsredeemed, $productlevelrewardpercentss, $date, 'RVPFRPFL', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                }
                break;
            case '2':
                if ($categorylevelrewardtype == '1') {
                    $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypoints);
                    RSPointExpiry::insert_earning_points($currentuserid, $pointsredeemed, $categorylevelrewardpointss, $date, 'RVPFRPFL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                    $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                    $order_id = '0';
                    $variationid = '0';
                    $refuserid = '0';
                    $equredeemamt = '0';
                    $pointsredeemed = '0';
                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                    RSPointExpiry::record_the_points($currentuserid, $pointsredeemed, $categorylevelrewardpointss, $date, 'RVPFRPFL', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                } else {
                    $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypercent);
                    RSPointExpiry::insert_earning_points($currentuserid, $pointsredeemed, $categorylevelrewardpercents, $date, 'RVPFRPFL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                    $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                    $order_id = '0';
                    $variationid = '0';
                    $refuserid = '0';
                    $equredeemamt = '0';
                    $pointsredeemed = '0';
                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                    RSPointExpiry::record_the_points($currentuserid, $pointsredeemed, $categorylevelrewardpercents, $date, 'RVPFRPFL', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                }
                break;
            case '3':
                if ($global_reward_type == '1') {
                    $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $global_reward_points);
                    RSPointExpiry::insert_earning_points($currentuserid, $pointsredeemed, $global_rewardpointss, $date, 'RVPFRPFL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                    $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                    $order_id = '0';
                    $variationid = '0';
                    $refuserid = '0';
                    $equredeemamt = '0';
                    $pointsredeemed = '0';
                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                    RSPointExpiry::record_the_points($currentuserid, $pointsredeemed, $global_rewardpointss, $date, 'RVPFRPFL', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                } else {
                    $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforgloballevel);
                    RSPointExpiry::insert_earning_points($currentuserid, $pointsredeemed, $global_rewardpercents, $date, 'RVPFRPFL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                    $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                    $order_id = '0';
                    $variationid = '0';
                    $refuserid = '0';
                    $equredeemamt = '0';
                    $pointsredeemed = '0';
                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                    RSPointExpiry::record_the_points($currentuserid, $pointsredeemed, $global_rewardpercents, $date, 'RVPFRPFL', $equearnamt, $equredeemamt, $order_id, $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                }
                break;
        }
    }

    /* Function to insert twitter tweet reward points */

    public static function update_reward_points_for_twitter_tweet() {
        $userid = get_current_user_id();
        global $wpdb;
        $table_name = $wpdb->prefix . 'rspointexpiry';
        $banning_type = FPRewardSystem::check_banning_type($userid);
        if ($banning_type != 'earningonly' && $banning_type != 'both') {


            if (isset($_POST['state']) && ($_POST['postid']) && ($_POST['currentuserid'])) {
                if (get_option('rs_set_price_to_calculate_rewardpoints_by_percentage') == '1') {
                    $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($_POST['postid'], '_regular_price');
                    if ($getregularprice == '') {
                        $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($_POST['postid'], '_price');
                    }
                } else {
                    $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($_POST['postid'], '_price');
                    if ($getregularprice == '') {
                        $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($_POST['postid'], '_regular_price');
                    }
                }
                $postid = $_POST['postid'];
                $currentuserid = $_POST['currentuserid'];
                $getarrayids[] = $_POST['postid'];
                $oldoption = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($_POST['currentuserid'], '_rstwittertweet');
                if (!empty($oldoption)) {
                    if (!in_array($_POST['postid'], $oldoption)) {
                        $mergedata = array_merge((array) $oldoption, $getarrayids);
                        RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($_POST['currentuserid'], '_rstwittertweet', $mergedata);
                        if ($_POST['state'] == 'on') {
                            $checklevel = self::checklevel_for_twitter_tweet($postid);
                            $orderid = '0';
                            $totalearnedpoints = '0';
                            $totalredeempoints = '0';
                            $pointsredeemed = '0';
                            $reasonindetail = '';
                            self::rs_insert_twitter_tweet_points($pointsredeemed, $getregularprice, $postid, $checklevel, $currentuserid, $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        }
                    } else {
                        _e('You already Tweet this post', 'rewardsystem');
                    }
                } else {
                    RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($_POST['currentuserid'], '_rstwittertweet', $getarrayids);
                    if ($_POST['state'] == 'on') {
                        $checklevel = self::checklevel_for_facebook_like($postid);
                        $orderid = '0';
                        $totalearnedpoints = '0';
                        $totalredeempoints = '0';
                        $pointsredeemed = '0';
                        $reasonindetail = '';
                        self::rs_insert_twitter_tweet_points($pointsredeemed, $getregularprice, $postid, $checklevel, $currentuserid, $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                    }
                }
                echo "Ajax Call Successfully Triggered";
            }
            do_action('fp_reward_point_for_twitter_tweet');
            exit();
        }
    }

    /* Function to check the level of the points from where it is awraded. */

    public static function checklevel_for_twitter_tweet($postid) {

        //Product Level
        $enablerewards = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_socialrewardsystemcheckboxvalue');
        $gettype = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_social_rewardsystem_options_twitter');
        $getpoints = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_socialrewardsystempoints_twitter');
        $getpercent = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_socialrewardsystempercent_twitter');

        //Category Level
        $categorylist = wp_get_post_terms($postid, 'product_cat');
        $getcount = count($categorylist);
        $term = get_the_terms($postid, 'product_cat');
        if (is_array($term)) {
            foreach ($term as $terms) {
                $termid = $terms->term_id;
                $categorylevel = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'enable_social_reward_system_category');
                $categorylevelrewardtype = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_twitter_enable_rs_rule');
                $categorylevelrewardpoints = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_twitter_rs_category_points');
                $categorylevelrewardpercents = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_twitter_rs_category_percent');
            }
        }

        //Global Level
        $global_enable = get_option('rs_global_social_enable_disable_reward');
        $global_reward_type = get_option('rs_global_social_reward_type_twitter');
        $global_reward_points = get_option('rs_global_social_twitter_reward_points');
        $global_reward_percent = get_option('rs_global_social_twitter_reward_percent');

        if ($enablerewards == 'yes') {
            if ($gettype == '1') {
                if ($getpoints != '') {
                    return '1';
                } else {
                    if ($getcount >= '1') {
                        if (($categorylevel == 'yes') || ($categorylevel != '')) {
                            if ($categorylevelrewardtype == '1') {
                                if ($categorylevelrewardpoints != '') {
                                    return '2';
                                } else {
                                    if ($global_enable == '1') {
                                        if ($global_reward_type == '1') {
                                            if ($global_reward_points != '') {
                                                return '2';
                                            }
                                        } else {
                                            if ($global_reward_type == '2') {
                                                if ($global_reward_percent != '') {
                                                    return '2';
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($categorylevelrewardtype == '2') {
                                    if ($categorylevelrewardpercents != '') {
                                        return '2';
                                    } else {
                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                if ($global_reward_points != '') {
                                                    return '2';
                                                }
                                            } else {
                                                if ($global_reward_type == '2') {
                                                    if ($global_reward_percent != '') {
                                                        return '2';
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($global_enable == '1') {
                                if ($global_reward_type == '1') {
                                    if ($global_reward_points != '') {
                                        return '3';
                                    }
                                } else {
                                    if ($global_reward_type == '2') {
                                        if ($global_reward_percent != '') {
                                            return '3';
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        if ($global_enable == '1') {
                            if ($global_reward_type == '1') {
                                if ($global_reward_points != '') {
                                    return '3';
                                }
                            } else {
                                if ($global_reward_type == '2') {
                                    if ($global_reward_percent != '') {
                                        return '3';
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                if ($getpercent != '') {
                    return '1';
                } else {
                    if ($getcount >= '1') {
                        if (($categorylevel == 'yes') || ($categorylevel != '')) {
                            if ($categorylevelrewardtype == '1') {
                                if ($categorylevelrewardpoints != '') {
                                    return '2';
                                } else {
                                    if ($global_enable == '1') {
                                        if ($global_reward_type == '1') {
                                            if ($global_reward_points != '') {
                                                return '2';
                                            }
                                        } else {
                                            if ($global_reward_type == '2') {
                                                if ($global_reward_percent != '') {
                                                    return '2';
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($categorylevelrewardtype == '2') {
                                    if ($categorylevelrewardpercents != '') {
                                        return '2';
                                    } else {
                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                if ($global_reward_points != '') {
                                                    return '2';
                                                }
                                            } else {
                                                if ($global_reward_type == '2') {
                                                    if ($global_reward_percent != '') {
                                                        return '2';
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($global_enable == '1') {
                                if ($global_reward_type == '1') {
                                    if ($global_reward_points != '') {
                                        return '3';
                                    }
                                } else {
                                    if ($global_reward_type == '2') {
                                        if ($global_reward_percent != '') {
                                            return '3';
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        if ($global_enable == '1') {
                            if ($global_reward_type == '1') {
                                if ($global_reward_points != '') {
                                    return '3';
                                }
                            } else {
                                if ($global_reward_type == '2') {
                                    if ($global_reward_percent != '') {
                                        return '3';
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /* Function to insert the twitter tweet points to db. */

    public static function rs_insert_twitter_tweet_points($pointsredeemed, $getregularprice, $postid, $level, $currentuserid, $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail) {

        $rewardpoints = array('0');
        $rewardpercents = array('0');

        //Product Level Points and Percent
        $gettype = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_social_rewardsystem_options_twitter');
        $getpoints = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_socialrewardsystempoints_twitter');
        $getpercent = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_socialrewardsystempercent_twitter');
        $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
        
        $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
        $getaverage = $getpercent / 100;
        $getaveragepoints = $getaverage * $getregularprice;
        $pointswithvalue = $getaveragepoints * $pointconversion;
        $rewardpercentsforproductlevel = $pointswithvalue / $pointconversionvalue;

        //Category Level Points and Percent
        $categorylist = wp_get_post_terms($postid, 'product_cat');
        $getcount = count($categorylist);
        $term = get_the_terms($postid, 'product_cat');
        if (is_array($term)) {
            foreach ($term as $terms) {
                $termid = $terms->term_id;
                $categorylevelrewardtype = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_twitter_enable_rs_rule');
                $categorylevelrewardpoints = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_twitter_rs_category_points');
                $categorylevelrewardpercents = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_twitter_rs_category_percent');
                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                
                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                $getaverage = $categorylevelrewardpercents / 100;
                $getaveragepoints = $categorylevelrewardpercents * $getregularprice;
                $pointswithvalue = $getaveragepoints * $pointconversion;
                $rewardpercentsforcategorylevel = $pointswithvalue / $pointconversionvalue;

                //Global Level Points and Percent
                $global_reward_type = get_option('rs_global_social_reward_type_twitter');
                $global_reward_points = get_option('rs_global_social_twitter_reward_points');
                $global_reward_percent = get_option('rs_global_social_twitter_reward_percent');
                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                
                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                $getaverage = $global_reward_percent / 100;
                $getaveragepoints = $global_reward_percent * $getregularprice;
                $pointswithvalue = $getaveragepoints * $pointconversion;
                $rewardpercentsforgloballevel = $pointswithvalue / $pointconversionvalue;

                if ($getcount > 1) {
                    if ($categorylevelrewardpoints == '') {
                        $rewardpoints[] = $global_reward_points;
                    } else {
                        $rewardpoints[] = $categorylevelrewardpoints;
                    }

                    if ($categorylevelrewardpercents == '') {
                        $rewardpercents[] = $rewardpercentsforgloballevel;
                    } else {
                        $rewardpercents[] = $rewardpercentsforcategorylevel;
                    }
                } else {
                    if ($categorylevelrewardpoints == '') {
                        $rewardpoints[] = $global_reward_points;
                    } else {
                        $rewardpoints[] = $categorylevelrewardpoints;
                    }

                    if ($categorylevelrewardpercents == '') {
                        $rewardpercents[] = $rewardpercentsforgloballevel;
                    } else {
                        $rewardpercents[] = $rewardpercentsforcategorylevel;
                    }
                }
            }
        } else {
            $global_reward_type = get_option('rs_global_social_reward_type_twitter');
            $global_reward_points = get_option('rs_global_social_twitter_reward_points');
            $global_reward_percent = get_option('rs_global_social_twitter_reward_percent');
            $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
            
            $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
            $getaverage = $global_reward_percent / 100;
            $getaveragepoints = $global_reward_percent * $getregularprice;
            $pointswithvalue = $getaveragepoints * $pointconversion;
            $rewardpercentsforgloballevel = $pointswithvalue / $pointconversionvalue;
        }

        $getcategorypoints = max($rewardpoints);
        $getcategorypercent = max($rewardpercents);
        $order_id = '0';
        $variationid = '0';
        $refuserid = '0';
        $equredeemamt = '0';
        $pointsredeemed = '0';
        $totalearnedpoints = '0';
        $totalredeempoints = '0';
        $reasonindetail = '';
        $restrictuserpoints = get_option('rs_max_earning_points_for_user');
        $enabledisablemaxpoints = get_option('rs_enable_disable_max_earning_points_for_user');
        $noofdays = get_option('rs_point_to_be_expire');

        if (($noofdays != '0') && ($noofdays != '')) {
            $date = time() + ($noofdays * 24 * 60 * 60);
        } else {
            $date = '999999999999';
        }
        switch ($level) {
            case '1':
                if ($gettype == '1') {
                    if ($enabledisablemaxpoints == 'yes') {
                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            if ($getoldpoints <= $restrictuserpoints) {
                                $totalpointss = $getoldpoints + $getpoints;
                                if ($totalpointss <= $restrictuserpoints) {
                                    $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getpoints);
                                    RSPointExpiry::insert_earning_points($currentuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'RPTT', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'RPTT', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                } else {
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                RSPointExpiry::insert_earning_points($currentuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                RSPointExpiry::record_the_points($currentuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                            }
                        } else {
                            $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getpoints);
                            RSPointExpiry::insert_earning_points($currentuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'RPTT', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                            $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            RSPointExpiry::record_the_points($currentuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'RPTT', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                        }
                    } else {
                        $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getpoints);
                        RSPointExpiry::insert_earning_points($currentuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'RPTT', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                        RSPointExpiry::record_the_points($currentuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'RPTT', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                    }
                } else {
                    if ($enabledisablemaxpoints == 'yes') {
                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            if ($getoldpoints <= $restrictuserpoints) {
                                $totalpointss = $getoldpoints + $rewardpercentsforproductlevel;
                                if ($totalpointss <= $restrictuserpoints) {
                                    $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforproductlevel);
                                    RSPointExpiry::insert_earning_points($currentuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'RPTT', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'RPTT', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                } else {
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                RSPointExpiry::insert_earning_points($currentuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                RSPointExpiry::record_the_points($currentuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                            }
                        } else {
                            $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforproductlevel);
                            RSPointExpiry::insert_earning_points($currentuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'RPTT', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                            $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            RSPointExpiry::record_the_points($currentuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'RPTT', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                        }
                    } else {
                        $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforproductlevel);
                        RSPointExpiry::insert_earning_points($currentuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'RPTT', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                        RSPointExpiry::record_the_points($currentuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'RPTT', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                    }
                }
                break;
            case '2':
                if ($categorylevelrewardtype == '1') {
                    if ($enabledisablemaxpoints == 'yes') {
                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            if ($getoldpoints <= $restrictuserpoints) {
                                $totalpointss = $getoldpoints + $getcategorypoints;
                                if ($totalpointss <= $restrictuserpoints) {
                                    $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypoints);
                                    RSPointExpiry::insert_earning_points($currentuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'RPTT', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'RPTT', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                } else {
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                RSPointExpiry::insert_earning_points($currentuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                RSPointExpiry::record_the_points($currentuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                            }
                        } else {
                            $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypoints);
                            RSPointExpiry::insert_earning_points($currentuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'RPTT', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                            $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            RSPointExpiry::record_the_points($currentuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'RPTT', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                        }
                    } else {
                        $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypoints);
                        RSPointExpiry::insert_earning_points($currentuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'RPTT', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                        RSPointExpiry::record_the_points($currentuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'RPTT', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                    }
                } else {
                    if ($enabledisablemaxpoints == 'yes') {
                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            if ($getoldpoints <= $restrictuserpoints) {
                                $totalpointss = $getoldpoints + $getcategorypercent;
                                if ($totalpointss <= $restrictuserpoints) {
                                    $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypercent);
                                    RSPointExpiry::insert_earning_points($currentuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'RPTT', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'RPTT', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                } else {
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                RSPointExpiry::insert_earning_points($currentuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                RSPointExpiry::record_the_points($currentuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                            }
                        } else {
                            $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypercent);
                            RSPointExpiry::insert_earning_points($currentuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'RPTT', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                            $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            RSPointExpiry::record_the_points($currentuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'RPTT', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                        }
                    } else {
                        $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypercent);
                        RSPointExpiry::insert_earning_points($currentuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'RPTT', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                        RSPointExpiry::record_the_points($currentuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'RPTT', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                    }
                }
                break;
            case '3':
                if ($global_reward_type == '1') {
                    if ($enabledisablemaxpoints == 'yes') {
                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            if ($getoldpoints <= $restrictuserpoints) {
                                $totalpointss = $getoldpoints + $global_reward_points;
                                if ($totalpointss <= $restrictuserpoints) {
                                    $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $global_reward_points);
                                    RSPointExpiry::insert_earning_points($currentuserid, $global_rewardpointss, $pointsredeemed, $date, 'RPTT', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $global_rewardpointss, $pointsredeemed, $date, 'RPTT', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                } else {
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                RSPointExpiry::insert_earning_points($currentuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                RSPointExpiry::record_the_points($currentuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                            }
                        } else {
                            $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $global_reward_points);
                            RSPointExpiry::insert_earning_points($currentuserid, $global_rewardpointss, $pointsredeemed, $date, 'RPTT', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                            $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            RSPointExpiry::record_the_points($currentuserid, $global_rewardpointss, $pointsredeemed, $date, 'RPTT', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                        }
                    } else {
                        $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $global_reward_points);
                        RSPointExpiry::insert_earning_points($currentuserid, $global_rewardpointss, $pointsredeemed, $date, 'RPTT', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                        RSPointExpiry::record_the_points($currentuserid, $global_rewardpointss, $pointsredeemed, $date, 'RPTT', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                    }
                } else {
                    if ($enabledisablemaxpoints == 'yes') {
                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            if ($getoldpoints <= $restrictuserpoints) {
                                $totalpointss = $getoldpoints + $rewardpercentsforgloballevel;
                                if ($totalpointss <= $restrictuserpoints) {
                                    $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforgloballevel);
                                    RSPointExpiry::insert_earning_points($currentuserid, $global_rewardpercents, $pointsredeemed, $date, 'RPTT', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $global_rewardpercents, $pointsredeemed, $date, 'RPTT', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                } else {
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                RSPointExpiry::insert_earning_points($currentuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                RSPointExpiry::record_the_points($currentuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                            }
                        } else {
                            $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforgloballevel);
                            RSPointExpiry::insert_earning_points($currentuserid, $global_rewardpercents, $pointsredeemed, $date, 'RPTT', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                            $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            RSPointExpiry::record_the_points($currentuserid, $global_rewardpercents, $pointsredeemed, $date, 'RPTT', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                        }
                    } else {
                        $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforgloballevel);
                        RSPointExpiry::insert_earning_points($currentuserid, $global_rewardpercents, $pointsredeemed, $date, 'RPTT', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                        RSPointExpiry::record_the_points($currentuserid, $global_rewardpercents, $pointsredeemed, $date, 'RPTT', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                    }
                }
                break;
        }
    }

    /* Function to insert Google+1 Share reward points */

    public static function update_reward_points_for_google_plus_share() {
        $userid = get_current_user_id();
        global $wpdb;
        $table_name = $wpdb->prefix . 'rspointexpiry';
        $banning_type = FPRewardSystem::check_banning_type($userid);
        if ($banning_type != 'earningonly' && $banning_type != 'both') {


            if (isset($_POST['state']) && ($_POST['postid']) && ($_POST['currentuserid'])) {
                if (get_option('rs_set_price_to_calculate_rewardpoints_by_percentage') == '1') {
                    $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($_POST['postid'], '_regular_price');
                    if ($getregularprice == '') {
                        $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($_POST['postid'], '_price');
                    }
                } else {
                    $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($_POST['postid'], '_price');
                    if ($getregularprice == '') {
                        $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($_POST['postid'], '_regular_price');
                    }
                }
                $postid = $_POST['postid'];
                $currentuserid = $_POST['currentuserid'];
                $getarrayids[] = $_POST['postid'];
                $oldoption = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($_POST['currentuserid'], '_rsgoogleshares');
                if (!empty($oldoption)) {
                    if (!in_array($_POST['postid'], $oldoption)) {
                        $mergedata = array_merge((array) $oldoption, $getarrayids);
                        RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($_POST['currentuserid'], '_rsgoogleshares', $mergedata);
                        if ($_POST['state'] == 'on') {
                            $checklevel = self::checklevel_for_google_plus_share($postid);
                            $orderid = '0';
                            $totalearnedpoints = '0';
                            $totalredeempoints = '0';
                            $pointsredeemed = '0';
                            $reasonindetail = '';
                            self::rs_insert_google_plus_share_points($pointsredeemed, $getregularprice, $postid, $checklevel, $currentuserid, $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        }
                    } else {
                        _e('You already Shared this post on Goole+1', 'rewardsystem');
                    }
                } else {
                    RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($_POST['currentuserid'], '_rsgoogleshares', $getarrayids);
                    if ($_POST['state'] == 'on') {
                        $checklevel = self::checklevel_for_google_plus_share($postid);
                        $orderid = '0';
                        $totalearnedpoints = '0';
                        $totalredeempoints = '0';
                        $pointsredeemed = '0';
                        $reasonindetail = '';
                        self::rs_insert_google_plus_share_points($pointsredeemed, $getregularprice, $postid, $checklevel, $currentuserid, $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                    }
                }

                if ($_POST['state'] == 'off') {
                    $getarrayunlikeids[] = $_POST['postid'];
                    $oldunlikeoption = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($_POST['currentuserid'], '_rsgoogleplusunlikes');
                    if (!empty($oldunlikeoption)) {
                        if (!in_array($_POST['postid'], $oldunlikeoption)) {
                            $mergedunlikedata = array_merge((array) $oldunlikeoption, $getarrayunlikeids);
                            RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($_POST['currentuserid'], '_rsgoogleplusunlikes', $mergedunlikedata);
                            $checklevel = self::checklevel_for_google_plus_share($postid);
                            $orderid = '0';
                            $totalearnedpoints = '0';
                            $totalredeempoints = '0';
                            $pointsredeemed = '0';
                            $reasonindetail = '';
                            self::rs_insert_google_plus_share_revised_points($pointsredeemed, $getregularprice, $postid, $checklevel, $currentuserid, $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        }
                    } else {
                        RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($_POST['currentuserid'], '_rsgoogleplusunlikes', $getarrayunlikeids);
                        $checklevel = self::checklevel_for_google_plus_share($postid);
                        $orderid = '0';
                        $totalearnedpoints = '0';
                        $totalredeempoints = '0';
                        $pointsredeemed = '0';
                        $reasonindetail = '';
                        self::rs_insert_google_plus_share_revised_points($pointsredeemed, $getregularprice, $postid, $checklevel, $currentuserid, $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                    }
                }
                echo "Ajax Call Successfully Triggered";
            }
            do_action('fp_reward_point_for_google_plus_share');
            exit();
        }
    }

    /* Function to check the level of the points from where it is awraded. */

    public static function checklevel_for_google_plus_share($postid) {

        //Product Level
        $enablerewards = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_socialrewardsystemcheckboxvalue');
        $gettype = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_social_rewardsystem_options_google');
        $getpoints = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_socialrewardsystempoints_google');
        $getpercent = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_socialrewardsystempercent_google');

        //Category Level
        $categorylist = wp_get_post_terms($postid, 'product_cat');
        $getcount = count($categorylist);
        $term = get_the_terms($postid, 'product_cat');
        if (is_array($term)) {
            foreach ($term as $terms) {
                $termid = $terms->term_id;
                $categorylevel = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'enable_social_reward_system_category');
                $categorylevelrewardtype = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_google_enable_rs_rule');
                $categorylevelrewardpoints = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_google_rs_category_points');
                $categorylevelrewardpercents = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_google_rs_category_percent');
            }
        }

        //Global Level
        $global_enable = get_option('rs_global_social_enable_disable_reward');
        $global_reward_type = get_option('rs_global_social_reward_type_google');
        $global_reward_points = get_option('rs_global_social_google_reward_points');
        $global_reward_percent = get_option('rs_global_social_google_reward_percent');

        if ($enablerewards == 'yes') {
            if ($gettype == '1') {
                if ($getpoints != '') {
                    return '1';
                } else {
                    if ($getcount >= '1') {
                        if (($categorylevel == 'yes') || ($categorylevel != '')) {
                            if ($categorylevelrewardtype == '1') {
                                if ($categorylevelrewardpoints != '') {
                                    return '2';
                                } else {
                                    if ($global_enable == '1') {
                                        if ($global_reward_type == '1') {
                                            if ($global_reward_points != '') {
                                                return '2';
                                            }
                                        } else {
                                            if ($global_reward_type == '2') {
                                                if ($global_reward_percent != '') {
                                                    return '2';
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($categorylevelrewardtype == '2') {
                                    if ($categorylevelrewardpercents != '') {
                                        return '2';
                                    } else {
                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                if ($global_reward_points != '') {
                                                    return '2';
                                                }
                                            } else {
                                                if ($global_reward_type == '2') {
                                                    if ($global_reward_percent != '') {
                                                        return '2';
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($global_enable == '1') {
                                if ($global_reward_type == '1') {
                                    if ($global_reward_points != '') {
                                        return '3';
                                    }
                                } else {
                                    if ($global_reward_type == '2') {
                                        if ($global_reward_percent != '') {
                                            return '3';
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        if ($global_enable == '1') {
                            if ($global_reward_type == '1') {
                                if ($global_reward_points != '') {
                                    return '3';
                                }
                            } else {
                                if ($global_reward_type == '2') {
                                    if ($global_reward_percent != '') {
                                        return '3';
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                if ($getpercent != '') {
                    return '1';
                } else {
                    if ($getcount >= '1') {
                        if (($categorylevel == 'yes') || ($categorylevel != '')) {
                            if ($categorylevelrewardtype == '1') {
                                if ($categorylevelrewardpoints != '') {
                                    return '2';
                                } else {
                                    if ($global_enable == '1') {
                                        if ($global_reward_type == '1') {
                                            if ($global_reward_points != '') {
                                                return '2';
                                            }
                                        } else {
                                            if ($global_reward_type == '2') {
                                                if ($global_reward_percent != '') {
                                                    return '2';
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($categorylevelrewardtype == '2') {
                                    if ($categorylevelrewardpercents != '') {
                                        return '2';
                                    } else {
                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                if ($global_reward_points != '') {
                                                    return '2';
                                                }
                                            } else {
                                                if ($global_reward_type == '2') {
                                                    if ($global_reward_percent != '') {
                                                        return '2';
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($global_enable == '1') {
                                if ($global_reward_type == '1') {
                                    if ($global_reward_points != '') {
                                        return '3';
                                    }
                                } else {
                                    if ($global_reward_type == '2') {
                                        if ($global_reward_percent != '') {
                                            return '3';
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        if ($global_enable == '1') {
                            if ($global_reward_type == '1') {
                                if ($global_reward_points != '') {
                                    return '3';
                                }
                            } else {
                                if ($global_reward_type == '2') {
                                    if ($global_reward_percent != '') {
                                        return '3';
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /* Function to insert the google+1 share points to db. */

    public static function rs_insert_google_plus_share_points($pointsredeemed, $getregularprice, $postid, $level, $currentuserid, $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail) {

        $rewardpoints = array('0');
        $rewardpercents = array('0');

        //Product Level Points and Percent
        $gettype = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_social_rewardsystem_options_google');
        $getpoints = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_socialrewardsystempoints_google');
        $getpercent = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_socialrewardsystempercent_google');
        $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
        
        $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
        $getaverage = $getpercent / 100;
        $getaveragepoints = $getaverage * $getregularprice;
        $pointswithvalue = $getaveragepoints * $pointconversion;
        $rewardpercentsforproductlevel = $pointswithvalue / $pointconversionvalue;

        //Category Level Points and Percent
        $categorylist = wp_get_post_terms($postid, 'product_cat');
        $getcount = count($categorylist);
        $term = get_the_terms($postid, 'product_cat');
        if (is_array($term)) {
            foreach ($term as $terms) {
                $termid = $terms->term_id;
                $categorylevelrewardtype = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_google_enable_rs_rule');
                $categorylevelrewardpoints = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_google_rs_category_points');
                $categorylevelrewardpercents = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_google_rs_category_percent');
                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                
                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                $getaverage = $categorylevelrewardpercents / 100;
                $getaveragepoints = $categorylevelrewardpercents * $getregularprice;
                $pointswithvalue = $getaveragepoints * $pointconversion;
                $rewardpercentsforcategorylevel = $pointswithvalue / $pointconversionvalue;

                //Global Level Points and Percent
                $global_reward_type = get_option('rs_global_social_reward_type_google');
                $global_reward_points = get_option('rs_global_social_google_reward_points');
                $global_reward_percent = get_option('rs_global_social_google_reward_percent');
                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                
                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                $getaverage = $global_reward_percent / 100;
                $getaveragepoints = $global_reward_percent * $getregularprice;
                $pointswithvalue = $getaveragepoints * $pointconversion;
                $rewardpercentsforgloballevel = $pointswithvalue / $pointconversionvalue;

                if ($getcount > 1) {
                    if ($categorylevelrewardpoints == '') {
                        $rewardpoints[] = $global_reward_points;
                    } else {
                        $rewardpoints[] = $categorylevelrewardpoints;
                    }

                    if ($categorylevelrewardpercents == '') {
                        $rewardpercents[] = $rewardpercentsforgloballevel;
                    } else {
                        $rewardpercents[] = $rewardpercentsforcategorylevel;
                    }
                } else {
                    if ($categorylevelrewardpoints == '') {
                        $rewardpoints[] = $global_reward_points;
                    } else {
                        $rewardpoints[] = $categorylevelrewardpoints;
                    }

                    if ($categorylevelrewardpercents == '') {
                        $rewardpercents[] = $rewardpercentsforgloballevel;
                    } else {
                        $rewardpercents[] = $rewardpercentsforcategorylevel;
                    }
                }
            }
        } else {
            $global_reward_type = get_option('rs_global_social_reward_type_google');
            $global_reward_points = get_option('rs_global_social_google_reward_points');
            $global_reward_percent = get_option('rs_global_social_google_reward_percent');
            $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
            
            $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
            $getaverage = $global_reward_percent / 100;
            $getaveragepoints = $global_reward_percent * $getregularprice;
            $pointswithvalue = $getaveragepoints * $pointconversion;
            $rewardpercentsforgloballevel = $pointswithvalue / $pointconversionvalue;
        }

        $getcategorypoints = max($rewardpoints);
        $getcategorypercent = max($rewardpercents);
        $restrictuserpoints = get_option('rs_max_earning_points_for_user');
        $enabledisablemaxpoints = get_option('rs_enable_disable_max_earning_points_for_user');
        $order_id = '0';
        $variationid = '0';
        $refuserid = '0';
        $equredeemamt = '0';
        $pointsredeemed = '0';
        $totalearnedpoints = '0';
        $totalredeempoints = '0';
        $reasonindetail = '';
        $noofdays = get_option('rs_point_to_be_expire');

        if (($noofdays != '0') && ($noofdays != '')) {
            $date = time() + ($noofdays * 24 * 60 * 60);
        } else {
            $date = '999999999999';
        }
        switch ($level) {
            case '1':
                if ($gettype == '1') {
                    if ($enabledisablemaxpoints == 'yes') {
                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            if ($getoldpoints <= $restrictuserpoints) {
                                $totalpointss = $getoldpoints + $getpoints;
                                if ($totalpointss <= $restrictuserpoints) {
                                    $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getpoints);
                                    RSPointExpiry::insert_earning_points($currentuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'RPGPOS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'RPGPOS', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                } else {
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                RSPointExpiry::insert_earning_points($currentuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                RSPointExpiry::record_the_points($currentuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                            }
                        } else {
                            $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getpoints);
                            RSPointExpiry::insert_earning_points($currentuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'RPGPOS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                            $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            RSPointExpiry::record_the_points($currentuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'RPGPOS', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                        }
                    } else {
                        $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getpoints);
                        RSPointExpiry::insert_earning_points($currentuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'RPGPOS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                        RSPointExpiry::record_the_points($currentuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'RPGPOS', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                    }
                } else {

                    if ($enabledisablemaxpoints == 'yes') {
                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            if ($getoldpoints <= $restrictuserpoints) {
                                $totalpointss = $getoldpoints + $rewardpercentsforproductlevel;
                                if ($totalpointss <= $restrictuserpoints) {
                                    $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforproductlevel);
                                    RSPointExpiry::insert_earning_points($currentuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'RPGPOS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'RPGPOS', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                } else {
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                RSPointExpiry::insert_earning_points($currentuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                RSPointExpiry::record_the_points($currentuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                            }
                        } else {
                            $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforproductlevel);
                            RSPointExpiry::insert_earning_points($currentuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'RPGPOS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                            $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            RSPointExpiry::record_the_points($currentuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'RPGPOS', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                        }
                    } else {
                        $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforproductlevel);
                        RSPointExpiry::insert_earning_points($currentuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'RPGPOS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                        RSPointExpiry::record_the_points($currentuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'RPGPOS', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                    }
                }
                break;
            case '2':
                if ($categorylevelrewardtype == '1') {
                    if ($enabledisablemaxpoints == 'yes') {
                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            if ($getoldpoints <= $restrictuserpoints) {
                                $totalpointss = $getoldpoints + $getcategorypoints;
                                if ($totalpointss <= $restrictuserpoints) {
                                    $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypoints);
                                    RSPointExpiry::insert_earning_points($currentuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'RPGPOS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'RPGPOS', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                } else {
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                RSPointExpiry::insert_earning_points($currentuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                RSPointExpiry::record_the_points($currentuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                            }
                        } else {
                            $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypoints);
                            RSPointExpiry::insert_earning_points($currentuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'RPGPOS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                            $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            RSPointExpiry::record_the_points($currentuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'RPGPOS', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                        }
                    } else {
                        $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypoints);
                        RSPointExpiry::insert_earning_points($currentuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'RPGPOS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                        RSPointExpiry::record_the_points($currentuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'RPGPOS', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                    }
                } else {
                    if ($enabledisablemaxpoints == 'yes') {
                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            if ($getoldpoints <= $restrictuserpoints) {
                                $totalpointss = $getoldpoints + $getcategorypercent;
                                if ($totalpointss <= $restrictuserpoints) {
                                    $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypercent);
                                    RSPointExpiry::insert_earning_points($currentuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'RPGPOS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'RPGPOS', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                } else {
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                RSPointExpiry::insert_earning_points($currentuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                RSPointExpiry::record_the_points($currentuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                            }
                        } else {
                            $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypercent);
                            RSPointExpiry::insert_earning_points($currentuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'RPGPOS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                            $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            RSPointExpiry::record_the_points($currentuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'RPGPOS', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                        }
                    } else {
                        $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypercent);
                        RSPointExpiry::insert_earning_points($currentuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'RPGPOS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                        RSPointExpiry::record_the_points($currentuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'RPGPOS', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                    }
                }
                break;
            case '3':
                if ($global_reward_type == '1') {
                    if ($enabledisablemaxpoints == 'yes') {
                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            if ($getoldpoints <= $restrictuserpoints) {
                                $totalpointss = $getoldpoints + $global_reward_points;
                                if ($totalpointss <= $restrictuserpoints) {
                                    $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $global_reward_points);
                                    RSPointExpiry::insert_earning_points($currentuserid, $global_rewardpointss, $pointsredeemed, $date, 'RPGPOS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $global_rewardpointss, $pointsredeemed, $date, 'RPGPOS', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                } else {
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                RSPointExpiry::insert_earning_points($currentuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                RSPointExpiry::record_the_points($currentuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                            }
                        } else {
                            $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $global_reward_points);
                            RSPointExpiry::insert_earning_points($currentuserid, $global_rewardpointss, $pointsredeemed, $date, 'RPGPOS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                            $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            RSPointExpiry::record_the_points($currentuserid, $global_rewardpointss, $pointsredeemed, $date, 'RPGPOS', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                        }
                    } else {
                        $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $global_reward_points);
                        RSPointExpiry::insert_earning_points($currentuserid, $global_rewardpointss, $pointsredeemed, $date, 'RPGPOS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                        RSPointExpiry::record_the_points($currentuserid, $global_rewardpointss, $pointsredeemed, $date, 'RPGPOS', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                    }
                } else {
                    if ($enabledisablemaxpoints == 'yes') {
                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            if ($getoldpoints <= $restrictuserpoints) {
                                $totalpointss = $getoldpoints + $rewardpercentsforgloballevel;
                                if ($totalpointss <= $restrictuserpoints) {
                                    $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforgloballevel);
                                    RSPointExpiry::insert_earning_points($currentuserid, $global_rewardpercents, $pointsredeemed, $date, 'RPGPOS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $global_rewardpercents, $pointsredeemed, $date, 'RPGPOS', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                } else {
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                RSPointExpiry::insert_earning_points($currentuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                RSPointExpiry::record_the_points($currentuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                            }
                        } else {
                            $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforgloballevel);
                            RSPointExpiry::insert_earning_points($currentuserid, $global_rewardpercents, $pointsredeemed, $date, 'RPGPOS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                            $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            RSPointExpiry::record_the_points($currentuserid, $global_rewardpercents, $pointsredeemed, $date, 'RPGPOS', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                        }
                    } else {
                        $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforgloballevel);
                        RSPointExpiry::insert_earning_points($currentuserid, $global_rewardpercents, $pointsredeemed, $date, 'RPGPOS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                        RSPointExpiry::record_the_points($currentuserid, $global_rewardpercents, $pointsredeemed, $date, 'RPGPOS', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                    }
                }
                break;
        }
    }

    public static function rs_insert_google_plus_share_revised_points($pointsredeemed, $getregularprice, $postid, $level, $currentuserid, $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail) {

        $rewardpoints = array('0');
        $rewardpercents = array('0');

        //Product Level Points and Percent
        $gettype = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_social_rewardsystem_options_google');
        $getpoints = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_socialrewardsystempoints_google');
        $getpercent = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_socialrewardsystempercent_google');
        $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
        
        $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
        $getaverage = $getpercent / 100;
        $getaveragepoints = $getaverage * $getregularprice;
        $pointswithvalue = $getaveragepoints * $pointconversion;
        $rewardpercentsforproductlevel = $pointswithvalue / $pointconversionvalue;

        //Category Level Points and Percent
        $categorylist = wp_get_post_terms($postid, 'product_cat');
        $getcount = count($categorylist);
        $term = get_the_terms($postid, 'product_cat');
        if (is_array($term)) {
            foreach ($term as $terms) {
                $termid = $terms->term_id;
                $categorylevelrewardtype = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_google_enable_rs_rule');
                $categorylevelrewardpoints = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_google_rs_category_points');
                $categorylevelrewardpercents = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_google_rs_category_percent');
                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                ;
                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                $getaverage = $categorylevelrewardpercents / 100;
                $getaveragepoints = $categorylevelrewardpercents * $getregularprice;
                $pointswithvalue = $getaveragepoints * $pointconversion;
                $rewardpercentsforcategorylevel = $pointswithvalue / $pointconversionvalue;

                //Global Level Points and Percent
                $global_reward_type = get_option('rs_global_social_reward_type_google');
                $global_reward_points = get_option('rs_global_social_google_reward_points');
                $global_reward_percent = get_option('rs_global_social_google_reward_percent');
                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                
                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                $getaverage = $global_reward_percent / 100;
                $getaveragepoints = $global_reward_percent * $getregularprice;
                $pointswithvalue = $getaveragepoints * $pointconversion;
                $rewardpercentsforgloballevel = $pointswithvalue / $pointconversionvalue;

                if ($getcount > 1) {
                    if ($categorylevelrewardpoints == '') {
                        $rewardpoints[] = $global_reward_points;
                    } else {
                        $rewardpoints[] = $categorylevelrewardpoints;
                    }

                    if ($categorylevelrewardpercents == '') {
                        $rewardpercents[] = $rewardpercentsforgloballevel;
                    } else {
                        $rewardpercents[] = $rewardpercentsforcategorylevel;
                    }
                } else {
                    if ($categorylevelrewardpoints == '') {
                        $rewardpoints[] = $global_reward_points;
                    } else {
                        $rewardpoints[] = $categorylevelrewardpoints;
                    }

                    if ($categorylevelrewardpercents == '') {
                        $rewardpercents[] = $rewardpercentsforgloballevel;
                    } else {
                        $rewardpercents[] = $rewardpercentsforcategorylevel;
                    }
                }
            }
        } else {
            $global_reward_type = get_option('rs_global_social_reward_type_google');
            $global_reward_points = get_option('rs_global_social_google_reward_points');
            $global_reward_percent = get_option('rs_global_social_google_reward_percent');
            $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
            
            $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
            $getaverage = $global_reward_percent / 100;
            $getaveragepoints = $global_reward_percent * $getregularprice;
            $pointswithvalue = $getaveragepoints * $pointconversion;
            $rewardpercentsforgloballevel = $pointswithvalue / $pointconversionvalue;
        }

        $getcategorypoints = max($rewardpoints);
        $getcategorypercent = max($rewardpercents);

        $noofdays = get_option('rs_point_to_be_expire');

        if (($noofdays != '0') && ($noofdays != '')) {
            $date = time() + ($noofdays * 24 * 60 * 60);
        } else {
            $date = '999999999999';
        }

        switch ($level) {
            case '1':
                if ($gettype == '1') {
                    $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getpoints);
                    RSPointExpiry::insert_earning_points($currentuserid, $pointsredeemed, $productlevelrewardpointss, $date, 'RVPFRPGPOS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                    $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                    $order_id = '0';
                    $variationid = '0';
                    $refuserid = '0';
                    $equredeemamt = '0';
                    $pointsredeemed = '0';
                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                    RSPointExpiry::record_the_points($currentuserid, $pointsredeemed, $productlevelrewardpointss, $date, 'RVPFRPGPOS', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                } else {
                    $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforproductlevel);
                    RSPointExpiry::insert_earning_points($currentuserid, $pointsredeemed, $productlevelrewardpercentss, $date, 'RVPFRPGPOS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                    $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                    $order_id = '0';
                    $variationid = '0';
                    $refuserid = '0';
                    $equredeemamt = '0';
                    $pointsredeemed = '0';
                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                    RSPointExpiry::record_the_points($currentuserid, $pointsredeemed, $productlevelrewardpercentss, $date, 'RVPFRPGPOS', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                }
                break;
            case '2':
                if ($categorylevelrewardtype == '1') {
                    $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypoints);
                    RSPointExpiry::insert_earning_points($currentuserid, $pointsredeemed, $categorylevelrewardpointss, $date, 'RVPFRPGPOS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                    $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                    $order_id = '0';
                    $variationid = '0';
                    $refuserid = '0';
                    $equredeemamt = '0';
                    $pointsredeemed = '0';
                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                    RSPointExpiry::record_the_points($currentuserid, $pointsredeemed, $categorylevelrewardpointss, $date, 'RVPFRPGPOS', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                } else {
                    $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypercent);
                    RSPointExpiry::insert_earning_points($currentuserid, $pointsredeemed, $categorylevelrewardpercents, $date, 'RVPFRPGPOS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                    $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                    $order_id = '0';
                    $variationid = '0';
                    $refuserid = '0';
                    $equredeemamt = '0';
                    $pointsredeemed = '0';
                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                    RSPointExpiry::record_the_points($currentuserid, $pointsredeemed, $categorylevelrewardpercents, $date, 'RVPFRPGPOS', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                }
                break;
            case '3':
                if ($global_reward_type == '1') {
                    $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $global_reward_points);
                    RSPointExpiry::insert_earning_points($currentuserid, $pointsredeemed, $global_rewardpointss, $date, 'RVPFRPGPOS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                    $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                    $order_id = '0';
                    $variationid = '0';
                    $refuserid = '0';
                    $equredeemamt = '0';
                    $pointsredeemed = '0';
                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                    RSPointExpiry::record_the_points($currentuserid, $pointsredeemed, $global_rewardpointss, $date, 'RVPFRPGPOS', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                } else {
                    $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforgloballevel);
                    RSPointExpiry::insert_earning_points($currentuserid, $pointsredeemed, $global_rewardpercents, $date, 'RVPFRPGPOS', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                    $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                    $order_id = '0';
                    $variationid = '0';
                    $refuserid = '0';
                    $equredeemamt = '0';
                    $pointsredeemed = '0';
                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                    RSPointExpiry::record_the_points($currentuserid, $pointsredeemed, $global_rewardpercents, $date, 'RVPFRPGPOS', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                }
                break;
        }
    }

    /* Function to insert VK.Com like reward points */

    public static function update_reward_points_for_vk_like() {
        $userid = get_current_user_id();
        global $wpdb;
        $table_name = $wpdb->prefix . 'rspointexpiry';
        $banning_type = FPRewardSystem::check_banning_type($userid);
        if ($banning_type != 'earningonly' && $banning_type != 'both') {


            if (isset($_POST['state']) && ($_POST['postid']) && ($_POST['currentuserid'])) {
                if (get_option('rs_set_price_to_calculate_rewardpoints_by_percentage') == '1') {
                    $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($_POST['postid'], '_regular_price');
                    if ($getregularprice == '') {
                        $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($_POST['postid'], '_price');
                    }
                } else {
                    $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($_POST['postid'], '_price');
                    if ($getregularprice == '') {
                        $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($_POST['postid'], '_regular_price');
                    }
                }
                $postid = $_POST['postid'];
                $currentuserid = $_POST['currentuserid'];
                $getarrayids[] = $_POST['postid'];
                $oldoption = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($_POST['currentuserid'], '_rsvklike');
                if (!empty($oldoption)) {
                    if (!in_array($_POST['postid'], $oldoption)) {
                        $mergedata = array_merge((array) $oldoption, $getarrayids);
                        RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($_POST['currentuserid'], '_rsvklike', $mergedata);
                        if ($_POST['state'] == 'on') {
                            $checklevel = self::checklevel_for_vk_like($postid);
                            $orderid = '0';
                            $totalearnedpoints = '0';
                            $totalredeempoints = '0';
                            $reasonindetail = '';
                            $pointsredeemed = '0';
                            self::rs_insert_vk_like_points($pointsredeemed, $getregularprice, $postid, $orderid, $checklevel, $currentuserid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        }
                    } else {
                        _e("You have already liked this post on VK.Com", 'rewardsystem');
                    }
                } else {
                    RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($_POST['currentuserid'], '_rsvklike', $getarrayids);
                    if ($_POST['state'] == 'on') {
                        $checklevel = self::checklevel_for_vk_like($postid);
                        $orderid = '0';
                        $totalearnedpoints = '0';
                        $totalredeempoints = '0';
                        $pointsredeemed = '0';
                        $reasonindetail = '';
                        self::rs_insert_vk_like_points($pointsredeemed, $getregularprice, $postid, $checklevel, $currentuserid, $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                    }
                }

                if ($_POST['state'] == 'off') {
                    $getarrayunlikeids[] = $_POST['postid'];
                    $oldunlikeoption = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($_POST['currentuserid'], '_rsvkunlikes');
                    if (!empty($oldunlikeoption)) {
                        if (!in_array($_POST['postid'], $oldunlikeoption)) {
                            $mergedunlikedata = array_merge((array) $oldunlikeoption, $getarrayunlikeids);
                            RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($_POST['currentuserid'], '_rsvkunlikes', $mergedunlikedata);
                            $checklevel = self::checklevel_for_vk_like($postid);
                            $orderid = '0';
                            $totalearnedpoints = '0';
                            $totalredeempoints = '0';
                            $reasonindetail = '';
                            $pointsredeemed = '0';
                            self::rs_insert_vk_like_revised_points($pointsredeemed, $getregularprice, $postid, $orderid, $checklevel, $currentuserid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        }
                    } else {
                        RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($_POST['currentuserid'], '_rsvkunlikes', $getarrayunlikeids);
                        $checklevel = self::checklevel_for_vk_like($postid);
                        $orderid = '0';
                        $totalearnedpoints = '0';
                        $totalredeempoints = '0';
                        $reasonindetail = '';
                        $pointsredeemed = '0';
                        self::rs_insert_vk_like_revised_points($pointsredeemed, $getregularprice, $postid, $orderid, $checklevel, $currentuserid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                    }
                }
                echo "Ajax Call Successfully Triggered";
            }
            do_action('fp_reward_point_for_vk_like');
            exit();
        }
    }

    /* Function to check the level of the points from where it is awraded. */

    public static function checklevel_for_vk_like($postid) {

        //Product Level
        $enablerewards = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_socialrewardsystemcheckboxvalue');
        $gettype = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_social_rewardsystem_options_vk');
        $getpoints = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_socialrewardsystempoints_vk');
        $getpercent = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_socialrewardsystempercent_vk');

        //Category Level
        $categorylist = wp_get_post_terms($postid, 'product_cat');
        $getcount = count($categorylist);
        $term = get_the_terms($postid, 'product_cat');
        if (is_array($term)) {
            foreach ($term as $terms) {
                $termid = $terms->term_id;
                $categorylevel = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'enable_social_reward_system_category');
                $categorylevelrewardtype = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_vk_enable_rs_rule');
                $categorylevelrewardpoints = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_vk_rs_category_points');
                $categorylevelrewardpercents = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_vk_rs_category_percent');
            }
        }

        //Global Level
        $global_enable = get_option('rs_global_social_enable_disable_reward');
        $global_reward_type = get_option('rs_global_social_reward_type_vk');
        $global_reward_points = get_option('rs_global_social_vk_reward_points');
        $global_reward_percent = get_option('rs_global_social_vk_reward_percent');

        if ($enablerewards == 'yes') {
            if ($gettype == '1') {
                if ($getpoints != '') {
                    return '1';
                } else {
                    if ($getcount >= '1') {
                        if (($categorylevel == 'yes') || ($categorylevel != '')) {
                            if ($categorylevelrewardtype == '1') {
                                if ($categorylevelrewardpoints != '') {
                                    return '2';
                                } else {
                                    if ($global_enable == '1') {
                                        if ($global_reward_type == '1') {
                                            if ($global_reward_points != '') {
                                                return '2';
                                            }
                                        } else {
                                            if ($global_reward_type == '2') {
                                                if ($global_reward_percent != '') {
                                                    return '2';
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($categorylevelrewardtype == '2') {
                                    if ($categorylevelrewardpercents != '') {
                                        return '2';
                                    } else {
                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                if ($global_reward_points != '') {
                                                    return '2';
                                                }
                                            } else {
                                                if ($global_reward_type == '2') {
                                                    if ($global_reward_percent != '') {
                                                        return '2';
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($global_enable == '1') {
                                if ($global_reward_type == '1') {
                                    if ($global_reward_points != '') {
                                        return '3';
                                    }
                                } else {
                                    if ($global_reward_type == '2') {
                                        if ($global_reward_percent != '') {
                                            return '3';
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        if ($global_enable == '1') {
                            if ($global_reward_type == '1') {
                                if ($global_reward_points != '') {
                                    return '3';
                                }
                            } else {
                                if ($global_reward_type == '2') {
                                    if ($global_reward_percent != '') {
                                        return '3';
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                if ($getpercent != '') {
                    return '1';
                } else {
                    if ($getcount >= '1') {
                        if (($categorylevel == 'yes') || ($categorylevel != '')) {
                            if ($categorylevelrewardtype == '1') {
                                if ($categorylevelrewardpoints != '') {
                                    return '2';
                                } else {
                                    if ($global_enable == '1') {
                                        if ($global_reward_type == '1') {
                                            if ($global_reward_points != '') {
                                                return '2';
                                            }
                                        } else {
                                            if ($global_reward_type == '2') {
                                                if ($global_reward_percent != '') {
                                                    return '2';
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($categorylevelrewardtype == '2') {
                                    if ($categorylevelrewardpercents != '') {
                                        return '2';
                                    } else {
                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                if ($global_reward_points != '') {
                                                    return '2';
                                                }
                                            } else {
                                                if ($global_reward_type == '2') {
                                                    if ($global_reward_percent != '') {
                                                        return '2';
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($global_enable == '1') {
                                if ($global_reward_type == '1') {
                                    if ($global_reward_points != '') {
                                        return '3';
                                    }
                                } else {
                                    if ($global_reward_type == '2') {
                                        if ($global_reward_percent != '') {
                                            return '3';
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        if ($global_enable == '1') {
                            if ($global_reward_type == '1') {
                                if ($global_reward_points != '') {
                                    return '3';
                                }
                            } else {
                                if ($global_reward_type == '2') {
                                    if ($global_reward_percent != '') {
                                        return '3';
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /* Function to insert the vk like points to db. */

    public static function rs_insert_vk_like_points($pointsredeemed, $getregularprice, $postid, $orderid, $level, $currentuserid, $totalearnedpoints, $totalredeempoints, $reasonindetail) {

        $rewardpoints = array('0');
        $rewardpercents = array('0');

        //Product Level Points and Percent
        $gettype = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_social_rewardsystem_options_vk');
        $getpoints = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_socialrewardsystempoints_vk');
        $getpercent = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_socialrewardsystempercent_vk');
        $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
        
        $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
        $getaverage = $getpercent / 100;
        $getaveragepoints = $getaverage * $getregularprice;
        $pointswithvalue = $getaveragepoints * $pointconversion;
        $rewardpercentsforproductlevel = $pointswithvalue / $pointconversionvalue;

        //Category Level Points and Percent
        $categorylist = wp_get_post_terms($postid, 'product_cat');
        $getcount = count($categorylist);
        $term = get_the_terms($postid, 'product_cat');
        if (is_array($term)) {
            foreach ($term as $terms) {
                $termid = $terms->term_id;
                $categorylevelrewardtype = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_vk_enable_rs_rule');
                $categorylevelrewardpoints = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_vk_rs_category_points');
                $categorylevelrewardpercents = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_vk_rs_category_percent');
                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                
                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                $getaverage = $categorylevelrewardpercents / 100;
                $getaveragepoints = $categorylevelrewardpercents * $getregularprice;
                $pointswithvalue = $getaveragepoints * $pointconversion;
                $rewardpercentsforcategorylevel = $pointswithvalue / $pointconversionvalue;

                //Global Level Points and Percent
                $global_reward_type = get_option('rs_global_social_reward_type_vk');
                $global_reward_points = get_option('rs_global_social_vk_reward_points');
                $global_reward_percent = get_option('rs_global_social_vk_reward_percent');
                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                
                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                $getaverage = $global_reward_percent / 100;
                $getaveragepoints = $global_reward_percent * $getregularprice;
                $pointswithvalue = $getaveragepoints * $pointconversion;
                $rewardpercentsforgloballevel = $pointswithvalue / $pointconversionvalue;

                if ($getcount > 1) {
                    if ($categorylevelrewardpoints == '') {
                        $rewardpoints[] = $global_reward_points;
                    } else {
                        $rewardpoints[] = $categorylevelrewardpoints;
                    }

                    if ($categorylevelrewardpercents == '') {
                        $rewardpercents[] = $rewardpercentsforgloballevel;
                    } else {
                        $rewardpercents[] = $rewardpercentsforcategorylevel;
                    }
                } else {
                    if ($categorylevelrewardpoints == '') {
                        $rewardpoints[] = $global_reward_points;
                    } else {
                        $rewardpoints[] = $categorylevelrewardpoints;
                    }

                    if ($categorylevelrewardpercents == '') {
                        $rewardpercents[] = $rewardpercentsforgloballevel;
                    } else {
                        $rewardpercents[] = $rewardpercentsforcategorylevel;
                    }
                }
            }
        } else {
            $global_reward_type = get_option('rs_global_social_reward_type_vk');
            $global_reward_points = get_option('rs_global_social_vk_reward_points');
            $global_reward_percent = get_option('rs_global_social_vk_reward_percent');
            $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
            
            $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
            $getaverage = $global_reward_percent / 100;
            $getaveragepoints = $global_reward_percent * $getregularprice;
            $pointswithvalue = $getaveragepoints * $pointconversion;
            $rewardpercentsforgloballevel = $pointswithvalue / $pointconversionvalue;
        }
        $getcategorypoints = max($rewardpoints);
        $getcategorypercent = max($rewardpercents);
        $restrictuserpoints = get_option('rs_max_earning_points_for_user');
        $enabledisablemaxpoints = get_option('rs_enable_disable_max_earning_points_for_user');
        $totalredeempoints = '0';
        $totalearnedpoints = '0';
        $refuserid = '0';
        $variationid = '0';
        $reasonindetail = '';
        $noofdays = get_option('rs_point_to_be_expire');

        if (($noofdays != '0') && ($noofdays != '')) {
            $date = time() + ($noofdays * 24 * 60 * 60);
        } else {
            $date = '999999999999';
        }
        switch ($level) {
            case '1':
                if ($gettype == '1') {
                    if ($enabledisablemaxpoints == 'yes') {
                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            if ($getoldpoints <= $restrictuserpoints) {
                                $totalpointss = $getoldpoints + $getpoints;
                                if ($totalpointss <= $restrictuserpoints) {
                                    $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getpoints);
                                    RSPointExpiry::insert_earning_points($currentuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'RPVL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $productlevelrewardpointss, '0', $date, 'RPVL', $equearnamt, '0', '0', $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                } else {
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                RSPointExpiry::insert_earning_points($currentuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                RSPointExpiry::record_the_points($currentuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                            }
                        } else {
                            $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getpoints);
                            RSPointExpiry::insert_earning_points($currentuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'RPVL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                            $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            RSPointExpiry::record_the_points($currentuserid, $productlevelrewardpointss, '0', $date, 'RPVL', $equearnamt, '0', '0', $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                        }
                    } else {
                        $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getpoints);
                        RSPointExpiry::insert_earning_points($currentuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'RPVL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                        RSPointExpiry::record_the_points($currentuserid, $productlevelrewardpointss, '0', $date, 'RPVL', $equearnamt, '0', '0', $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                    }
                } else {
                    if ($enabledisablemaxpoints == 'yes') {
                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            if ($getoldpoints <= $restrictuserpoints) {
                                $totalpointss = $getoldpoints + $rewardpercentsforproductlevel;
                                if ($totalpointss <= $restrictuserpoints) {
                                    $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforproductlevel);
                                    RSPointExpiry::insert_earning_points($currentuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'RPVL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $productlevelrewardpercentss, '0', $date, 'RPVL', $equearnamt, '0', '0', $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                } else {
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                RSPointExpiry::insert_earning_points($currentuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                RSPointExpiry::record_the_points($currentuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                            }
                        } else {
                            $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforproductlevel);
                            RSPointExpiry::insert_earning_points($currentuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'RPVL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                            $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            RSPointExpiry::record_the_points($currentuserid, $productlevelrewardpercentss, '0', $date, 'RPVL', $equearnamt, '0', '0', $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                        }
                    } else {
                        $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforproductlevel);
                        RSPointExpiry::insert_earning_points($currentuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'RPVL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                        RSPointExpiry::record_the_points($currentuserid, $productlevelrewardpercentss, '0', $date, 'RPVL', $equearnamt, '0', '0', $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                    }
                }
                break;
            case '2':
                if ($categorylevelrewardtype == '1') {
                    if ($enabledisablemaxpoints == 'yes') {
                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            if ($getoldpoints <= $restrictuserpoints) {
                                $totalpointss = $getoldpoints + $getcategorypoints;
                                if ($totalpointss <= $restrictuserpoints) {
                                    $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypoints);
                                    RSPointExpiry::insert_earning_points($currentuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'RPVL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $categorylevelrewardpointss, '0', $date, 'RPVL', $equearnamt, '0', '0', $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                } else {
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                RSPointExpiry::insert_earning_points($currentuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                RSPointExpiry::record_the_points($currentuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                            }
                        } else {
                            $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypoints);
                            RSPointExpiry::insert_earning_points($currentuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'RPVL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                            $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            RSPointExpiry::record_the_points($currentuserid, $categorylevelrewardpointss, '0', $date, 'RPVL', $equearnamt, '0', '0', $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                        }
                    } else {
                        $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypoints);
                        RSPointExpiry::insert_earning_points($currentuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'RPVL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                        RSPointExpiry::record_the_points($currentuserid, $categorylevelrewardpointss, '0', $date, 'RPVL', $equearnamt, '0', '0', $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                    }
                } else {
                    if ($enabledisablemaxpoints == 'yes') {
                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            if ($getoldpoints <= $restrictuserpoints) {
                                $totalpointss = $getoldpoints + $getcategorypercent;
                                if ($totalpointss <= $restrictuserpoints) {
                                    $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypercent);
                                    RSPointExpiry::insert_earning_points($currentuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'RPVL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $categorylevelrewardpercents, '0', $date, 'RPVL', $equearnamt, '0', '0', $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                } else {
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                RSPointExpiry::insert_earning_points($currentuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                RSPointExpiry::record_the_points($currentuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                            }
                        } else {
                            $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypercent);
                            RSPointExpiry::insert_earning_points($currentuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'RPVL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                            $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            RSPointExpiry::record_the_points($currentuserid, $categorylevelrewardpercents, '0', $date, 'RPVL', $equearnamt, '0', '0', $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                        }
                    } else {
                        $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypercent);
                        RSPointExpiry::insert_earning_points($currentuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'RPVL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                        RSPointExpiry::record_the_points($currentuserid, $categorylevelrewardpercents, '0', $date, 'RPVL', $equearnamt, '0', '0', $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                    }
                }
                break;
            case '3':
                if ($global_reward_type == '1') {
                    if ($enabledisablemaxpoints == 'yes') {
                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            if ($getoldpoints <= $restrictuserpoints) {
                                $totalpointss = $getoldpoints + $global_reward_points;
                                if ($totalpointss <= $restrictuserpoints) {
                                    $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $global_reward_points);
                                    RSPointExpiry::insert_earning_points($currentuserid, $global_rewardpointss, $pointsredeemed, $date, 'RPVL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $global_rewardpointss, '0', $date, 'RPVL', $equearnamt, '0', '0', $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                } else {
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                RSPointExpiry::insert_earning_points($currentuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                RSPointExpiry::record_the_points($currentuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                            }
                        } else {
                            $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $global_reward_points);
                            RSPointExpiry::insert_earning_points($currentuserid, $global_rewardpointss, $pointsredeemed, $date, 'RPVL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                            $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            RSPointExpiry::record_the_points($currentuserid, $global_rewardpointss, '0', $date, 'RPVL', $equearnamt, '0', '0', $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                        }
                    } else {
                        $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $global_reward_points);
                        RSPointExpiry::insert_earning_points($currentuserid, $global_rewardpointss, $pointsredeemed, $date, 'RPVL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                        RSPointExpiry::record_the_points($currentuserid, $global_rewardpointss, '0', $date, 'RPVL', $equearnamt, '0', '0', $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                    }
                } else {
                    if ($enabledisablemaxpoints == 'yes') {
                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            if ($getoldpoints <= $restrictuserpoints) {
                                $totalpointss = $getoldpoints + $rewardpercentsforgloballevel;
                                if ($totalpointss <= $restrictuserpoints) {
                                    $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforgloballevel);
                                    RSPointExpiry::insert_earning_points($currentuserid, $global_rewardpercents, $pointsredeemed, $date, 'RPVL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $global_rewardpercents, '0', $date, 'RPVL', $equearnamt, '0', '0', $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                } else {
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                    RSPointExpiry::record_the_points($currentuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                RSPointExpiry::insert_earning_points($currentuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                                RSPointExpiry::record_the_points($currentuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                            }
                        } else {
                            $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforgloballevel);
                            RSPointExpiry::insert_earning_points($currentuserid, $global_rewardpercents, $pointsredeemed, $date, 'RPVL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                            $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                            RSPointExpiry::record_the_points($currentuserid, $global_rewardpercents, '0', $date, 'RPVL', $equearnamt, '0', '0', $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                        }
                    } else {
                        $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforgloballevel);
                        RSPointExpiry::insert_earning_points($currentuserid, $global_rewardpercents, $pointsredeemed, $date, 'RPVL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                        $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                        RSPointExpiry::record_the_points($currentuserid, $global_rewardpercents, '0', $date, 'RPVL', $equearnamt, '0', '0', $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                    }
                }
                break;
        }
    }

    public static function rs_insert_vk_like_revised_points($pointsredeemed, $getregularprice, $postid, $orderid, $level, $currentuserid, $totalearnedpoints, $totalredeempoints, $reasonindetail) {

        $rewardpoints = array('0');
        $rewardpercents = array('0');

        //Product Level Points and Percent
        $gettype = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_social_rewardsystem_options_vk');
        $getpoints = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_socialrewardsystempoints_vk');
        $getpercent = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($postid, '_socialrewardsystempercent_vk');
        $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
       
        $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
        $getaverage = $getpercent / 100;
        $getaveragepoints = $getaverage * $getregularprice;
        $pointswithvalue = $getaveragepoints * $pointconversion;
        $rewardpercentsforproductlevel = $pointswithvalue / $pointconversionvalue;

        //Category Level Points and Percent
        $categorylist = wp_get_post_terms($postid, 'product_cat');
        $getcount = count($categorylist);
        $term = get_the_terms($postid, 'product_cat');
        if (is_array($term)) {
            foreach ($term as $terms) {
                $termid = $terms->term_id;
                $categorylevelrewardtype = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_vk_enable_rs_rule');
                $categorylevelrewardpoints = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_vk_rs_category_points');
                $categorylevelrewardpercents = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'social_vk_rs_category_percent');
                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                
                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                $getaverage = $categorylevelrewardpercents / 100;
                $getaveragepoints = $categorylevelrewardpercents * $getregularprice;
                $pointswithvalue = $getaveragepoints * $pointconversion;
                $rewardpercentsforcategorylevel = $pointswithvalue / $pointconversionvalue;

                //Global Level Points and Percent
                $global_reward_type = get_option('rs_global_social_reward_type_vk');
                $global_reward_points = get_option('rs_global_social_vk_reward_points');
                $global_reward_percent = get_option('rs_global_social_vk_reward_percent');
                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                
                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                $getaverage = $global_reward_percent / 100;
                $getaveragepoints = $global_reward_percent * $getregularprice;
                $pointswithvalue = $getaveragepoints * $pointconversion;
                $rewardpercentsforgloballevel = $pointswithvalue / $pointconversionvalue;

                if ($getcount > 1) {
                    if ($categorylevelrewardpoints == '') {
                        $rewardpoints[] = $global_reward_points;
                    } else {
                        $rewardpoints[] = $categorylevelrewardpoints;
                    }

                    if ($categorylevelrewardpercents == '') {
                        $rewardpercents[] = $rewardpercentsforgloballevel;
                    } else {
                        $rewardpercents[] = $rewardpercentsforcategorylevel;
                    }
                } else {
                    if ($categorylevelrewardpoints == '') {
                        $rewardpoints[] = $global_reward_points;
                    } else {
                        $rewardpoints[] = $categorylevelrewardpoints;
                    }

                    if ($categorylevelrewardpercents == '') {
                        $rewardpercents[] = $rewardpercentsforgloballevel;
                    } else {
                        $rewardpercents[] = $rewardpercentsforcategorylevel;
                    }
                }
            }
        } else {
            $global_reward_type = get_option('rs_global_social_reward_type_vk');
            $global_reward_points = get_option('rs_global_social_vk_reward_points');
            $global_reward_percent = get_option('rs_global_social_vk_reward_percent');
            $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
            
            $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
            $getaverage = $global_reward_percent / 100;
            $getaveragepoints = $global_reward_percent * $getregularprice;
            $pointswithvalue = $getaveragepoints * $pointconversion;
            $rewardpercentsforgloballevel = $pointswithvalue / $pointconversionvalue;
        }
        $getcategorypoints = max($rewardpoints);
        $getcategorypercent = max($rewardpercents);

        $noofdays = get_option('rs_point_to_be_expire');

        if (($noofdays != '0') && ($noofdays != '')) {
            $date = time() + ($noofdays * 24 * 60 * 60);
        } else {
            $date = '999999999999';
        }

        switch ($level) {
            case '1':
                if ($gettype == '1') {
                    $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getpoints);
                    $totalearnedpoints = $productlevelrewardpointss;
                    $totalredeempoints = '0';
                    $reasonindetail = '';
                    RSPointExpiry::insert_earning_points($currentuserid, $pointsredeemed, $productlevelrewardpointss, $date, 'RVPFRPVL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                    $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                    $refuserid = '0';
                    $variationid = '0';
                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                    RSPointExpiry::record_the_points($currentuserid, '0', $productlevelrewardpointss, $date, 'RVPFRPVL', $equearnamt, '0', '0', $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                } else {
                    $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforproductlevel);
                    $totalearnedpoints = $productlevelrewardpercentss;
                    $totalredeempoints = '0';
                    $reasonindetail = '';
                    RSPointExpiry::insert_earning_points($currentuserid, $pointsredeemed, $productlevelrewardpercentss, $date, 'RVPFRPVL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                    $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                    $refuserid = '0';
                    $variationid = '0';
                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                    RSPointExpiry::record_the_points($currentuserid, '0', $productlevelrewardpercentss, $date, 'RVPFRPVL', $equearnamt, '0', '0', $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                }
                break;
            case '2':
                if ($categorylevelrewardtype == '1') {
                    $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypoints);
                    $totalearnedpoints = $categorylevelrewardpointss;
                    $totalredeempoints = '0';
                    $reasonindetail = '';
                    $refuserid = '0';
                    $variationid = '0';
                    RSPointExpiry::insert_earning_points($currentuserid, $pointsredeemed, $categorylevelrewardpointss, $date, 'RVPFRPVL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                    $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                    RSPointExpiry::record_the_points($currentuserid, '0', $categorylevelrewardpointss, $date, 'RVPFRPVL', $equearnamt, '0', '0', $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                } else {
                    $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $getcategorypercent);
                    $totalearnedpoints = $categorylevelrewardpercents;
                    $totalredeempoints = '0';
                    $reasonindetail = '';
                    $refuserid = '0';
                    $variationid = '0';
                    RSPointExpiry::insert_earning_points($currentuserid, $pointsredeemed, $categorylevelrewardpercents, $date, 'RVPFRPVL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                    $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                    RSPointExpiry::record_the_points($currentuserid, '0', $categorylevelrewardpercents, $date, 'RVPFRPVL', $equearnamt, '0', '0', $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                }
                break;
            case '3':
                if ($global_reward_type == '1') {
                    $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($currentuserid, $global_reward_points);
                    $totalearnedpoints = $global_rewardpointss;
                    $totalredeempoints = '0';
                    $reasonindetail = '';
                    $refuserid = '0';
                    $variationid = '0';
                    RSPointExpiry::insert_earning_points($currentuserid, $pointsredeemed, $global_rewardpointss, $date, 'RVPFRPVL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                    $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                    RSPointExpiry::record_the_points($currentuserid, '0', $global_rewardpointss, $date, 'RVPFRPVL', $equearnamt, '0', '0', $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                } else {
                    $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($currentuserid, $rewardpercentsforgloballevel);
                    $totalearnedpoints = $global_rewardpercents;
                    $totalredeempoints = '0';
                    $reasonindetail = '';
                    $refuserid = '0';
                    $variationid = '0';
                    RSPointExpiry::insert_earning_points($currentuserid, $pointsredeemed, $global_rewardpercents, $date, 'RVPFRPVL', $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                    $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($currentuserid);
                    RSPointExpiry::record_the_points($currentuserid, '0', $global_rewardpercents, $date, 'RVPFRPVL', $equearnamt, '0', '0', $postid, $variationid, $refuserid, '', $totalpoints, '', '0');
                }
                break;
        }
    }

    public static function add_shortcode_for_social_vk_like($contents) {
        ob_start();
        global $post;
        global $woocommerce;
        $rewardpoints = array('0');
        $global_enable = get_option('rs_global_social_enable_disable_reward');
        $global_reward_type = get_option('rs_global_social_reward_type_vk');
        $enablerewards = get_post_meta($post->ID, '_socialrewardsystemcheckboxvalue', true);
        $getaction = get_post_meta($post->ID, '_social_rewardsystem_options_vk', true);
        $getpoints = get_post_meta($post->ID, '_socialrewardsystempoints_vk', true);
        $getpercent = get_post_meta($post->ID, '_socialrewardsystempercent_vk', true);
        if (get_option('rs_set_price_to_calculate_rewardpoints_by_percentage') == '1') {
            $getregularprice = get_post_meta($post->ID, '_regular_price', true);
            if ($getregularprice == '') {
                $getregularprice = get_post_meta($post->ID, '_price', true);
            }
        } else {
            $getregularprice = get_post_meta($post->ID, '_price', true);
            if ($getregularprice == '') {
                $getregularprice = get_post_meta($post->ID, '_regular_price', true);
            }
        }
        if ($enablerewards == 'yes') {
            if ($getaction == '1') {

                if ($getpoints == '') {
                    $term = get_the_terms($post->ID, 'product_cat');

                    if (is_array($term)) {

                        foreach ($term as $term) {
                            $enablevalue = get_woocommerce_term_meta($term->term_id, 'enable_social_reward_system_category', true);
                            $display_type = get_woocommerce_term_meta($term->term_id, 'social_vk_enable_rs_rule', true);
                            if (($enablevalue == 'yes') && ( $enablevalue != '')) {
                                if ($display_type == '1') {
                                    if (get_woocommerce_term_meta($term->term_id, 'social_vk_rs_category_points', true) == '') {
                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                $rewardpoints[] = get_option('rs_global_social_vk_reward_points');
                                            } else {
                                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                
                                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                $getaverage = get_option('rs_global_social_vk_reward_percent') / 100;
                                                $getaveragepoints = $getaverage * $getregularprice;
                                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                            }
                                        }
                                    } else {
                                        $rewardpoints[] = get_woocommerce_term_meta($term->term_id, 'social_vk_rs_category_points', true);
                                    }
                                } else {
                                    $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                    
                                    $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                    $getaverage = get_woocommerce_term_meta($term->term_id, 'social_vk_rs_category_percent', true) / 100;
                                    $getaveragepoints = $getaverage * $getregularprice;
                                    $pointswithvalue = $getaveragepoints * $pointconversion;
                                    if (get_woocommerce_term_meta($term->term_id, 'social_vk_rs_category_percent', true) == '') {

                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                $rewardpoints[] = get_option('rs_global_social_vk_reward_points');
                                            } else {
                                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                
                                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                $getaverage = get_option('rs_global_social_vk_reward_percent') / 100;
                                                $getaveragepoints = $getaverage * $getregularprice;
                                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                            }
                                        }
                                    } else {
                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                    }
                                }
                            } else {
                                if ($global_enable == '1') {
                                    if ($global_reward_type == '1') {
                                        $rewardpoints[] = get_option('rs_global_social_vk_reward_points');
                                    } else {
                                        $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                        ;
                                        $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                        $getaverage = get_option('rs_global_social_vk_reward_percent') / 100;
                                        $getaveragepoints = $getaverage * $getregularprice;
                                        $pointswithvalue = $getaveragepoints * $pointconversion;
                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                    }
                                }
                            }
                        }
                    } else {

                        if ($global_enable == '1') {

                            if ($global_reward_type == '1') {
                                $rewardpoints[] = get_option('rs_global_social_vk_reward_points');
                            } else {
                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                ;
                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                $getaverage = get_option('rs_global_social_vk_reward_percent') / 100;
                                $getaveragepoints = $getaverage * $getregularprice;
                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                            }
                        }
                    }
                } else {
                    $rewardpoints[] = $getpoints;
                }
            } else {

                $points = RSFunctionofGeneralTab::earn_point_conversion();
                ;
                $pointsequalto = RSFunctionofGeneralTab::earn_point_conversion_value();
                $takeaverage = $getpercent / 100;
                $mainaveragevalue = $takeaverage * $getregularprice;
                $addinpoint = $mainaveragevalue * $points;
                $totalpoint = $addinpoint / $pointsequalto;
                if ($getpercent === '') {
                    $term = get_the_terms($post->ID, 'product_cat');
                    if (is_array($term)) {
                        foreach ($term as $term) {
                            $enablevalue = get_woocommerce_term_meta($term->term_id, 'enable_social_reward_system_category', true);
                            $display_type = get_woocommerce_term_meta($term->term_id, 'social_vk_enable_rs_rule', true);
                            if (($enablevalue == 'yes') && ( $enablevalue != '')) {
                                if ($display_type == '1') {
                                    if (get_woocommerce_term_meta($term->term_id, 'social_vk_rs_category_points', true) == '') {
                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                $rewardpoints[] = get_option('rs_global_social_vk_reward_points');
                                            } else {
                                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                
                                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                $getaverage = get_option('rs_global_social_vk_reward_percent') / 100;
                                                $getaveragepoints = $getaverage * $getregularprice;
                                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                            }
                                        }
                                    } else {
                                        $rewardpoints[] = get_woocommerce_term_meta($term->term_id, 'social_vk_rs_category_points', true);
                                    }
                                } else {
                                    $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                    
                                    $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                    $getaverage = get_woocommerce_term_meta($term->term_id, 'social_vk_rs_category_percent', true) / 100;
                                    $getaveragepoints = $getaverage * $getregularprice;
                                    $pointswithvalue = $getaveragepoints * $pointconversion;
                                    if (get_woocommerce_term_meta($term->term_id, 'social_vk_rs_category_percent', true) == '') {

                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                $rewardpoints[] = get_option('rs_global_social_vk_reward_points');
                                            } else {
                                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                
                                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                $getaverage = get_option('rs_global_social_vk_reward_percent') / 100;
                                                $getaveragepoints = $getaverage * $getregularprice;
                                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                            }
                                        }
                                    } else {
                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                    }
                                }
                            } else {
                                if ($global_enable == '1') {
                                    if ($global_reward_type == '1') {
                                        $rewardpoints[] = get_option('rs_global_social_vk_reward_points');
                                    } else {
                                        $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                        
                                        $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                        $getaverage = get_option('rs_global_social_vk_reward_percent') / 100;
                                        $getaveragepoints = $getaverage * $getregularprice;
                                        $pointswithvalue = $getaveragepoints * $pointconversion;
                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                    }
                                }
                            }
                        }
                    } else {
                        if ($global_enable == '1') {
                            if ($global_reward_type == '1') {
                                $rewardpoints[] = get_option('rs_global_social_vk_reward_points');
                            } else {
                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                
                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                $getaverage = get_option('rs_global_social_vk_reward_percent') / 100;
                                $getaveragepoints = $getaverage * $getregularprice;
                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                            }
                        }
                    }
                } else {
                    $rewardpoints[] = $totalpoint;
                }
            }
            if (!empty($rewardpoints)) {
                echo $getpoints = max($rewardpoints);
            }
            $newcontnt = ob_get_clean();
            return $newcontnt;
        }
    }

    /* short code replacing end */

    public static function add_shortcode_for_social_google_share($contents) {
        ob_start();
        global $post;
        global $woocommerce;
        $rewardpoints = array('0');
        $global_enable = get_option('rs_global_social_enable_disable_reward');
        $global_reward_type = get_option('rs_global_social_reward_type_google');
        $enablerewards = get_post_meta($post->ID, '_socialrewardsystemcheckboxvalue', true);
        $getaction = get_post_meta($post->ID, '_social_rewardsystem_options_google', true);
        $getpoints = get_post_meta($post->ID, '_socialrewardsystempoints_google', true);
        $getpercent = get_post_meta($post->ID, '_socialrewardsystempercent_google', true);
        if (get_option('rs_set_price_to_calculate_rewardpoints_by_percentage') == '1') {
            $getregularprice = get_post_meta($post->ID, '_regular_price', true);
            if ($getregularprice == '') {
                $getregularprice = get_post_meta($post->ID, '_price', true);
            }
        } else {
            $getregularprice = get_post_meta($post->ID, '_price', true);
            if ($getregularprice == '') {
                $getregularprice = get_post_meta($post->ID, '_regular_price', true);
            }
        }
        if ($enablerewards == 'yes') {
            if ($getaction == '1') {

                if ($getpoints == '') {
                    $term = get_the_terms($post->ID, 'product_cat');

                    if (is_array($term)) {

                        foreach ($term as $term) {
                            $enablevalue = get_woocommerce_term_meta($term->term_id, 'enable_social_reward_system_category', true);
                            $display_type = get_woocommerce_term_meta($term->term_id, 'social_google_enable_rs_rule', true);
                            if (($enablevalue == 'yes') && ( $enablevalue != '')) {
                                if ($display_type == '1') {
                                    if (get_woocommerce_term_meta($term->term_id, 'social_google_rs_category_points', true) == '') {
                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                $rewardpoints[] = get_option('rs_global_social_google_reward_points');
                                            } else {
                                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                
                                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                $getaverage = get_option('rs_global_social_google_reward_percent') / 100;
                                                $getaveragepoints = $getaverage * $getregularprice;
                                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                            }
                                        }
                                    } else {
                                        $rewardpoints[] = get_woocommerce_term_meta($term->term_id, 'social_google_rs_category_points', true);
                                    }
                                } else {
                                    $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                    
                                    $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                    $getaverage = get_woocommerce_term_meta($term->term_id, 'social_google_rs_category_percent', true) / 100;
                                    $getaveragepoints = $getaverage * $getregularprice;
                                    $pointswithvalue = $getaveragepoints * $pointconversion;
                                    if (get_woocommerce_term_meta($term->term_id, 'social_google_rs_category_percent', true) == '') {

                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                $rewardpoints[] = get_option('rs_global_social_google_reward_points');
                                            } else {
                                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                
                                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                $getaverage = get_option('rs_global_social_google_reward_percent') / 100;
                                                $getaveragepoints = $getaverage * $getregularprice;
                                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                            }
                                        }
                                    } else {
                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                    }
                                }
                            } else {
                                if ($global_enable == '1') {
                                    if ($global_reward_type == '1') {
                                        $rewardpoints[] = get_option('rs_global_social_google_reward_points');
                                    } else {
                                        $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                        
                                        $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                        $getaverage = get_option('rs_global_social_google_reward_percent') / 100;
                                        $getaveragepoints = $getaverage * $getregularprice;
                                        $pointswithvalue = $getaveragepoints * $pointconversion;
                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                    }
                                }
                            }
                        }
                    } else {

                        if ($global_enable == '1') {

                            if ($global_reward_type == '1') {
                                $rewardpoints[] = get_option('rs_global_social_google_reward_points');
                            } else {
                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                ;
                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                $getaverage = get_option('rs_global_social_google_reward_percent') / 100;
                                $getaveragepoints = $getaverage * $getregularprice;
                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                            }
                        }
                    }
                } else {
                    $rewardpoints[] = $getpoints;
                }
            } else {

                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                ;
                $pointsequalto = RSFunctionofGeneralTab::earn_point_conversion_value();
                $takeaverage = $getpercent / 100;
                $mainaveragevalue = $takeaverage * $getregularprice;
                $addinpoint = $mainaveragevalue * $points;
                $totalpoint = $addinpoint / $pointsequalto;
                if ($getpercent === '') {
                    $term = get_the_terms($post->ID, 'product_cat');
                    if (is_array($term)) {
                        foreach ($term as $term) {
                            $enablevalue = get_woocommerce_term_meta($term->term_id, 'enable_social_reward_system_category', true);
                            $display_type = get_woocommerce_term_meta($term->term_id, 'social_google_enable_rs_rule', true);
                            if (($enablevalue == 'yes') && ( $enablevalue != '')) {
                                if ($display_type == '1') {
                                    if (get_woocommerce_term_meta($term->term_id, 'social_google_rs_category_points', true) == '') {
                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                $rewardpoints[] = get_option('rs_global_social_google_reward_points');
                                            } else {
                                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                
                                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                $getaverage = get_option('rs_global_social_google_reward_percent') / 100;
                                                $getaveragepoints = $getaverage * $getregularprice;
                                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                            }
                                        }
                                    } else {
                                        $rewardpoints[] = get_woocommerce_term_meta($term->term_id, 'social_google_rs_category_points', true);
                                    }
                                } else {
                                    $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                    ;
                                    $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                    $getaverage = get_woocommerce_term_meta($term->term_id, 'social_google_rs_category_percent', true) / 100;
                                    $getaveragepoints = $getaverage * $getregularprice;
                                    $pointswithvalue = $getaveragepoints * $pointconversion;
                                    if (get_woocommerce_term_meta($term->term_id, 'social_google_rs_category_percent', true) == '') {

                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                $rewardpoints[] = get_option('rs_global_social_google_reward_points');
                                            } else {
                                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                ;
                                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                $getaverage = get_option('rs_global_social_google_reward_percent') / 100;
                                                $getaveragepoints = $getaverage * $getregularprice;
                                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                            }
                                        }
                                    } else {
                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                    }
                                }
                            } else {
                                if ($global_enable == '1') {
                                    if ($global_reward_type == '1') {
                                        $rewardpoints[] = get_option('rs_global_social_google_reward_points');
                                    } else {
                                        $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                        
                                        $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                        $getaverage = get_option('rs_global_social_google_reward_percent') / 100;
                                        $getaveragepoints = $getaverage * $getregularprice;
                                        $pointswithvalue = $getaveragepoints * $pointconversion;
                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                    }
                                }
                            }
                        }
                    } else {
                        if ($global_enable == '1') {
                            if ($global_reward_type == '1') {
                                $rewardpoints[] = get_option('rs_global_social_google_reward_points');
                            } else {
                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                
                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                $getaverage = get_option('rs_global_social_google_reward_percent') / 100;
                                $getaveragepoints = $getaverage * $getregularprice;
                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                            }
                        }
                    }
                } else {
                    $rewardpoints[] = $totalpoint;
                }
            }
            if (!empty($rewardpoints)) {
                echo $getpoints = max($rewardpoints);
            }
            $newcontnt = ob_get_clean();
            return $newcontnt;
        }
    }

    public static function add_shortcode_for_social_facebook_like($contents) {
        ob_start();
        global $woocommerce;
        global $post;
        $rewardpoints = array('0');
        $global_enable = get_option('rs_global_social_enable_disable_reward');
        $global_reward_type = get_option('rs_global_social_reward_type_facebook');
        $enablerewards = get_post_meta($post->ID, '_socialrewardsystemcheckboxvalue', true);
        $getaction = get_post_meta($post->ID, '_social_rewardsystem_options_facebook', true);
        $getpoints = get_post_meta($post->ID, '_socialrewardsystempoints_facebook', true);
        $getpercent = get_post_meta($post->ID, '_socialrewardsystempercent_facebook', true);
        if (get_option('rs_set_price_to_calculate_rewardpoints_by_percentage') == '1') {
            $getregularprice = get_post_meta($post->ID, '_regular_price', true);
            if ($getregularprice == '') {
                $getregularprice = get_post_meta($post->ID, '_price', true);
            }
        } else {
            $getregularprice = get_post_meta($post->ID, '_price', true);
            if ($getregularprice == '') {
                $getregularprice = get_post_meta($post->ID, '_regular_price', true);
            }
        }
        if ($enablerewards == 'yes') {
            if ($getaction == '1') {
                if ($getpoints == '') {
                    $term = get_the_terms($post->ID, 'product_cat');
                    if (is_array($term)) {
                        foreach ($term as $term) {
                            $enablevalue = get_woocommerce_term_meta($term->term_id, 'enable_social_reward_system_category', true);
                            $display_type = get_woocommerce_term_meta($term->term_id, 'social_facebook_enable_rs_rule', true);
                            if (($enablevalue == 'yes') && ( $enablevalue != '')) {
                                if ($display_type == '1') {
                                    if (get_woocommerce_term_meta($term->term_id, 'social_facebook_rs_category_points', true) == '') {
                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                $rewardpoints[] = get_option('rs_global_social_facebook_reward_points');
                                            } else {
                                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                
                                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                $getaverage = get_option('rs_global_social_facebook_reward_percent') / 100;
                                                $getaveragepoints = $getaverage * $getregularprice;
                                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                            }
                                        }
                                    } else {
                                        $rewardpoints[] = get_woocommerce_term_meta($term->term_id, 'social_facebook_rs_category_points', true);
                                    }
                                } else {
                                    $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                    
                                    $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                    $getaverage = get_woocommerce_term_meta($term->term_id, 'social_facebook_rs_category_percent', true) / 100;
                                    $getaveragepoints = $getaverage * $getregularprice;
                                    $pointswithvalue = $getaveragepoints * $pointconversion;
                                    if (get_woocommerce_term_meta($term->term_id, 'social_facebook_rs_category_percent', true) == '') {
                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                $rewardpoints[] = get_option('rs_global_social_facebook_reward_points');
                                            } else {
                                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                ;
                                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                $getaverage = get_option('rs_global_social_facebook_reward_percent') / 100;
                                                $getaveragepoints = $getaverage * $getregularprice;
                                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                            }
                                        }
                                    } else {
                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                    }
                                }
                            } else {
                                if ($global_enable == '1') {
                                    if ($global_reward_type == '1') {
                                        $rewardpoints[] = get_option('rs_global_social_facebook_reward_points');
                                    } else {
                                        $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                        
                                        $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                        $getaverage = get_option('rs_global_social_facebook_reward_percent') / 100;
                                        $getaveragepoints = $getaverage * $getregularprice;
                                        $pointswithvalue = $getaveragepoints * $pointconversion;
                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                    }
                                }
                            }
                        }
                    } else {
                        if ($global_enable == '1') {
                            if ($global_reward_type == '1') {
                                $rewardpoints[] = get_option('rs_global_social_facebook_reward_points');
                            } else {
                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                
                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                $getaverage = get_option('rs_global_social_facebook_reward_percent') / 100;
                                $getaveragepoints = $getaverage * $getregularprice;
                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                            }
                        }
                    }
                } else {
                    $rewardpoints[] = $getpoints;
                }
            } else {


                $points = RSFunctionofGeneralTab::earn_point_conversion();
                ;
                $pointsequalto = RSFunctionofGeneralTab::earn_point_conversion_value();
                $takeaverage = $getpercent / 100;
                $mainaveragevalue = $takeaverage * $getregularprice;
                $addinpoint = $mainaveragevalue * $points;
                $totalpoint = $addinpoint / $pointsequalto;
                if ($getpercent === '') {
                    $term = get_the_terms($post->ID, 'product_cat');
                    if (is_array($term)) {
                        foreach ($term as $term) {
                            $enablevalue = get_woocommerce_term_meta($term->term_id, 'enable_social_reward_system_category', true);
                            $display_type = get_woocommerce_term_meta($term->term_id, 'social_facebook_enable_rs_rule', true);
                            if (($enablevalue == 'yes') && ( $enablevalue != '')) {
                                if ($display_type == '1') {
                                    if (get_woocommerce_term_meta($term->term_id, 'social_facebook_rs_category_points', true) == '') {
                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                $rewardpoints[] = get_option('rs_global_social_facebook_reward_points');
                                            } else {
                                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                
                                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                $getaverage = get_option('rs_global_social_facebook_reward_percent') / 100;
                                                $getaveragepoints = $getaverage * $getregularprice;
                                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                            }
                                        }
                                    } else {
                                        $rewardpoints[] = get_woocommerce_term_meta($term->term_id, 'social_facebook_rs_category_points', true);
                                    }
                                } else {
                                    $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                    
                                    $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                    $getaverage = get_woocommerce_term_meta($term->term_id, 'social_facebook_rs_category_percent', true) / 100;
                                    $getaveragepoints = $getaverage * $getregularprice;
                                    $pointswithvalue = $getaveragepoints * $pointconversion;
                                    if (get_woocommerce_term_meta($term->term_id, 'social_facebook_rs_category_percent', true) == '') {

                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                $rewardpoints[] = get_option('rs_global_social_facebook_reward_points');
                                            } else {
                                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                
                                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                $getaverage = get_option('rs_global_social_facebook_reward_percent') / 100;
                                                $getaveragepoints = $getaverage * $getregularprice;
                                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                            }
                                        }
                                    } else {
                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                    }
                                }
                            } else {
                                if ($global_enable == '1') {
                                    if ($global_reward_type == '1') {
                                        $rewardpoints[] = get_option('rs_global_social_facebook_reward_points');
                                    } else {
                                        $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                        
                                        $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                        $getaverage = get_option('rs_global_social_facebook_reward_percent') / 100;
                                        $getaveragepoints = $getaverage * $getregularprice;
                                        $pointswithvalue = $getaveragepoints * $pointconversion;
                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                    }
                                }
                            }
                        }
                    } else {
                        if ($global_enable == '1') {
                            if ($global_reward_type == '1') {
                                $rewardpoints[] = get_option('rs_global_social_facebook_reward_points');
                            } else {
                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                
                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                $getaverage = get_option('rs_global_social_facebook_reward_percent') / 100;
                                $getaveragepoints = $getaverage * $getregularprice;
                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                            }
                        }
                    }
                } else {
                    $rewardpoints[] = $totalpoint;
                }
            }
            if (!empty($rewardpoints)) {
                echo $getpoints = max($rewardpoints);
            }
            $newcontentss = ob_get_clean();
            return $newcontentss;
        }
    }

    public static function add_shortcode_for_social_facebook_share($contents) {
        ob_start();
        global $woocommerce;
        global $post;
        $rewardpoints = array('0');
        $global_enable = get_option('rs_global_social_enable_disable_reward');
        $global_reward_type = get_option('rs_global_social_reward_type_facebook_share');
        $enablerewards = get_post_meta($post->ID, '_socialrewardsystemcheckboxvalue', true);
        $getaction = get_post_meta($post->ID, '_social_rewardsystem_options_facebook_share', true);
        $getpoints = get_post_meta($post->ID, '_socialrewardsystempoints_facebook_share', true);
        $getpercent = get_post_meta($post->ID, '_socialrewardsystempercent_facebook_share', true);
        if (get_option('rs_set_price_to_calculate_rewardpoints_by_percentage') == '1') {
            $getregularprice = get_post_meta($post->ID, '_regular_price', true);
            if ($getregularprice == '') {
                $getregularprice = get_post_meta($post->ID, '_price', true);
            }
        } else {
            $getregularprice = get_post_meta($post->ID, '_price', true);
            if ($getregularprice == '') {
                $getregularprice = get_post_meta($post->ID, '_regular_price', true);
            }
        }
        if ($enablerewards == 'yes') {
            if ($getaction == '1') {
                if ($getpoints == '') {
                    $term = get_the_terms($post->ID, 'product_cat');
                    if (is_array($term)) {
                        foreach ($term as $term) {
                            $enablevalue = get_woocommerce_term_meta($term->term_id, 'enable_social_reward_system_category', true);
                            $display_type = get_woocommerce_term_meta($term->term_id, 'social_facebook_share_enable_rs_rule', true);
                            if (($enablevalue == 'yes') && ( $enablevalue != '')) {
                                if ($display_type == '1') {
                                    if (get_woocommerce_term_meta($term->term_id, 'social_facebook_share_rs_category_points', true) == '') {
                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                $rewardpoints[] = get_option('rs_global_social_facebook_share_reward_points');
                                            } else {
                                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                
                                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                $getaverage = get_option('rs_global_social_facebook_share_reward_percent') / 100;
                                                $getaveragepoints = $getaverage * $getregularprice;
                                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                            }
                                        }
                                    } else {
                                        $rewardpoints[] = get_woocommerce_term_meta($term->term_id, 'social_facebook_rs_category_points', true);
                                    }
                                } else {
                                    $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                    
                                    $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                    $getaverage = get_woocommerce_term_meta($term->term_id, 'social_facebook_share_rs_category_percent', true) / 100;
                                    $getaveragepoints = $getaverage * $getregularprice;
                                    $pointswithvalue = $getaveragepoints * $pointconversion;
                                    if (get_woocommerce_term_meta($term->term_id, 'social_facebook_share_rs_category_percent', true) == '') {
                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                $rewardpoints[] = get_option('rs_global_social_facebook_share_reward_points');
                                            } else {
                                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                
                                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                $getaverage = get_option('rs_global_social_facebook_share_reward_percent') / 100;
                                                $getaveragepoints = $getaverage * $getregularprice;
                                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                            }
                                        }
                                    } else {
                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                    }
                                }
                            } else {
                                if ($global_enable == '1') {
                                    if ($global_reward_type == '1') {
                                        $rewardpoints[] = get_option('rs_global_social_facebook_share_reward_points');
                                    } else {
                                        $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                        
                                        $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                        $getaverage = get_option('rs_global_social_facebook_share_reward_percent') / 100;
                                        $getaveragepoints = $getaverage * $getregularprice;
                                        $pointswithvalue = $getaveragepoints * $pointconversion;
                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                    }
                                }
                            }
                        }
                    } else {
                        if ($global_enable == '1') {
                            if ($global_reward_type == '1') {
                                $rewardpoints[] = get_option('rs_global_social_facebook_share_reward_points');
                            } else {
                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                
                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                $getaverage = get_option('rs_global_social_facebook_share_reward_percent') / 100;
                                $getaveragepoints = $getaverage * $getregularprice;
                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                            }
                        }
                    }
                } else {
                    $rewardpoints[] = $getpoints;
                }
            } else {
                $points = RSFunctionofGeneralTab::earn_point_conversion();
                
                $pointsequalto = RSFunctionofGeneralTab::earn_point_conversion_value();

                $takeaverage = $getpercent / 100;
                $mainaveragevalue = $takeaverage * $getregularprice;
                $addinpoint = $mainaveragevalue * $points;
                $totalpoint = $addinpoint / $pointsequalto;
                if ($getpercent === '') {
                    $term = get_the_terms($post->ID, 'product_cat');
                    if (is_array($term)) {
                        foreach ($term as $term) {
                            $enablevalue = get_woocommerce_term_meta($term->term_id, 'enable_social_reward_system_category', true);
                            $display_type = get_woocommerce_term_meta($term->term_id, 'social_facebook_share_enable_rs_rule', true);
                            if (($enablevalue == 'yes') && ( $enablevalue != '')) {
                                if ($display_type == '1') {
                                    if (get_woocommerce_term_meta($term->term_id, 'social_facebook_share_rs_category_points', true) == '') {
                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                $rewardpoints[] = get_option('rs_global_social_facebook_share_reward_points');
                                            } else {
                                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                
                                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                $getaverage = get_option('rs_global_social_facebook_share_reward_percent') / 100;
                                                $getaveragepoints = $getaverage * $getregularprice;
                                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                            }
                                        }
                                    } else {
                                        $rewardpoints[] = get_woocommerce_term_meta($term->term_id, 'social_facebook_share_rs_category_points', true);
                                    }
                                } else {
                                    $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                    
                                    $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                    $getaverage = get_woocommerce_term_meta($term->term_id, 'social_facebook_share_rs_category_percent', true) / 100;
                                    $getaveragepoints = $getaverage * $getregularprice;
                                    $pointswithvalue = $getaveragepoints * $pointconversion;
                                    if (get_woocommerce_term_meta($term->term_id, 'social_facebook_share_rs_category_percent', true) == '') {

                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                $rewardpoints[] = get_option('rs_global_social_facebook_share_reward_points');
                                            } else {
                                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                
                                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                $getaverage = get_option('rs_global_social_facebook_share_reward_percent') / 100;
                                                $getaveragepoints = $getaverage * $getregularprice;
                                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                            }
                                        }
                                    } else {
                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                    }
                                }
                            } else {
                                if ($global_enable == '1') {
                                    if ($global_reward_type == '1') {
                                        $rewardpoints[] = get_option('rs_global_social_facebook_share_reward_points');
                                    } else {
                                        $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                        
                                        $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                        $getaverage = get_option('rs_global_social_facebook_share_reward_percent') / 100;
                                        $getaveragepoints = $getaverage * $getregularprice;
                                        $pointswithvalue = $getaveragepoints * $pointconversion;
                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                    }
                                }
                            }
                        }
                    } else {
                        if ($global_enable == '1') {
                            if ($global_reward_type == '1') {
                                $rewardpoints[] = get_option('rs_global_social_facebook_share_reward_points');
                            } else {
                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                
                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                $getaverage = get_option('rs_global_social_facebook_share_reward_percent') / 100;
                                $getaveragepoints = $getaverage * $getregularprice;
                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                            }
                        }
                    }
                } else {
                    $rewardpoints[] = $totalpoint;
                }
            }
            if (!empty($rewardpoints)) {
                echo $getpoints = max($rewardpoints);
            }
            $newcontentss = ob_get_clean();
            return $newcontentss;
        }
    }

    public static function add_shortcode_for_social_twitter_tweet() {
        ob_start();
        global $woocommerce;
        global $post;
        $rewardpoints = array('0');
        $global_enable = get_option('rs_global_social_enable_disable_reward');
        $global_reward_type = get_option('rs_global_social_reward_type_twitter');
        $enablerewards = get_post_meta($post->ID, '_socialrewardsystemcheckboxvalue', true);
        $getaction = get_post_meta($post->ID, '_social_rewardsystem_options_twitter', true);
        $getpoints = get_post_meta($post->ID, '_socialrewardsystempoints_twitter', true);
        $getpercent = get_post_meta($post->ID, '_socialrewardsystempercent_twitter', true);
        if (get_option('rs_set_price_to_calculate_rewardpoints_by_percentage') == '1') {
            $getregularprice = get_post_meta($post->ID, '_regular_price', true);
            if ($getregularprice == '') {
                $getregularprice = get_post_meta($post->ID, '_price', true);
            }
        } else {
            $getregularprice = get_post_meta($post->ID, '_price', true);
            if ($getregularprice == '') {
                $getregularprice = get_post_meta($post->ID, '_regular_price', true);
            }
        }
        if ($enablerewards == 'yes') {
            if ($getaction == '1') {
                if ($getpoints == '') {
                    $term = get_the_terms($post->ID, 'product_cat');
                    if (is_array($term)) {
                        foreach ($term as $term) {
                            $enablevalue = get_woocommerce_term_meta($term->term_id, 'enable_social_reward_system_category', true);
                            $display_type = get_woocommerce_term_meta($term->term_id, 'social_twitter_enable_rs_rule', true);
                            if (($enablevalue == 'yes') && ( $enablevalue != '')) {
                                if ($display_type == '1') {
                                    if (get_woocommerce_term_meta($term->term_id, 'social_twitter_rs_category_points', true) == '') {
                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                $rewardpoints[] = get_option('rs_global_social_twitter_reward_points');
                                            } else {
                                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                
                                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                $getaverage = get_option('rs_global_social_twitter_reward_percent') / 100;
                                                $getaveragepoints = $getaverage * $getregularprice;
                                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                            }
                                        }
                                    } else {
                                        $rewardpoints[] = get_woocommerce_term_meta($term->term_id, 'social_twitter_rs_category_points', true);
                                    }
                                } else {
                                    $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                    
                                    $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                    $getaverage = get_woocommerce_term_meta($term->term_id, 'social_twitter_rs_category_percent', true) / 100;
                                    $getaveragepoints = $getaverage * $getregularprice;
                                    $pointswithvalue = $getaveragepoints * $pointconversion;
                                    if (get_woocommerce_term_meta($term->term_id, 'social_twitter_rs_category_percent', true) == '') {
                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                $rewardpoints[] = get_option('rs_global_social_twitter_reward_points');
                                            } else {
                                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                ;
                                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                $getaverage = get_option('rs_global_social_twitter_reward_percent') / 100;
                                                $getaveragepoints = $getaverage * $getregularprice;
                                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                            }
                                        }
                                    } else {
                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                    }
                                }
                            } else {
                                if ($global_enable == '1') {
                                    if ($global_reward_type == '1') {
                                        $rewardpoints[] = get_option('rs_global_social_twitter_reward_points');
                                    } else {
                                        $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                        
                                        $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                        $getaverage = get_option('rs_global_social_twitter_reward_percent') / 100;
                                        $getaveragepoints = $getaverage * $getregularprice;
                                        $pointswithvalue = $getaveragepoints * $pointconversion;
                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                    }
                                }
                            }
                        }
                    } else {
                        if ($global_enable == '1') {
                            if ($global_reward_type == '1') {
                                $rewardpoints[] = get_option('rs_global_social_twitter_reward_points');
                            } else {
                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                
                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                $getaverage = get_option('rs_global_social_twitter_reward_percent') / 100;
                                $getaveragepoints = $getaverage * $getregularprice;
                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                            }
                        }
                    }
                } else {
                    $rewardpoints[] = $getpoints;
                }
            } else {
                $points = RSFunctionofGeneralTab::earn_point_conversion();
                ;
                $pointsequalto = RSFunctionofGeneralTab::earn_point_conversion_value();
                $takeaverage = $getpercent / 100;
                $mainaveragevalue = $takeaverage * $getregularprice;
                $addinpoint = $mainaveragevalue * $points;
                $totalpoint = $addinpoint / $pointsequalto;
                if ($getpercent === '') {
                    $term = get_the_terms($post->ID, 'product_cat');
                    if (is_array($term)) {
                        foreach ($term as $term) {
                            $enablevalue = get_woocommerce_term_meta($term->term_id, 'enable_social_reward_system_category', true);
                            $display_type = get_woocommerce_term_meta($term->term_id, 'social_twitter_enable_rs_rule', true);
                            if (($enablevalue == 'yes') && ( $enablevalue != '')) {
                                if ($display_type == '1') {
                                    if (get_woocommerce_term_meta($term->term_id, 'social_twitter_rs_category_points', true) == '') {
                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                $rewardpoints[] = get_option('rs_global_social_twitter_reward_points');
                                            } else {
                                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                
                                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                $getaverage = get_option('rs_global_social_twitter_reward_percent') / 100;
                                                $getaveragepoints = $getaverage * $getregularprice;
                                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                            }
                                        }
                                    } else {
                                        $rewardpoints[] = get_woocommerce_term_meta($term->term_id, 'social_twitter_rs_category_points', true);
                                    }
                                } else {
                                    $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                    
                                    $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                    $getaverage = get_woocommerce_term_meta($term->term_id, 'social_twitter_rs_category_percent', true) / 100;
                                    $getaveragepoints = $getaverage * $getregularprice;
                                    $pointswithvalue = $getaveragepoints * $pointconversion;
                                    if (get_woocommerce_term_meta($term->term_id, 'social_twitter_rs_category_percent', true) == '') {

                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                $rewardpoints[] = get_option('rs_global_social_twitter_reward_points');
                                            } else {
                                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                ;
                                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                $getaverage = get_option('rs_global_social_twitter_reward_percent') / 100;
                                                $getaveragepoints = $getaverage * $getregularprice;
                                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                            }
                                        }
                                    } else {
                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                    }
                                }
                            } else {
                                if ($global_enable == '1') {
                                    if ($global_reward_type == '1') {
                                        $rewardpoints[] = get_option('rs_global_social_twitter_reward_points');
                                    } else {
                                        $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                        
                                        $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                        $getaverage = get_option('rs_global_social_twitter_reward_percent') / 100;
                                        $getaveragepoints = $getaverage * $getregularprice;
                                        $pointswithvalue = $getaveragepoints * $pointconversion;
                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                    }
                                }
                            }
                        }
                    } else {
                        if ($global_enable == '1') {
                            if ($global_reward_type == '1') {
                                $rewardpoints[] = get_option('rs_global_social_twitter_reward_points');
                            } else {
                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                ;
                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                $getaverage = get_option('rs_global_social_twitter_reward_percent') / 100;
                                $getaveragepoints = $getaverage * $getregularprice;
                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                            }
                        }
                    }
                } else {
                    $rewardpoints[] = $totalpoint;
                }
            }
            if (!empty($rewardpoints)) {
                echo $getpoints = max($rewardpoints);
            }
            $newcontents = ob_get_clean();
            return $newcontents;
        }
    }

    public static function add_enqueue_for_social_messages() {

        wp_register_script('wp_reward_tooltip', plugins_url('rewardsystem/js/jquery.tipsy.js'));
        wp_register_style('wp_reward_tooltip_style', plugins_url('rewardsystem/css/tipsy.css'));
        wp_register_script('wp_jscolor_rewards', plugins_url('rewardsystem/jscolor/jscolor.js'));
        if (get_option('rs_reward_point_enable_tipsy_social_rewards') == '1') {
            wp_enqueue_script('wp_reward_tooltip');
        }
        wp_enqueue_script('wp_jscolor_rewards');
        wp_enqueue_style('wp_reward_tooltip_style');
    }

    public static function add_enqueue_jscolor_for_social_messages() {
        wp_register_script('wp_jscolor_rewards', plugins_url('rewardsystem/jscolor/jscolor.js'));
        wp_enqueue_script('wp_jscolor_rewards');
    }

}

new RSFunctionForSocialRewards();
