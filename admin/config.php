<?php

	// Do not allow a direct connection to this file
	if (!isset($include)) {
		header('HTTP/1.0 403 Forbidden');
		exit;
	} else {unset($include);}

	// Declare and set global variables
	$debug          = FALSE; // Changing to TRUE enables console logging in browser for troubleshooting
	$https          = FALSE; // Change to TRUE if Radarr -> Settings (show advanced) -> 'Enable SSL' is enabled
	$ip             = '';    // Publicly accessible IP address
	$port           = '';    // Publicly accessible port (remember to open on your router)
	$radarr_api_key = '';    // Radarr API key (found in Radarr under Settings -> General
	$ifttt_api_key  = '';    // IFTTT API key

	// Set '$protocol' based on '$https' value
	$https ? $protocol = 'https' : $protocol = 'http';

	// The php variable '$apiUrl' is passed to the JavaScript 'apiUrl' variable in index.php
	$radarr_api_url = $protocol . '://' . $ip . ':' . $port . '/api/v3/movie/lookup?apikey=' . $radarr_api_key;
