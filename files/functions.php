<?php

	// Do not allow a direct connection to this file
	if (!isset($include)) {
		header('HTTP/1.0 403 Forbidden');
		exit;
	} else {unset($include);}

	// Get the latest version from GitHub and return array of attributes
	function get_latest_version() {
		$github  = 'https://api.github.com/repos/dynamiccookies/Radarr-Assistant/releases';
		$json    = json_decode(file_get_contents($github, false, stream_context_create(array('http' => array('user_agent'=> $_SERVER['HTTP_USER_AGENT'])))), true);
		$version = array();

		foreach($json as $release) {
			array_push($version, (object)[
				'created'    => $release['created_at'],
				'draft'      => $release['draft'],
				'name'       => $release['name'],
				'prerelease' => $release['prerelease'],
				'published'  => $release['published_at'],
				'tag'        => $release['tag_name'],
				'url'        => $release['html_url']
			]);
		}

        return $version[0];
	}

	// Call a specific IFTTT API event and pass up to three values - Returns true or false based on whether the call was successful
	function ifttt_api($ifttt_api_key, $ifttt_event, $value1 = null, $value2 = null, $value3 = null) {

		$curl   = curl_init();
		$query  = 'Congratulations!';
		$values = '{"value1":"' . $value1 . '","value2":"' . $value2 . '","value3":"' . $value3 . '"}';

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

	// Upgrade the site from GitHub
	function upgrade() {
		try {

			$exclude_files = array('.gitignore', 'LICENSE', 'README.md', 'config.php');
			$repository    = 'https://github.com/dynamiccookies/Radarr-Assistant/';
			$source        = 'Radarr-Assistant-main';
			$file          = file_put_contents(dirname(__DIR__) . '/install.zip', fopen($repository . 'archive/main.zip', 'r'), LOCK_EX);

			if ($file === false) ifttt_api($ifttt_api_key, 'issue', 'Error Upgrading', 'Automatic upgrade failed at `$file === false`');

			$zip           = new ZipArchive;
			$results       = $zip->open(dirname(__DIR__) . '/install.zip');

			if ($results === true) {

				for ($i=0; $i<$zip->numFiles; $i++) {
					$name = $zip->getNameIndex($i);

					if (strpos($name, "{$source}/") !== 0) continue;

					$name_array = explode('/', $name);
					if (in_array(end($name_array), $exclude_files)) continue;

					$file = dirname(__DIR__) . '/' . substr($name, strlen($source) + 1);
					if (substr($file, -1) != '/') {
						$dir = dirname($file);
						if (!is_dir($dir)) mkdir($dir, 0777, true);
						$fread  = $zip->getStream($name);
						$fwrite = fopen($file, 'w');
						while ($data = fread($fread, 1024)) {fwrite($fwrite, $data);}
						fclose($fread);
						fclose($fwrite);
					}
				}

				$zip->close();
				unlink(dirname(__DIR__) . '/install.zip');

				echo "<meta http-equiv='refresh' content='0'>";
			} else {ifttt_api($ifttt_api_key, 'issue', 'Error Upgrading', 'Automatic upgrade failed at `$results === true -> else`');}
		} catch (Exception $e) {ifttt_api($ifttt_api_key, 'issue', 'Error Upgrading', 'Automatic upgrade failed in the try/catch. Error: ' . $e);}
	}
