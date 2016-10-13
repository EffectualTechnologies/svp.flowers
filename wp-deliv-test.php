<?php
?>
<html>
<body>
<?php
	$params = array();
    $params["store_id"] = "19fc3813-b4d1-4930-b528-de37eac77142";
    $params["customer_zipcode"] = "20006"; 
    $params["ready_by"] = "2016-05-17T18:21:09Z"; 
 
 	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://sandbox.deliv.co/v2/delivery_estimates");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Api-Key: ae42cdacd35c5fb5fb995e88827202f7ce16'));
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	$result = curl_exec($ch);
	curl_close($ch);

	var_dump($result);
?>
</body>
</html>
