/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(function ($) {
    //alert("yes it is loaded");
    var is_blocked = function ($node) {
        return $node.is('.processing');
    };

    var block = function ($node) {
        $node.addClass('processing').block({
            message: null,
            overlayCSS: {
                background: '#fff',
                opacity: 0.6
            }
        });
    };
    var unblock = function ($node) {
        $node.removeClass('processing').unblock();
    };

    var another_common_syntax = function ($node) {


        //console.log($node);
        var $html = $.parseHTML($node);

        var $html_data = $(".cart_totals", $html);

        //console.log($html_data);

        var $sumomessage = $('div.sumo_reward_points_info_message', $html);

        var $complete_message = $('div.sumo_reward_points_complete_message', $html);

        var $current_point_message = $('div.sumo_reward_points_current_points_message', $html);
        var $autoredeem_message = $('div.sumo_reward_points_auto_redeem_message', $html);
        var $autoredeem_error_message = $('div.sumo_reward_points_auto_redeem_error_message', $html);
        var $manualredeem_message = $('div.sumo_reward_points_manual_redeem_message', $html);
        var $manualredeem_error_message = $('div.sumo_reward_points_manual_redeem_error_message', $html);
        var $load_script = $('div.sumo_reward_point_hide_field_script', $html);
        var $apply_discount = $('div.sumo_reward_points_cart_apply_discount', $html).closest('form');

        console.log($apply_discount);

        //console.log($apply_discount);
        show_notice($sumomessage, false);
        show_notice($complete_message, false);
        show_notice($current_point_message, false);
        show_notice($autoredeem_message, false);
        show_notice($autoredeem_error_message, false);
        show_notice($manualredeem_message, false);
        show_notice($manualredeem_error_message, false);
        show_notice($load_script, false);

        console.log($apply_discount);
        $('div.woocommerce').prepend($apply_discount);
        //$('div.cart_totals').replaceWith($html_data);
    };

    var common_syntax = function ($node) {
        //console.log($node);
        var $html = $.parseHTML($node, true);
        //console.log($($html).find('script#sumo_reward_point_hide_field_script'));
        var $html_data = $($.parseHTML($node)).filter(".cart_totals");
        var $sumomessage = $($.parseHTML($node)).filter('.sumo_reward_points_info_message');
        var $complete_message = $($.parseHTML($node)).filter('.sumo_reward_points_complete_message');
        var $current_point_message = $($.parseHTML($node)).filter('.sumo_reward_points_current_points_message');
        var $autoredeem_message = $($.parseHTML($node)).filter('.sumo_reward_points_auto_redeem_message');
        var $autoredeem_error_message = $($.parseHTML($node)).filter('.sumo_reward_points_auto_redeem_error_message');
        var $manualredeem_message = $($.parseHTML($node)).filter('.sumo_reward_points_manual_redeem_message');
        var $manualredeem_error_message = $($.parseHTML($node)).filter('.sumo_reward_points_manual_redeem_error_message');
        var $load_script = $($html).filter('div.sumo_reward_point_hide_field_script');

        var $apply_discount = $('div.sumo_reward_points_cart_apply_discount', $html).closest('form');
        //console.log($load_script);
        //

        if ($load_script.length > 0) {
            $('div.sumo_reward_points_cart_apply_discount', $html).closest('form').hide();
        } else {
            $('div.woocommerce').prepend($apply_discount);
        }

        show_notice($sumomessage, false);
        show_notice($complete_message, false);
        show_notice($current_point_message, false);
        show_notice($autoredeem_message, false);
        show_notice($autoredeem_error_message, false);
        show_notice($manualredeem_message, false);
        show_notice($manualredeem_error_message, false);
        show_notice($load_script, false);
        //show_notice($apply_discount, false);

        $('div.cart_totals').replaceWith($html_data);


    };


    var remove_coupon_syntax = function (html_str) {


    };

    var update_wc_div = function (html_str) {
        var $html = $.parseHTML(html_str);
        var $new_form = $('table.shop_table.cart', $html).closest('form');
        var $new_totals = $('.cart_totals', $html);

        // Error message collection
        var $error = $('.woocommerce-error', $html);
        
        var $message = $('.woocommerce-message', $html);

        // Remove errors
      //  $('.woocommerce-error, .woocommerce-message').remove();

        if ($new_form.length === 0) {
            // No items to display now! Replace all cart content.
            var $cart_html = $('.cart-empty', $html).closest('.woocommerce');
            $('table.shop_table.cart').closest('.woocommerce').replaceWith($cart_html);

            if ($error.length > 0) {
                show_notice($error, $('.cart-empty').closest('.woocommerce'));
            } else if ($message.length > 0) {
                show_notice($message, $('.cart-empty').closest('.woocommerce'));
            }
        } else {
            $('table.shop_table.cart').closest('form').replaceWith($new_form);
            $('table.shop_table.cart').closest('form').find('input[name="update_cart"]').prop('disabled', true);

            if ($error.length > 0) {
                show_notice($error);
            } else if ($message.length > 0) {
                show_notice($message);
            }

            update_cart_totals_div($new_totals);
        }

        $(document.body).trigger('updated_wc_div');
    };

    var update_cart_totals_div = function (html_str) {
        $('.cart_totals').replaceWith(html_str);
        $(document.body).trigger('updated_cart_totals');
    };

    var show_notice = function (html_element, $target) {
        // if (!$target) {
        $target = $('table.shop_table.cart').closest('form');
        // }

        $target.before(html_element);
    };

    var show_another_notice = function (html_element, $target) {

        $target = $('table.show_table.cart').closest('form');
        //console.log($target);
        //$target.before(html_element);
    }

    var cart = {
        /**
         * Initialize cart UI events.
         */
        init: function () {

            this.cart_apply_redeeming = this.cart_apply_redeeming.bind(this);
            this.submit_click = this.submit_click.bind(this);
            this.apply_sumo_reward_points = this.apply_sumo_reward_points.bind(this);
            this.quantity_update_cart = this.quantity_update_cart.bind(this);
            this.quantity_update = this.quantity_update.bind(this);
            this.item_remove_clicked = this.item_remove_clicked.bind(this);
            this.apply_redeeming_button = this.apply_redeeming_button.bind(this);
            this.remove_coupon_clicked = this.remove_coupon_clicked.bind(this);
            this.update_cart = this.update_cart.bind(this);
            this.add_free_product = this.add_free_product.bind(this);

            $(document).on(
                    'submit',
                    'div.woocommerce > form',
                    this.quantity_update_cart);


            $(document).on(
                    'submit',
                    'div.woocommerce > form',
                    this.cart_apply_redeeming);


            $(document).on(
                    'submit',
                    'div.woocommerce > form',
                    this.apply_redeeming_button);


            $(document).on(
                    'click',
                    'td.product-remove > a',
                    this.item_remove_clicked);

            $(document).on(
                    'click',
                    'a.woocommerce-remove-coupon',
                    this.remove_coupon_clicked);

            $(document).on(
                    'click',
                    'a.add_removed_free_product_to_cart',
                    this.add_free_product);


        },
        add_free_product: function (evt) {

            var target = evt.target;
            //console.log($(evt.target).data('cartkey'));
            //console.log(this.attr('data-cartkey'));
            // return false;
            var data = {
                action: 'delete_meta_current_key',
                current_user_id: sumo_global_variable_js.user_id,
                key_to_remove: $(evt.target).data('cartkey'),
            };
            $.ajax({
                url: sumo_global_variable_js.wp_ajax_url,
                data: data,
                type: 'post',
                //dataType: 'html',
                success: function (response) {
                    var newresponse = response.replace(/\s/g, '');
                    if (newresponse === '1') {
                        location.reload();
                    }
                }
            });


        },
        item_remove_clicked: function (evt) {

            evt.preventDefault();

            var $a = $(evt.target);
            var $form = $a.parents('form');

            block($form);
            block($('div.cart_totals'));
            $('.sumo_reward_points_info_message, .sumo_reward_points_complete_message, .sumo_reward_points_current_points_message, .sumo_reward_points_auto_redeem_message, .sumo_reward_points_auto_redeem_error_message, .sumo_reward_points_manual_redeem_message, .sumo_reward_points_manual_redeem_error_message, .sumo_reward_point_hide_field_script, .sumo_reward_points_cart_apply_discount').remove();
            $.ajax({
                type: 'GET',
                url: $a.attr('href'),
                dataType: 'html',
                success: another_common_syntax,
                complete: function () {
                    unblock($form);
                    unblock($('div.cart_totals'));
                }
            });
        },
        remove_coupon_clicked: function (evt) {
            evt.preventDefault();
            $form = $('table.shop_table.cart').closest('form');
            var $datacoupon = $(evt.target).data('coupon');
            //console.log($datacoupon);
            var data = {
                action: 'sumo_remove_coupon',
                coupon: $datacoupon,
            };
            $.ajax({
                url: sumo_global_variable_js.wp_ajax_url,
                data: data,
                dataType: 'html',
                type: 'post',
                success: function (response) {
                    $('.sumo_reward_points_info_message, .sumo_reward_points_complete_message, .sumo_reward_points_current_points_message, .sumo_reward_points_auto_redeem_message, .sumo_reward_points_auto_redeem_error_message, .sumo_reward_points_manual_redeem_message, .sumo_reward_points_manual_redeem_error_message, .sumo_reward_point_hide_field_script, .sumo_reward_points_cart_apply_discount').remove();
                    //another_common_syntax(response);
                    location.reload();
                }
            });
        },
        quantity_update_cart: function (evt) {
            evt.preventDefault();

            var $form = $(evt.target);

            var $submit = $(document.activeElement);

            var $clicked = $('input[type=submit][clicked=true]');




            if ($clicked.is('[name="update_cart"]')) {
                this.update_cart();

            }
        },
        quantity_update: function () {
            $form = $('table.shop_table.cart').closest('form');
            block($('div.cart_totals'));

            var data = {
                action: 'sumo_updated_cart_total',
            };
            $.ajax({
                url: sumo_global_variable_js.wp_ajax_url,
                data: data,
                dataType: 'html',
                //async:false,
                success: function (response) {
                    $('.sumo_reward_points_info_message, .sumo_reward_points_complete_message, .sumo_reward_points_current_points_message, .sumo_reward_points_auto_redeem_message, .sumo_reward_points_auto_redeem_error_message, .sumo_reward_points_manual_redeem_message, .sumo_reward_points_manual_redeem_error_message, .sumo_reward_point_hide_field_script, .sumo_reward_points_cart_apply_discount').remove();
                    common_syntax(response);
                    //$('div.sumo_reward_points_info_message', sumomessage);
                    $(document.body).trigger('updated_cart_totals');
                },
                complete: function () {
                    unblock($form);
                    unblock($('div.cart_totals'));

                }
            });


        },
        /**
         * Update entire cart via ajax.
         */
        update_cart: function () {
            var $form = $('table.shop_table.cart').closest('form');

            block($form);
            block($('div.cart_totals'));

            // Make call to actual form post URL.
            $.ajax({
                type: $form.attr('method'),
                url: $form.attr('action'),
                data: $form.serialize(),
                dataType: 'html',
                success: function (response) {
                    update_wc_div(response);
                },
                complete: function () {
                    unblock($form);
                    unblock($('div.cart_totals'));
                    cart.quantity_update();
                }
            });
        },
        /**
         * Update the cart after something has changed.
         */
        update_cart_totals_sumo: function () {
            block($('div.cart_totals'));

            var data = {
                action: 'sumo_updated_cart_total',
            };
            $.ajax({
                url: sumo_global_variable_js.wp_ajax_url,
                data: data,
                dataType: 'html',
                success: function (response) {

                    $('.sumo_reward_points_info_message, .sumo_reward_points_complete_message, .sumo_reward_points_current_points_message, .sumo_reward_points_auto_redeem_message, .sumo_reward_points_auto_redeem_error_message, .sumo_reward_points_manual_redeem_message, .sumo_reward_points_manual_redeem_error_message, .sumo_reward_point_hide_field_script, .sumo_reward_points_cart_apply_discount').remove();
                    common_syntax(response);


                    //$('div.sumo_reward_points_info_message', sumomessage);
                    $(document.body).trigger('updated_cart_totals');
                }
            });
        },
        cart_apply_redeeming: function (evt) {

            evt.preventDefault();

            var $form = $(evt.target);

            var $submit = $(document.activeElement);

            var $clicked = $('input[type=submit][clicked=true]');

            if (0 === $form.find('table.shop_table.cart').length) {
                return false;
            }
            if (is_blocked($form)) {
                return false;
            }

            if ($clicked.is('[name="rs_apply_coupon_code"]') || $submit.is('#mainsubmi')) {
                this.apply_sumo_reward_points($form, '1');
            }
        },
        apply_redeeming_button: function (evt) {

            evt.preventDefault();

            var $form = $(evt.target);

            $($(evt.target).attr('disabled', 'disabled'));

            var $submit = $(document.activeElement);

            var $clicked = $('input[type=submit][clicked=true]');



            if ($clicked.is('[name="rs_apply_coupon_code1"]') && $submit.is("#mainsubmi")) {
                this.apply_sumo_reward_points($form, '1');
            }
        },
        /**
         * Special handling to identify which submit button was clicked.
         *
         * @param {Object} evt The JQuery event
         */
        submit_click: function (evt) {
            $('input[type=submit]', $(evt.target).parents('form')).removeAttr('clicked');
            $(evt.target).attr('clicked', 'true');
        },
        /**
         * Apply Coupon code
         *
         * @param {JQuery Object} $form The cart form.
         */
        apply_sumo_reward_points: function ($form, $data_variation) {

            $form = $('table.shop_table.cart').closest('form');
            block($form);
            //  block($('div.cart_totals'));
            var cart = this;
            var $text_field = $('#rs_apply_coupon_code_field');
            var coupon_code = $text_field.val();

            // if ($data_variation === '1') {
            var data = {
                //security: wc_cart_params.apply_coupon_nonce,
                rs_apply_coupon_code: "yes",
                rs_apply_coupon_code_field: coupon_code,
                action: 'apply_sumo_reward_points',
            };
            //}

            $.ajax({
                type: 'POST',
                url: sumo_global_variable_js.wp_ajax_url,
                data: data,
                dataType: 'html',
                success: function (response) {
                    $('.sumo_reward_points_info_message, .sumo_reward_points_complete_message, .sumo_reward_points_current_points_message, .sumo_reward_points_auto_redeem_message, .sumo_reward_points_auto_redeem_error_message, .sumo_reward_points_manual_redeem_message, .sumo_reward_points_manual_redeem_error_message, .sumo_reward_point_hide_field_script, .sumo_reward_points_cart_apply_discount').remove();
                    show_notice(response);
                    $(document.body).trigger('applied_coupon');
                },
                complete: function () {
                    unblock($form);
                    unblock($('div.cart_totals'));
                    $('div.rs_warning_message').hide();
                    //$text_field.val('');
                    cart.update_cart_totals_sumo();
                    cart.update_cart();
                    $('input[name="rs_apply_coupon_code1"]').removeAttr('disabled');
                    //cart.update_cart();
                }
            });
        },
    };
    cart.init();

});