/* ==============================================
    Preload
=============================================== */
$ = jQuery.noConflict();
"use strict";

// Array foreach Prototype declare
(function(A) {

    if (!A.forEach) {
        A.forEach = A.forEach || function(action, that) {
            for (var i = 0, l = this.length; i < l; i++) {
                if (i in this) action.call(that, this[i], i, this);
            }
        };
    }

})(Array.prototype);


/* ==============================================
    Activate Pre-loader
=============================================== */
(function($) { 

    $(window).load( function() { // makes sure the whole site is loaded
        $('#status').fadeOut(); // will first fade out the loading animation
        $('#preloader').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website.
        $('body').delay(350).css({'overflow':'visible'});
    })

})( jQuery );


/* ==============================================
    Sticky nav
=============================================== */
(function($){ 

    $(window).scroll( function() {
        if ( $(this).scrollTop() > 10 ) {  
            $('header').addClass("sticky");
        } else {
            $('header').removeClass("sticky");
        }
    });

})( jQuery );


/* ==============================================
    Menu
=============================================== */
(function($){ 

    $('a.open_close').on( "click", function() {
        $('.main-menu').toggleClass('show');
        $('.layer').toggleClass('layer-is-visible');
    });

    $('.menu-item-has-children > a').on( "click", function(e) {
        $(this).next().toggleClass("show_normal");
    });

    $('.menu-item-has-children-mega > a').on( "click", function() {
        $(this).next().toggleClass("show_mega");
    });

    if( $(window).width() <= 480 ) {
        $('a.open_close').on( "click", function() {
            $('.cmn-toggle-switch').removeClass('active')
        });
    }

})( jQuery );


/* ==============================================
    Common
=============================================== */
(function($){ 

    // Tooltip
    $('.tooltip-1').tooltip( {html: true} );

    // Accordion
    function toggleChevron(e) {
        $(e.target)
            .prev('.panel-heading')
            .find("i.indicator")
            .toggleClass('icon-plus icon-minus');
    }
    $('.panel-group').on('hidden.bs.collapse shown.bs.collapse', toggleChevron);

    // Widget Recent Entries
    if ( $('.widget_recent_entries').length ) {
        $( '.widget_recent_entries > ul > li' ).each( function(index) {
            $(this).children('.post-date').after( $(this).children('a') );
        });
    }

    // Animation on scroll
    new WOW().init();

})( jQuery );


/* ==============================================
    Overlay mask form + incrementer
=============================================== */
(function($){ 

    $('.expose').on( "click", function(e) {
        $('#overlay i.animate-spin').hide();
        $(this).css('z-index','100');
        $('#overlay').fadeIn(300);
    });

    $('#overlay').on( "click", function(e) {
        $('#overlay i.animate-spin').show();

        $('#overlay').fadeOut( 300, function() {
            $('.expose').css( 'z-index','1' );
        });
    });


    // Popup box
    $(document).bind( 'keydown', function (e) {
        var key = e.keyCode;

        if ($(".opacity-overlay:visible").length > 0 && key === 27) {
            e.preventDefault();
            $(".opacity-overlay").fadeOut();
        }
    });

    $(document).on( "click", ".opacity-overlay", function(e) {
        if ( !$(e.target).is(".opacity-overlay .popup-content *") ) {
            $(".opacity-overlay").fadeOut();
        }
    });

})( jQuery );


/* ==============================================
    Video modal dialog + Parallax + Scroll to top + Incrementer
=============================================== */
(function($) {

    $('.video').magnificPopup({type:'iframe'}); /* video modal*/
	//$('.parallax-window').parallax({ speed: 1.0 }); /* Parallax modal*/
    $('.parallax-window').parallax({}); /* Parallax modal*/

    // Image popups
    $('.magnific-gallery').each(function() {
        $(this).magnificPopup({
            delegate: 'a', 
            type: 'image',
            gallery: { enabled: true }
        });
    }); 

    /* Top drodown prevent close*/
    $('.dropdown-menu').on( "click",function(e) {
        e.stopPropagation();
    });

    /* Hamburger icon*/
    var toggles = document.querySelectorAll(".cmn-toggle-switch"); 

    for (var i = toggles.length - 1; i >= 0; i--) {
        var toggle = toggles[i];
        toggleHandler(toggle);
    };

    function toggleHandler(toggle) {
        toggle.addEventListener( "click", function(e) {
            e.preventDefault();
            (this.classList.contains("active") === true) ? this.classList.remove("active") : this.classList.add("active");
        });
    };
      
    /* Scroll to top*/
    $(window).scroll( function() {
        if($(this).scrollTop() != 0) {
            $('#toTop').fadeIn();   
        } else {
            $('#toTop').fadeOut();
        }
    });

    $('#toTop').on( "click", function() {
        $('body, html').animate( {scrollTop: 0},500 );
    });

    /* Input incrementer*/
    $('.numbers-row').each( function() { 
        if ( ! $(this).find('.inc.button_inc').length ) { 
            $(this).append( '<div class="inc button_inc">+</div><div class="dec button_inc">-</div>' );
        }
    } );

    $(".numbers-row input").on( "change", function() {
        if ( $(this).parent().attr("data-max") && $(this).val() > $(this).parent().data('max') ) {
            $(this).val( $(this).parent().data('max') );
        }
        if ( $(this).parent().attr("data-min") && $(this).val() < $(this).parent().data('min') ) {
            $(this).val( $(this).parent().data('min') );
        }
    });

    $('body').on( 'click', '.button_inc', function () {

        var $button = $(this);
        var oldValue = $button.parent().find("input").val();

        if ( $button.text() == "+" ) {
            var max_val = 9999;
            if ( $(this).parent().attr("data-max") ) {
                max_val = $(this).parent().data("max");
            }
            if (oldValue < max_val) {
                var newVal = parseFloat(oldValue) + 1;
            } else {
                newVal = max_val;
            }
        } else {
            // Don't allow decrementing below zero
            var min_val = 0;
            if ( $(this).parent().attr("data-min") ) {
                min_val = $(this).parent().data("min");
            }
            if ( oldValue > min_val ) {
                var newVal = parseFloat(oldValue) - 1;
            } else {
                if ( $(this).parent() )
                newVal = min_val;
            }
        }
        if ( ! $button.parent().find("input").attr('disabled') ) { 
            $button.parent().find("input").val(newVal).change();
        }
    });

    $('.booking_time').timeDropper({
        setCurrentTime: false,
        meridians: true,
        primaryColor: "#e74e84",
        borderColor: "#e74e84",
        minutesInterval: '15'
    });


})( jQuery );

/* ==============================================
    Single Hotel Rooms Gallery Carousel
=============================================== */
(function($){ 

    if ( is_rtl === true ) { 
        is_rtl = true;
    } else {
        is_rtl = false;
    }

    if ( $("#single_hotel_desc .owl-carousel").length ) {
        $("#single_hotel_desc .owl-carousel").owlCarousel({ 
            rtl: is_rtl,
            autoplay: true,
            responsiveClass:true,
            responsive:{
                0:{
                    items:1
                },
                500:{
                    items:2
                },
                768:{
                    items:3
                },
                1200:{
                    items:4
                }
            }
        });
    }

})( jQuery );


/* ==============================================
    Reviews
=============================================== */
(function($){ 

    //reviews ajax loading
    $('.guest-reviews .more-review').click(function() {
        var $this = $(this);

        $.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                'action': 'get_more_reviews',
                'post_id' : $(this).data('post_id'),
                'last_no' : $('.guest-review').length
            },
            success: function(response){
                if ( response.success == 0 ) {
                    $('.more-review').remove();
                } else {
                    $('.guest-reviews').append( response.html );
                    $('.guest-reviews').append( $this );
                }
                    
                if ( response.more_reviews != 1 ) { 
                    $this.text( response.notice );
                    $this.attr('disabled', 'disabled');
                }
            }
        });

        return false;
    });

    $('#review-form').submit(function() {
        $('#message-review').hide();

        var ajax_data = $(this).serialize();

        $.ajax({
            url: ajaxurl,
            type: "POST",
            data: ajax_data,
            success: function(response){
                if (response.success == 1) {
                    $('#review-form').hide();
                    $('#message-review').html(response.result);
                    $('#myReviewLabel').html(response.title);
                    $('#message-review').show();
                } else {
                    $('#message-review').html(response.result);
                    $('#message-review').show();
                }
            }
        });

        return false;
    });

})( jQuery );


/* ==============================================
    Action for Wishlist button
=============================================== */
(function($){ 

    // load more button click action on search result page
    $("body").on( 'click', '.btn-add-wishlist', function(e) {
        e.preventDefault();

        $('#overlay i.animate-spin').show();
        $('#overlay').show();

        var $t = $(this);

        $.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                'action' : 'add_to_wishlist',
                'post_id' : $(this).data('post-id')
            },
            success: function(response){
                if (response.success == 1) {
                    $t.hide();
                    $t.siblings('.btn-remove-wishlist').show();
                } else {
                    alert(response.result);
                }
                $('#overlay').hide();
            }
        });
        return false;
    });

    // load more button click action on search result page
    $("body").on( 'click', '.btn-remove-wishlist', function(e) {
        e.preventDefault();

        $('#overlay i.animate-spin').show();
        $('#overlay').show();

        var $t = $(this);

        $.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                'action' : 'add_to_wishlist',
                'post_id' : $(this).data('post-id'),
                'remove' : 1
            },
            success: function(response){
                if (response.success == 1) {
                    $t.hide();
                    $t.siblings('.btn-add-wishlist').show();
                } else {
                    alert(response.result);
                }
                $('#overlay').hide();
            }
        });
        return false;
    });

})( jQuery );


/* ==============================================
    Filters on list page
=============================================== */
(function($){ 

    // Toggle Filters container
    $(window).bind( 'load', function() {
        if( $(this).width() < 991 ) {
            $('.collapse#collapseFilters').removeClass('in');
            $('.collapse#collapseFilters').addClass('out');
        } else {
            $('.collapse#collapseFilters').removeClass('out');
            $('.collapse#collapseFilters').addClass('in');
        }
    });

    $(document).ready( function() {

        $('.list-filter input').on( 'ifToggled', function() {
            var base_url = $(this).closest('ul').data('base-url').replace(/&amp;/g, '&');
            var new_url = base_url;
            var arg = $(this).closest('ul').data('arg');
            $(this).closest('ul').find('input:checked').each(function(index){
                if ( $(this).val() == -1 ) {new_url = base_url; return false;}
                new_url += '&' + arg + '[]=' + $(this).val();
                //new_url += '&' + arg + '%5B%5D=' + $(this).val();
            });
            if (new_url.indexOf("?") < 0) { new_url = new_url.replace(/&/, '?'); }
            window.location.href = new_url;
            return false;
        });

        $('#sort_price').change( function() {
            var base_url = $(this).data('base-url').replace(/&amp;/g, '&');
            if ( $(this).val() == "lower" ) {
                base_url += '&order_by=price&order=ASC';
            } else if ( $(this).val() == "higher" ) {
                base_url += '&order_by=price&order=DESC';
            }
            if (base_url.indexOf("?") < 0) { base_url = base_url.replace(/&/, '?'); }
            window.location.href = base_url;
            return false;
        });

        $('#sort_rating').change( function() {
            var base_url = $(this).data('base-url').replace( /&amp;/g, '&' );
            if ( $(this).val() == "lower" ) {
                base_url += '&order_by=rating&order=ASC';
            } else if ( $(this).val() == "higher" ) {
                base_url += '&order_by=rating&order=DESC';
            }
            if (base_url.indexOf("?") < 0) { base_url = base_url.replace(/&/, '?'); }
            window.location.href = base_url;
            return false;
        });

        if ( typeof cookie_notification != 'undefined' ) {
            $.cookieBar({
                    fixed: true,
                    message: cookie_notification.description, //Message displayed on bar
                    acceptText: cookie_notification.accept_text, //Text on accept/enable button
                    policyText: cookie_notification.policy_text, //Text on Privacy Policy button
                    policyURL: cookie_notification.url, //URL of Privacy Policy
                });
        }
    });

})( jQuery );


/* ==============================================
    SignUp and Login Form
=============================================== */
(function($){ 

    $('.signup-btn').click( function(e) {
        e.preventDefault();

        $('.loginform').hide();
        $('.signupform').show();

        return false;
    });

    $('.login-btn').click( function(e) {
        e.preventDefault();

        $('.loginform').show();
        $('.signupform').hide();

        return false;
    });

})( jQuery );


/* ==============================================
    Currency & Language Switcher
=============================================== */
(function($){ 

    $('.cl-switcher').change( function() {
        window.location.href = $(this).find(":selected").data('url');

        return false;
    });

})( jQuery );


/* ==============================================
    WooCommerce Single Product Image/Thumb slider
=============================================== */
function selectThumb( $images_slider, $thumbs_slider, index ) { 
    var len = $thumbs_slider.find('.owl-item').length,
        actives = [],
        i = 0,
        duration = 300;

    index = (index + len) % len;

    if ($images_slider) {
        $images_slider.trigger('to.owl.carousel', [index, duration, true]);
    }

    $thumbs_slider.find( '.owl-item' ).removeClass( 'selected' );
    $thumbs_slider.find( '.owl-item:eq(' + index + ')' ).addClass( 'selected' );
    $thumbs_slider.data( 'currentThumb', index );
    $thumbs_slider.find( '.owl-item.active' ).each( function() {
        actives[i++] = $(this).index();
    });

    if ( $.inArray( index, actives ) == -1 ) {
        if ( Math.abs(index - actives[0]) > Math.abs(index - actives[actives.length - 1]) ) {
            $thumbs_slider.trigger( 'to.owl.carousel', [(index - actives.length + 1) % len, duration, true] );
        } else {
            $thumbs_slider.trigger( 'to.owl.carousel', [index % len, duration, true] );
        }
    }
}

function wooProductImageSlider( $slider_container ) { 
    if ( $slider_container == undefined ) { 
        $slider_container = jQuery('.product-images-slider');
    }

    var $images_slider = $slider_container,
        $product = $images_slider.closest('.product-images'),
        $thumbs_slider = $product.find('.product-thumbs-slider'),
        thumbs_count = 4,
        currentSlide = 0,
        count = $images_slider.find('> *').length;

    $thumbs_slider.owlCarousel({ 
        rtl: is_rtl,
        loop : false,
        autoplay : false,
        items : thumbs_count,
        nav: false,
        navText: ["", ""],
        dots: false,
        rewind: true,
        stagePadding: 1,
        onInitialized: function() {
            selectThumb( null, $thumbs_slider, 0 );
            if ( $thumbs_slider.find('.owl-item').length >= thumbs_count )
                $thumbs_slider.append('<div class="thumb-nav"><div class="thumb-prev"></div><div class="thumb-next"></div></div>');
        }
    }).on('click', '.owl-item', function() {
        selectThumb( $images_slider, $thumbs_slider, $(this).index());
    });

    $thumbs_slider.on( 'click', '.thumb-prev', function(e) {
        var currentThumb = $thumbs_slider.data( 'currentThumb' );
        selectThumb( $images_slider, $thumbs_slider, --currentThumb );
    });

    $thumbs_slider.on( 'click', '.thumb-next', function(e) {
        var currentThumb = $thumbs_slider.data( 'currentThumb' );
        selectThumb( $images_slider, $thumbs_slider, ++currentThumb );
    });

    $images_slider.owlCarousel({
        rtl: is_rtl,
        loop : (count > 1) ? true : false,
        autoplay : false,
        items : 1,
        autoHeight : true,
        nav: true,
        navText: ["", ""],
        dots: false,
        rewind: true,
        onTranslate : function(event) {
            currentSlide = event.item.index - $images_slider.find('.cloned').length / 2;
            selectThumb( null, $thumbs_slider, currentSlide );
        }
    });
}

(function($, undefined){ 
    'use strict';

    $(window).load(function() { 
        wooProductImageSlider(); // Initialize Woo Product Image Slider
    });

})( jQuery );


/* ==============================================
    WooCommerce
=============================================== */
(function($, undefined){ 

    // Up-sells, Cross-sells and Related products slider
    if ( $('.related-products .owl-carousel').length > 0 ) { 
        $('.related-products .owl-carousel').each( function() { 

            var slider_options = $(this).data('slider'),
                slider_loop = false;

            if ( slider_options ) { 
                if ( slider_options.items === undefined ) { 
                    slider_options.items = 3;
                }

                if ( slider_options.slide_count === undefined ) { 
                    slider_options.slide_count = slider_options.items + 1;
                }

                slider_loop = ( slider_options.slide_count > slider_options.items )? true : false;

            $(this).owlCarousel({
                rtl : is_rtl,
                items : slider_options.items,
                loop : slider_loop,
                autoplay : true,
                autoplayTimeout : 4000,
                nav : true,
                    navText : ["", ""],
                    responsive : {
                        480 : {
                            items : 1,
                            nav : true
                        },
                        767 : {
                            items : 2,
                            nav : true
                        },
                        991 : {
                            items : slider_options.items,
                            nav : true
                        }
                    }
            });
            }

        });
    }

    $('.product-remove span.edit-product').click( function(e){
        window.open( $(this).attr('href') );
    });

    $(document).on( 'change', '.woocommerce-checkout #billing_country, .woocommerce-checkout #shipping_country, .shipping-calculator-form #calc_shipping_country', function(e) { 
        if ( $('#billing_state_field > input, #billing_state_field > select').length > 0 ) { 
            $('#billing_state_field > input, #billing_state_field > select').addClass( 'form-control' );
        }

        if ( $('#shipping_state_field > input, #shipping_state_field > select').length > 0 ) { 
            $('#shipping_state_field > input, #shipping_state_field > select').addClass( 'form-control' );
        }

        if ( $('#calc_shipping_state_field input, #calc_shipping_state_field select').length > 0 && !$('#calc_shipping_state_field input, #calc_shipping_state_field select').hasClass('form-control') ) {
            $('#calc_shipping_state_field input, #calc_shipping_state_field select').addClass( 'form-control' );
        }
    });

    // QuickView button action
    $(document).on( 'click', '.quickview_disabled', function(e) { 
        e.preventDefault();

        var pid = $(this).data('id');

        if ($('#ct_product_popup').length < 1) {
            $('<div class="opacity-overlay" id="ct_product_popup"><div class="container"><div class="popup-wrapper"><i class="icon-spin3 animate-spin"></i><div class="popup-content"></div></div></div></div>').appendTo('body');
        }

        $("#ct_product_popup").fadeIn();

        var product_content = null;

        $.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                'action' : 'ct_product_quickview',
                'pid' : pid
            },
            success: function(response){
                if (response.success == 1) {
                    $('#ct_product_popup .popup-content').html(response.output);

                    $popup_slider = $('#ct_product_popup .popup-content').find('.product-images-slider');
                    wooProductImageSlider($popup_slider);

                    $(".popup-content .numbers-row").append('<div class="inc button_inc">+</div><div class="dec button_inc">-</div>');
                } else {
                    alert( 'Please try again later' );
                }
            }
        });
    });

    // Price Filter on archive page
    if ( $('.widget.widget_price_filter .price_slider').length > 0 ) { 
        'use strict';

        var $price_slider = $('.widget.widget_price_filter .price_slider'),
            left = 0,
            width = 0;

        $price_slider.append( '<span class="price_from"></span>' );
        $price_slider.append( '<span class="price_to"></span>' );

        $('.widget.widget_price_filter .price_label .from').on( 'DOMSubtreeModified', function () {
            $price_slider.find('.price_from').text($(this).html());

            width = $price_slider.find('.price_from').width();
            left = $price_slider.find('span:nth-child(4)').css('left');
            left = parseInt( left.substring( 0, left.length - 2 ) ) - width/2;
            $price_slider.find('.price_from').css( 'left', left + 'px' );
        });

        $('.widget.widget_price_filter .price_label .to').on( 'DOMSubtreeModified', function () {
            $price_slider.find('.price_to').text( $(this).html() );

            width = $price_slider.find('.price_to').width();
            left = $price_slider.find('span:last-child').css('left');
            left = parseInt( left.substring( 0, left.length - 2 ) ) - width/2;
            $price_slider.find('.price_to').css( 'left', left + 'px' );
        });
    }

    // Mini Cart Scroll
    $(document).ready( function(){ 
        'use strict';

        if ( $('.dropdown-cart #cart_items').length > 0 ) { 
            var height = $('#cart_items .product_list_widget').height();
        }
    });

    // Ajax add-to-cart button
    $(document).on( 'added_to_cart', 'body', function( e, fragments, cart_hash, cart_btn ) { 
        'use strict';

        var $view_btn   = cart_btn.parent().find( '.added_to_cart' ),
            label       = $view_btn.html();

        $view_btn.html('<i class="fa fa-shopping-bag"></i><div class="tool-tip">' + label + '</div>');
        $view_btn.addClass('btn_shop');
        cart_btn.hide();
        
        if ( $('header #cart_items').length > 0 ) { 
            var nonce_value = $('header #cart_items #ajax_mini_cart').val();

            $.ajax({
                url: ajaxurl,
                type: "POST",
                data: {
                    'action': 'ct_ajax_mini_cart',
                    'nonce' : nonce_value
                },
                success: function( response ){
                    if ( response.success ) {
                        $('header .cart-item-qty').text( response.cart_qty );
                        $('#cart_items').html( response.mini_cart );
                    }
                }
            });
        }
    } );
})( jQuery );


/* ==============================================
    Smooth Page Scroll to finding section
=============================================== */
(function($){ 
    $('#faq_box a[href*=#]:not([href=#])').click(function() {
        if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
            
            if (target.length) {
                $('html,body').animate({
                    scrollTop: target.offset().top - 110
                }, 500);
                return false;
            }
        }
    });
})( jQuery );

/*
* Project: Bootstrap Notify = v3.1.5
* Description: Turns standard Bootstrap alerts into "Growl-like" notifications.
* Author: Mouse0270 aka Robert McIntosh
* License: MIT License
* Website: https://github.com/mouse0270/bootstrap-growl
*/

/* global define:false, require: false, jQuery:false */

(function (factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define(['jquery'], factory);
    } else if (typeof exports === 'object') {
        // Node/CommonJS
        factory(require('jquery'));
    } else {
        // Browser globals
        factory(jQuery);
    }
}(function ($) {
    // Create the defaults once
    var defaults = {
        element: 'body',
        position: null,
        type: "info",
        allow_dismiss: true,
        allow_duplicates: true,
        newest_on_top: false,
        showProgressbar: false,
        placement: {
            from: "top",
            align: "right"
        },
        offset: 20,
        spacing: 10,
        z_index: 1031,
        delay: 5000,
        timer: 1000,
        url_target: '_blank',
        mouse_over: null,
        animate: {
            enter: 'animated fadeInDown',
            exit: 'animated fadeOutUp'
        },
        onShow: null,
        onShown: null,
        onClose: null,
        onClosed: null,
        onClick: null,
        icon_type: 'class',
        template: '<div data-notify="container" class="customized_notify alert alert-{0}" role="alert"><button type="button" aria-hidden="true" class="close" data-notify="dismiss">&times;</button><i><span data-notify="icon"></span></i> <span data-notify="title">{1}</span> <span data-notify="message">{2}</span><div class="progress" data-notify="progressbar"><div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div></div><a href="{3}" target="{4}" data-notify="url"></a></div>'
    };

    String.format = function () {
        var args = arguments;
        var str = arguments[0];
        return str.replace(/(\{\{\d\}\}|\{\d\})/g, function (str) {
            if (str.substring(0, 2) === "{{") return str;
            var num = parseInt(str.match(/\d/)[0]);
            return args[num + 1];
        });
    };

    function isDuplicateNotification(notification) {
        var isDupe = false;

        $('[data-notify="container"]').each(function (i, el) {
            var $el = $(el);
            var title = $el.find('[data-notify="title"]').html().trim();
            var message = $el.find('[data-notify="message"]').html().trim();

            // The input string might be different than the actual parsed HTML string!
            // (<br> vs <br /> for example)
            // So we have to force-parse this as HTML here!
            var isSameTitle = title === $("<div>" + notification.settings.content.title + "</div>").html().trim();
            var isSameMsg = message === $("<div>" + notification.settings.content.message + "</div>").html().trim();
            var isSameType = $el.hasClass('alert-' + notification.settings.type);

            if (isSameTitle && isSameMsg && isSameType) {
                //we found the dupe. Set the var and stop checking.
                isDupe = true;
            }
            return !isDupe;
        });

        return isDupe;
    }

    function Notify(element, content, options) {
        // Setup Content of Notify
        var contentObj = {
            content: {
                message: typeof content === 'object' ? content.message : content,
                title: content.title ? content.title : '',
                icon: content.icon ? content.icon : '',
                url: content.url ? content.url : '#',
                target: content.target ? content.target : '-'
            }
        };

        options = $.extend(true, {}, contentObj, options);
        this.settings = $.extend(true, {}, defaults, options);
        this._defaults = defaults;
        if (this.settings.content.target === "-") {
            this.settings.content.target = this.settings.url_target;
        }
        this.animations = {
            start: 'webkitAnimationStart oanimationstart MSAnimationStart animationstart',
            end: 'webkitAnimationEnd oanimationend MSAnimationEnd animationend'
        };

        if (typeof this.settings.offset === 'number') {
            this.settings.offset = {
                x: this.settings.offset,
                y: this.settings.offset
            };
        }

        //if duplicate messages are not allowed, then only continue if this new message is not a duplicate of one that it already showing
        if (this.settings.allow_duplicates || (!this.settings.allow_duplicates && !isDuplicateNotification(this))) {
            this.init();
        }
    }

    $.extend(Notify.prototype, {
        init: function () {
            var self = this;

            this.buildNotify();
            if (this.settings.content.icon) {
                this.setIcon();
            }
            if (this.settings.content.url != "#") {
                this.styleURL();
            }
            this.styleDismiss();
            this.placement();
            this.bind();

            this.notify = {
                $ele: this.$ele,
                update: function (command, update) {
                    var commands = {};
                    if (typeof command === "string") {
                        commands[command] = update;
                    } else {
                        commands = command;
                    }
                    for (var cmd in commands) {
                        switch (cmd) {
                            case "type":
                                this.$ele.removeClass('alert-' + self.settings.type);
                                this.$ele.find('[data-notify="progressbar"] > .progress-bar').removeClass('progress-bar-' + self.settings.type);
                                self.settings.type = commands[cmd];
                                this.$ele.addClass('alert-' + commands[cmd]).find('[data-notify="progressbar"] > .progress-bar').addClass('progress-bar-' + commands[cmd]);
                                break;
                            case "icon":
                                var $icon = this.$ele.find('[data-notify="icon"]');
                                if (self.settings.icon_type.toLowerCase() === 'class') {
                                    $icon.removeClass(self.settings.content.icon).addClass(commands[cmd]);
                                } else {
                                    if (!$icon.is('img')) {
                                        $icon.find('img');
                                    }
                                    $icon.attr('src', commands[cmd]);
                                }
                                self.settings.content.icon = commands[command];
                                break;
                            case "progress":
                                var newDelay = self.settings.delay - (self.settings.delay * (commands[cmd] / 100));
                                this.$ele.data('notify-delay', newDelay);
                                this.$ele.find('[data-notify="progressbar"] > div').attr('aria-valuenow', commands[cmd]).css('width', commands[cmd] + '%');
                                break;
                            case "url":
                                this.$ele.find('[data-notify="url"]').attr('href', commands[cmd]);
                                break;
                            case "target":
                                this.$ele.find('[data-notify="url"]').attr('target', commands[cmd]);
                                break;
                            default:
                                this.$ele.find('[data-notify="' + cmd + '"]').html(commands[cmd]);
                        }
                    }
                    var posX = this.$ele.outerHeight() + parseInt(self.settings.spacing) + parseInt(self.settings.offset.y);
                    self.reposition(posX);
                },
                close: function () {
                    self.close();
                }
            };

        },
        buildNotify: function () {
            var content = this.settings.content;
            this.$ele = $(String.format(this.settings.template, this.settings.type, content.title, content.message, content.url, content.target));
            this.$ele.attr('data-notify-position', this.settings.placement.from + '-' + this.settings.placement.align);
            if (!this.settings.allow_dismiss) {
                this.$ele.find('[data-notify="dismiss"]').css('display', 'none');
            }
            if ((this.settings.delay <= 0 && !this.settings.showProgressbar) || !this.settings.showProgressbar) {
                this.$ele.find('[data-notify="progressbar"]').remove();
            }
        },
        setIcon: function () {
            if (this.settings.icon_type.toLowerCase() === 'class') {
                this.$ele.find('[data-notify="icon"]').addClass(this.settings.content.icon);
            } else {
                if (this.$ele.find('[data-notify="icon"]').is('img')) {
                    this.$ele.find('[data-notify="icon"]').attr('src', this.settings.content.icon);
                } else {
                    this.$ele.find('[data-notify="icon"]').append('<img src="' + this.settings.content.icon + '" alt="Notify Icon" />');
                }
            }
        },
        styleDismiss: function () {
            this.$ele.find('[data-notify="dismiss"]').css({
                position: 'absolute',
                right: '10px',
                top: '5px',
                zIndex: this.settings.z_index + 2
            });
        },
        styleURL: function () {
            this.$ele.find('[data-notify="url"]').css({
                backgroundImage: 'url(data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7)',
                height: '100%',
                left: 0,
                position: 'absolute',
                top: 0,
                width: '100%',
                zIndex: this.settings.z_index + 1
            });
        },
        placement: function () {
            var self = this,
                offsetAmt = this.settings.offset.y,
                css = {
                    display: 'inline-block',
                    margin: '0px auto',
                    position: this.settings.position ? this.settings.position : (this.settings.element === 'body' ? 'fixed' : 'absolute'),
                    transition: 'all .5s ease-in-out',
                    zIndex: this.settings.z_index
                },
                hasAnimation = false,
                settings = this.settings;

            $('[data-notify-position="' + this.settings.placement.from + '-' + this.settings.placement.align + '"]:not([data-closing="true"])').each(function () {
                offsetAmt = Math.max(offsetAmt, parseInt($(this).css(settings.placement.from)) + parseInt($(this).outerHeight()) + parseInt(settings.spacing));
            });
            if (this.settings.newest_on_top === true) {
                offsetAmt = this.settings.offset.y;
            }
            css[this.settings.placement.from] = offsetAmt + 'px';

            switch (this.settings.placement.align) {
                case "left":
                case "right":
                    css[this.settings.placement.align] = this.settings.offset.x + 'px';
                    break;
                case "center":
                    css.left = 0;
                    css.right = 0;
                    break;
            }
            this.$ele.css(css).addClass(this.settings.animate.enter);
            $.each(Array('webkit-', 'moz-', 'o-', 'ms-', ''), function (index, prefix) {
                self.$ele[0].style[prefix + 'AnimationIterationCount'] = 1;
            });

            $(this.settings.element).append(this.$ele);

            if (this.settings.newest_on_top === true) {
                offsetAmt = (parseInt(offsetAmt) + parseInt(this.settings.spacing)) + this.$ele.outerHeight();
                this.reposition(offsetAmt);
            }

            if ($.isFunction(self.settings.onShow)) {
                self.settings.onShow.call(this.$ele);
            }

            this.$ele.one(this.animations.start, function () {
                hasAnimation = true;
            }).one(this.animations.end, function () {
                self.$ele.removeClass(self.settings.animate.enter);
                if ($.isFunction(self.settings.onShown)) {
                    self.settings.onShown.call(this);
                }
            });

            setTimeout(function () {
                if (!hasAnimation) {
                    if ($.isFunction(self.settings.onShown)) {
                        self.settings.onShown.call(this);
                    }
                }
            }, 600);
        },
        bind: function () {
            var self = this;

            this.$ele.find('[data-notify="dismiss"]').on('click', function () {
                self.close();
            });

            if ($.isFunction(self.settings.onClick)) {
                this.$ele.on('click', function (event) {
                    if (event.target != self.$ele.find('[data-notify="dismiss"]')[0]) {
                        self.settings.onClick.call(this, event);
                    }
                });
            }

            this.$ele.mouseover(function () {
                $(this).data('data-hover', "true");
            }).mouseout(function () {
                $(this).data('data-hover', "false");
            });
            this.$ele.data('data-hover', "false");

            if (this.settings.delay > 0) {
                self.$ele.data('notify-delay', self.settings.delay);
                var timer = setInterval(function () {
                    var delay = parseInt(self.$ele.data('notify-delay')) - self.settings.timer;
                    if ((self.$ele.data('data-hover') === 'false' && self.settings.mouse_over === "pause") || self.settings.mouse_over != "pause") {
                        var percent = ((self.settings.delay - delay) / self.settings.delay) * 100;
                        self.$ele.data('notify-delay', delay);
                        self.$ele.find('[data-notify="progressbar"] > div').attr('aria-valuenow', percent).css('width', percent + '%');
                    }
                    if (delay <= -(self.settings.timer)) {
                        clearInterval(timer);
                        self.close();
                    }
                }, self.settings.timer);
            }
        },
        close: function () {
            var self = this,
                posX = parseInt(this.$ele.css(this.settings.placement.from)),
                hasAnimation = false;

            this.$ele.attr('data-closing', 'true').addClass(this.settings.animate.exit);
            self.reposition(posX);

            if ($.isFunction(self.settings.onClose)) {
                self.settings.onClose.call(this.$ele);
            }

            this.$ele.one(this.animations.start, function () {
                hasAnimation = true;
            }).one(this.animations.end, function () {
                $(this).remove();
                if ($.isFunction(self.settings.onClosed)) {
                    self.settings.onClosed.call(this);
                }
            });

            setTimeout(function () {
                if (!hasAnimation) {
                    self.$ele.remove();
                    if (self.settings.onClosed) {
                        self.settings.onClosed(self.$ele);
                    }
                }
            }, 600);
        },
        reposition: function (posX) {
            var self = this,
                notifies = '[data-notify-position="' + this.settings.placement.from + '-' + this.settings.placement.align + '"]:not([data-closing="true"])',
                $elements = this.$ele.nextAll(notifies);
            if (this.settings.newest_on_top === true) {
                $elements = this.$ele.prevAll(notifies);
            }
            $elements.each(function () {
                $(this).css(self.settings.placement.from, posX);
                posX = (parseInt(posX) + parseInt(self.settings.spacing)) + $(this).outerHeight();
            });
        }
    });

    $.notify = function (content, options) {
        var plugin = new Notify(this, content, options);
        return plugin.notify;
    };
    $.notifyDefaults = function (options) {
        defaults = $.extend(true, {}, defaults, options);
        return defaults;
    };

    $.notifyClose = function (selector) {

        if (typeof selector === "undefined" || selector === "all") {
            $('[data-notify]').find('[data-notify="dismiss"]').trigger('click');
        }else if(selector === 'success' || selector === 'info' || selector === 'warning' || selector === 'danger'){
            $('.alert-' + selector + '[data-notify]').find('[data-notify="dismiss"]').trigger('click');
        } else if(selector){
            $(selector + '[data-notify]').find('[data-notify="dismiss"]').trigger('click');
        }
        else {
            $('[data-notify-position="' + selector + '"]').find('[data-notify="dismiss"]').trigger('click');
        }
    };

    $.notifyCloseExcept = function (selector) {

        if(selector === 'success' || selector === 'info' || selector === 'warning' || selector === 'danger'){
            $('[data-notify]').not('.alert-' + selector).find('[data-notify="dismiss"]').trigger('click');
        } else{
            $('[data-notify]').not(selector).find('[data-notify="dismiss"]').trigger('click');
        }
    };
}));