<?php
 	require_once 'inc/MCAPI.class.php';
	$api = new MCAPI('8dac6920f54bdb011eed492ed859d63c-us12');	
	$merge_vars = Array( 
        'ZIP' => $_POST["ZIP"]
    );
    	
	$retval = $api->listSubscribe( 'aa97a97e80', $_POST["EMAIL"], $merge_vars, 'html', false, true );
	
	if ($api->errorCode){
		echo $api->errorMessage;
	} else {
		echo "success";
	}