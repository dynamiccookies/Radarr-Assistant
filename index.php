<?php include 'admin/config.php';?>
<!doctype html>
<html>
	<head>
		<meta charset='utf-8'>
		<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
		<link rel='stylesheet' href='style.css'>
		<script src='http://code.jquery.com/jquery-latest.min.js'></script>
		<script>
			var apiUrl = '<?=$apiUrl?>';
			var debug  = '<?=$debug?>';
		</script>
		<script src='script.js'></script>
		<title>Movie Search</title>
	</head>
	<body>
        <div class='heading'>Movie Search</div><br>
        <div class='container'>
            <input type='text' id='titleInput' placeholder='Movie Title' /><button id='search'><i class='fa fa-search'></i></button>
        </div>
		<hr>
		<div id='movieDetails'></div>
	</body>
</html>
