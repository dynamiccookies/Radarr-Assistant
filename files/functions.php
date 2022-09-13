<?php
	function ifttt_api($ifttt_api_key, $ifttt_event, $value1 = null, $value2 = null, $value3 = null) {

		$query  = 'Congratulations!';
		$values = '{"value1":"' . $value1 . '","value2":"' . $value2 . '","value3":"' . $value3 . '"}';

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL            => 'https://maker.ifttt.com/trigger/' . $ifttt_event . '/with/key/' . $ifttt_api_key,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => '',
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => 'POST',
			CURLOPT_POSTFIELDS     => $values,
			CURLOPT_HTTPHEADER     => array('Content-Type: application/json'),
		));

		$response = curl_exec($curl);

		curl_close($curl);

		if (substr($response, 0, strlen($query)) === $query) {return true;}
		else {return false;}
	}
