<?php
	// Declare and set variables
	$debug  = FALSE; // Changing to TRUE enables console logging in browser for troubleshooting
	$https  = FALSE; // Change to TRUE if Radarr -> Settings (show advanced) -> 'Enable SSL' is enabled
	$ip     = '';    // Publicly accessible IP address
	$port   = '';    // Publicly accessible port (remember to open on your router
	$apiKey = '';    // Radarr API key (found in Radarr under Settings -> General

	// Set '$protocol' based on '$https' value
	$https ? $protocol = 'https' : $protocol = 'http';
	
	// The php variable '$apiUrl' is passed to the JavaScript 'apiUrl' variable in index.php
	$apiUrl = $protocol . '://' . $ip . ':' . $port . '/api/v3/movie/lookup?apikey=' . $apiKey;
