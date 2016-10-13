<?php
$output = '';
if (function_exists('mk_love_this')) {
    ob_start();
    mk_love_this();
    $output = '<div class="mk-love-holder">' . ob_get_contents() . '</div>';
    ob_get_clean();
}

echo $output;
