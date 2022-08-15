<?php include 'admin/config.php';?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
		<link rel='stylesheet' href='style.css'>
		<script src='http://code.jquery.com/jquery-latest.min.js'></script>
		<script>var apiUrl = '<?=$apiUrl?>';</script>
		<script src='script.js'></script>
		<title>Movie Search</title>
	</head>
	<body>
        <h1>Movie Search</h1>
        <div class='container'>
            <input type="text" id="titleInput" accesskey="M" placeholder='Movie Title' /><button id="search" accesskey="N" style="width:135px"><i class="fa fa-search"></i></button>
        </div>
		<hr width="100%">
		<div id="movieDetails"></div>
	</body>
</html>
