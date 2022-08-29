<?php
    function add_movie($radarr_api_key, $ifttt_api_key, $ifttt_event, $tmdbid, $title) {

		$query  = 'Congratulations!';
        $values = '{"value1":"' . $radarr_api_key . '","value2":"' . $tmdbid . '","value3":"' . $title . '"}';

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

        if (substr($response, 0, strlen($query)) === $query) {return TRUE;}
        else {return FALSE;}
    }
