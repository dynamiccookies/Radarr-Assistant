<?php 
    include 'admin/config.php';

    $issue_submitted = null;
    $movie_added     = null;
    $search_term     = null;
    $tmdb_id         = null;

    if (isset($_POST['add_movie']) && isset($_POST['tmdbId']) && isset($_POST['title'])) {
        include 'functions.php';
        $movie_added = ifttt_api($ifttt_api_key, 'movie_added', $radarr_api_key, $_POST['tmdbId'], $_POST['title']);
        $search_term = $_POST['searchTerm'];
        $tmdb_id     = $_POST['tmdbId'];
    }
    if (isset($_POST['readd_movie']) && isset($_POST['movieId'])) {
        include 'functions.php';
        $movie_added = ifttt_api($ifttt_api_key, 'movie_readded', $radarr_api_key, $_POST['movieId']);
        $search_term = $_POST['searchTerm'];
        $tmdb_id     = $_POST['tmdbId'];
    }
    if (isset($_POST['submit_issue'])) {
        include 'functions.php';
        $issue_submitted = ifttt_api($ifttt_api_key, 'issue', $_POST['subject'], $_POST['details']);
        $search_term     = $_POST['form_search_term'];
    }

    if ($movie_added) {
        $log_record = date('Y-m-d H:i:s') . ',' . $_SERVER['REMOTE_ADDR'] . ',' . $search_term . ',' . $tmdb_id . ',' . $_POST['title'];
        file_put_contents('admin/log.txt', $log_record.PHP_EOL, FILE_APPEND | LOCK_EX);
    }
?>
<!doctype html>
<html>
	<head>
		<meta charset='utf-8'>
		<meta name='viewport' content='width=device-width, initial-scale=1'>
		<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
		<link rel='stylesheet' href='style.css'>
		<script src='http://code.jquery.com/jquery-latest.min.js'></script>
		<script src='script.js'></script>
		<script>
			var radarrApiUrl = '<?=base64_encode($radarr_api_url)?>';
			var debug        = '<?=$debug?>';
			var movieAdded   = '<?=$movie_added?>';
			var searchTerm   = '<?=$search_term?>';
			var tmdbId       = '<?=$tmdb_id?>';
		</script>
		<title>Movie Search</title>
	</head>
	<body>
	    <div class='header'>
            <div class='issue-report' onclick="document.getElementById('issueForm').style.display='block';">Report Issue</div>
            <div class='heading' id='heading'>Movie Search</div><br>
            <div class='container'>
                <input type='text' id='titleInput' placeholder='Movie Title' />
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
