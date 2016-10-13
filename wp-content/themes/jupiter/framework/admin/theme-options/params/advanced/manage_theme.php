<?php
$advanced_section[] = array(
    "type" => "sub_group",
    "id" => "mk_options_manage_theme",
    "name" => __("Advanced / Manage Theme", "mk_framework") ,
    "desc" => __("", "mk_framework") ,
    "fields" => array(
        array(
            "name" => __("Google Page Speed Optimization (Beta feature)", "mk_framework") ,
            "desc" => __("Optimizes your web-site assets for Google in order to get higher points and rankings.
            Before enabling this option make sure that you have : <br />
            1- Properly formatted coding tags. <br />
            2- Powerful hosting. This option consumes server resources greedily.  <br />
            3- Proper Caching and Minifying plug-ins. We also recommend Super Cache and Super Minify plug-ins, which is free.<br />
            This feature is yet in beta stage. Do not use this in a production website.
            ", "mk_framework") ,
            "id" => "pagespeed-optimization",
            "default" => "false",
            "type" => "toggle"
        ),
        array(
            "name" => __("Minify Theme Javascript File", "mk_framework") ,
            "desc" => __("If you enable this option pre-minified theme Java Script files will be renderred in front-end. Minified JS is 30%-40% smaller in file size which will improve page speed.", "mk_framework") ,
            "id" => "minify-js",
            "default" => "false",
            "type" => "toggle"
        ) ,
        array(
            "name" => __("Minify Theme Styles Files", "mk_framework") ,
            "desc" => __("If you enable this option pre-minified theme CSS files will be renderred in front-end. Minified CSS is 30%-40% smaller in file size which will improve page speed.", "mk_framework") ,
            "id" => "minify-css",
            "default" => "true",
            "type" => "toggle"
        ) ,
        array(
            "name" => __("Query String From Static Flies", "mk_framework") ,
            "desc" => __("Disabling this option will remove \"ver\" query string from JS and CSS files. For More info Please <a target=\"_blank\" href=\"https://developers.google.com/speed/docs/best-practices/caching#LeverageProxyCaching\">Read Here</a>.", "mk_framework") ,
            "id" => "remove-js-css-ver",
            "default" => "true",
            "type" => "toggle"
        ) ,
        array(
            "name" => __("Portfolio Post Type", "mk_framework") ,
            "desc" => __("If you will not use Portfolio post type feature simply disable this option.", "mk_framework") ,
            "id" => "portfolio-post-type",
            "default" => "true",
            "type" => "toggle"
        ) ,
        array(
            "name" => __("News Post Type", "mk_framework") ,
            "desc" => __("If you will not use News post type feature simply disable this option.", "mk_framework") ,
            "id" => "news-post-type",
            "default" => "true",
            "type" => "toggle"
        ) ,
        array(
            "name" => __("FAQ Post Type", "mk_framework") ,
            "desc" => __("If you will not use faq post type feature simply disable this option.", "mk_framework") ,
            "id" => "faq-post-type",
            "default" => "true",
            "type" => "toggle"
        ) ,
       /* array(
            "name" => __("Timeline Post Type", "mk_framework") ,
            "desc" => __("If you will not use timeline post type feature simply disable this option.", "mk_framework") ,
            "id" => "timeline-post-type",
            "default" => "true",
            "type" => "toggle"
        ) ,*/

        array(
            "name" => __("Photo Album Post Type", "mk_framework") ,
            "desc" => __("If you will not use Photo Album post type feature simply disable this option.", "mk_framework") ,
            "id" => "photo_album-post-type",
            "default" => "true",
            "type" => "toggle"
        ) ,
        
        array(
            "name" => __("Pricing Tables Post Type", "mk_framework") ,
            "desc" => __("If you will not use Pricing Tables post type feature simply disable this option.", "mk_framework") ,
            "id" => "pricing-post-type",
            "default" => "true",
            "type" => "toggle"
        ) ,
        array(
            "name" => __("Clients Post Type", "mk_framework") ,
            "desc" => __("If you will not use Clients post type feature simply disable this option.", "mk_framework") ,
            "id" => "clients-post-type",
            "default" => "true",
            "type" => "toggle"
        ) ,
        array(
            "name" => __("Employees Post Type", "mk_framework") ,
            "desc" => __("If you will not use Employees post type feature simply disable this option.", "mk_framework") ,
            "id" => "employees-post-type",
            "default" => "true",
            "type" => "toggle"
        ) ,
        array(
            "name" => __("Testimonial Post Type", "mk_framework") ,
            "desc" => __("If you will not use Testimonial post type feature simply disable this option.", "mk_framework") ,
            "id" => "testimonial-post-type",
            "default" => "true",
            "type" => "toggle"
        ) ,
        array(
            "name" => __("FlexSlider Post Type", "mk_framework") ,
            "desc" => __("If you will not use FlexSlider post type feature simply disable this option.", "mk_framework") ,
            "id" => "slideshow-post-type",
            "default" => "false",
            "type" => "toggle"
        ) ,
        array(
            "name" => __("Edge Slideshow Post Type", "mk_framework") ,
            "desc" => __("If you will not use Edge Slideshow post type feature simply disable this option.", "mk_framework") ,
            "id" => "edge-post-type",
            "default" => "true",
            "type" => "toggle"
        ) ,
        array(
            "name" => __("Banner Builder Post Type", "mk_framework") ,
            "desc" => __("If you will not use Banner Builder post type feature simply disable this option.", "mk_framework") ,
            "id" => "banner_builder-post-type",
            "default" => "false",
            "type" => "toggle"
        ) ,
        
        array(
            "name" => __("Tab Slider Post Type", "mk_framework") ,
            "desc" => __("If you will not use Tab Slider post type feature simply disable this option.", "mk_framework") ,
            "id" => "tab_slider-post-type",
            "default" => "true",
            "type" => "toggle"
        ),
        array(
            "name" => __("Animated Columns Post Type", "mk_framework") ,
            "desc" => __("If you will not use Animated Columns post type feature simply disable this option.", "mk_framework") ,
            "id" => "animated-columns-post-type",
            "default" => "true",
            "type" => "toggle"
        ),
        array(
            "name" => __("Disable Dynamic Assets", "mk_framework") ,
            "desc" => __("If you disable this option your web site will serve all assets even it's not needed. In some exceptional cases dynamic assets can cause problems with your hosting such as:  <br />
            1- Unproperly customised asset files. <br />
            2- Unsufficient shared hosting resources.  <br />
            3- Weird caching settings by hosting environment. <br />
            ", "mk_framework") ,
            "id" => "disable-dynamic-assets",
            "default" => "false",
            "type" => "toggle"
        ),
    ) ,
);
