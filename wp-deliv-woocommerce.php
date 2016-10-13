<?php
/**
 * Webhook to send data to Deliv.co
*/

$params = array("id" => "19fc3813-b4d1-4930-b528-de37eac77142");
$createddate= strtotime($_POST["order"]["created_at"]);
$params["ready_by"] = date('c', strtotime("next Thursday",$createddate)); 
$params["customer_zipcode"] = $_POST["order"]["shipping_address"]["postcode"];
  $response= deliv_submit("https://api.deliv.co/v2/delivery_estimates", $params);
	var_dump($response);
	function deliv_submit($url, array $parameters = array(), $http_method = 'POST', $form_content_type = 1){
		$curl_options = array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_CUSTOMREQUEST  => $http_method, 
			CURLOPT_HTTPHEADER => array('Content-Type:application/json',
			'Api-Key:ae42cdacd35c5fb5fb995e88827202f7ce16',
			'Content-Length: ' . strlen(json_encode($parameters)))
		);

		switch($http_method){
			case 'POST':
				$curl_options[CURLOPT_POSTFIELDS] = json_encode($parameters);
				break;
			case 'GET':
				$url .= '?' . http_build_query($parameters, null, '&');
				break;
			default:
				break;
		}

		$curl_options[CURLOPT_URL] = $url;

		$ch = curl_init();
		curl_setopt_array($ch, $curl_options);
	
		$result = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
		curl_close($ch);

		return array(
						'code' => $http_code,
						'result' => $result
						);
	}
?>
