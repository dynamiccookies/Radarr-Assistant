<?php 

	$include = true;
	require_once 'admin/config.php';

	$include = true;
	require_once 'files/functions.php';

	// Declare and set global PHP variables
	$installed_version = 'v0.1.1-alpha';
	$issue_submitted   = null;
	$latest_version    = get_latest_version();
	$movie_added       = null;
	$search_term       = null;
	$tmdb_id           = null;
	$upgrade_needed    = version_compare($latest_version->tag, $installed_version, '>');

	// If a newer release exists, run the upgrade() function
    if ($upgrade_needed) upgrade();

	// If the add_movie button was pressed and it included a movie tmdbId and title...
	if (isset($_POST['add_movie']) && isset($_POST['tmdbId']) && isset($_POST['title'])) {

		// Invoke the ifttt_api() function, passing data to add a new movie - Returns true or false
		$movie_added = ifttt_api($ifttt_api_key, 'movie_added', $radarr_api_key, $_POST['tmdbId'], $_POST['title']);
		$search_term = $_POST['searchTerm'];
		$tmdb_id     = $_POST['tmdbId'];
	}

	// If the readd_movie button was pressed and it included a movie tmdbId...
	if (isset($_POST['readd_movie']) && isset($_POST['movieId'])) {

		// Invoke the ifttt_api() function, passing data to readd a movie - Returns true or false
		$movie_added = ifttt_api($ifttt_api_key, 'movie_readded', $radarr_api_key, $_POST['movieId']);
		$search_term = $_POST['searchTerm'];
		$tmdb_id     = $_POST['tmdbId'];
	}

	// If the submit_issue button was pressed...
	if (isset($_POST['submit_issue'])) {

		// Invoke the ifttt_api() function, passing data to post an issue to GitHub - Returns true or false
		$issue_submitted = ifttt_api($ifttt_api_key, 'issue', $_POST['subject'], $_POST['details']);
		$search_term     = $_POST['form_search_term'];
	}

	// If a movie was added or readded and the ifttt_api() function returned true...
	if ($movie_added) {

		// Set the timezone to CST
		date_default_timezone_set('America/Chicago');

		// Build a pipe delimited string with data about the movie (re)added
		$log_record = date('Y-m-d H:i:s') . '|' . $_SERVER['REMOTE_ADDR'] . '|' . $search_term . '|' . $tmdb_id . '|' . $_POST['title'];

		// Append $log_record string to the log.txt file
		file_put_contents('admin/log.txt', $log_record.PHP_EOL, FILE_APPEND | LOCK_EX);
	}
?>
<!doctype html>
<html>
	<head>
		<meta charset='utf-8'>
		<meta name='viewport' content='width=device-width, initial-scale=1'>
		<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
		<link rel='stylesheet' href='files/style.css'>
		<script src='http://code.jquery.com/jquery-latest.min.js'></script>
		<script src='files/script.js'></script>
		<script>
			const debug        = '<?=$debug?>';
			const movieAdded   = '<?=$movie_added?>';
			const radarrApiUrl = '<?=base64_encode($radarr_api_url)?>';
			let   searchTerm   = '<?=$search_term?>';
			const tmdbId       = '<?=$tmdb_id?>';
		</script>
		<title>Movie Search</title>
	</head>
	<body>
		<div class='header'>
			<div class='version'><a href='https://github.com/dynamiccookies/Radarr-Assistant/releases/tag/<?=$installed_version?>' target='_blank'><?=$installed_version?></a></div>
			<div class='issue-report' onclick="document.getElementById('issueForm').style.display='block';">Report Issue</div>
			<div class='heading' id='heading'>Movie Search</div><br>
			<div class='container'>
				<input type='text' id='titleInput' placeholder='Movie Title' accesskey='M' autofocus>
				<button id='search'><i class='fa fa-search'></i></button>
				<button id='share'><i class='fa fa-share share' title='Share Search Results'></i></button>
			</div>
			<hr>
		</div>
		<div id='movieDetails'></div>
		<form method='post' id='issueForm' class='form-container'>
			<label for='subject'>Issue or Request</label>
			<input class='input' type='text' name='subject' required>
			<label for='details'>Details</label>
			<textarea class='input' placeholder='Describe the Issue or Feature Request' name='details' cols='30' rows='5'></textarea>
			<input type='hidden' id='form_search_term' name='form_search_term' value=''>
			<button type='submit' class='issue-button issue-submit' name='submit_issue'>Submit</button>
			<button type='button' class='issue-button issue-close' onclick="document.getElementById('issueForm').style.display='none';">Close</button>
		</form>
	</body>
</html>
