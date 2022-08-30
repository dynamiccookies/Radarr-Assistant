<?php 
    include 'admin/config.php';

    $movie_added = null;
    $search_term = null;
    $tmdb_id     = null;

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
?>
<!doctype html>
<html>
	<head>
		<meta charset='utf-8'>
		<meta name='viewport' content='width=device-width, initial-scale=1'>
		<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
		<link rel='stylesheet' href='style.css'>
		<script src='http://code.jquery.com/jquery-latest.min.js'></script>
		<script>
			var radarrApiUrl = '<?=$radarr_api_url?>';
			var debug        = '<?=$debug?>';
			var movieAdded   = '<?=$movie_added?>';
			var searchTerm   = '<?=$search_term?>';
			var tmdbId       = '<?=$tmdb_id?>';
		</script>
		<script src='script.js'></script>
		<title>Movie Search</title>
	</head>
	<body>
        <div class='heading'>Movie Search</div><br>
        <div class='container'>
            <input type='text' id='titleInput' placeholder='Movie Title' />
            <button id='search'><i class='fa fa-search'></i></button>
            <button id='share'><i class='fa fa-share share' title='Share Search Results'></i></button>
        </div>
		<hr>
		<div id='movieDetails'></div>
	</body>
</html>
