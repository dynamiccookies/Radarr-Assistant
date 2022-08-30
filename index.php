<?php 
    include 'admin/config.php';

    $movie_added = null;

    if (isset($_POST['add_movie']) && isset($_POST['tmdbId']) && isset($_POST['title'])) {
        include 'add_movie.php';
        $movie_added = add_movie($radarr_api_key, $ifttt_api_key, $ifttt_event, $_POST['tmdbId'], $_POST['title']);
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
		</script>
		<script src='script.js'></script>
		<title>Movie Search</title>
	</head>
	<body>
        <div class='heading'>Movie Search</div><br>
        <div class='container'>
            <input type='text' id='titleInput' placeholder='Movie Title' /><button id='search'><i class='fa fa-search'></i></button><button id='share'><i class='fa fa-share share' title='Share Search Results'></i></button>
        </div>
		<hr>
		<div id='movieDetails'></div>
	</body>
</html>
