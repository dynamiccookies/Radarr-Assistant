<?php
    include 'admin/config.php';
?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
		<link rel='stylesheet' href='style.css'>
		<script src='http://code.jquery.com/jquery-latest.min.js'></script>
		<title>Movie Search</title>
		<script>
			var maxHeight = 0;
			var apiUrl = '<?php echo $apiUrl;?>';
			$(document).ready(function() {
				$('#search').click(searchNewMovies);
				$('.container').keypress(function(e){
					if (e.keyCode == 13){    //Enter key pressed
						e.preventDefault(); //Required to stop page from reloading on Enter
						searchNewMovies();  //Trigger search button click event
					}
				});
			});

			function searchNewMovies() {

				// Show loading spinner on screen while search completes
                // Pure CSS loading spinner from loading.io
                $("#movieDetails").html("<div class='container'><div class='lds-spinner center'><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>");

				// Declare/set variables and build API URL string based on search term
				var searchTerm = document.getElementById('titleInput').value;
				var url        = apiUrl + '&term=' + searchTerm;

				// Call API to retreive data
				$.getJSON(url, function(data) {

					// Clear loading spinner from screen
					$("#movieDetails").empty();

                    // Check for results from API, return error if none
                    if (!data.length) {
                        $('#movieDetails').html("<h2 class='noresults'>No Results Found - Please try changing your search criteria</h2>");
                        return;
                    }

					// Reverse sort results by year - newest first
					data.sort(function(a,b) {return a.year-b.year}).reverse();

					// Loop through results, 
					for (var i in data) {
						var exists;
						var file    = data[i].hasFile;
						var image   = data[i].remotePoster;
						var imdb    = data[i].imdbId;
						var plot    = data[i].overview;
						var title   = data[i].title;
						var tmdb    = data[i].tmdbId;
						var year    = data[i].year;
						var youtube = data[i].youTubeTrailerId;

                        // Only create movie container if the IMDb AND TMDB IDs exist
                        if (imdb && tmdb) {

							// Build HTML container with movie details and append container to screen
    						$('#movieDetails').append(
    
    						    // Create container for movie details
    							"<div class='film center' id='filmID" + i + "'>" +
    
    								// If file exists in library, show green checkmark icon
    								(file ? "<img src='img/dwcheckyes.png' title='Already in Library' class='checkmark'>" : '') +

                                    // Build movie title string
                                    (file ? "<a href='https://app.plex.tv/desktop/#!/search?query=" + searchTerm + "' target='_blank'><strong>" + title + (year ? ' (' + year + ')' : '') + '</strong></a>' : '<strong>' + title + (year ? ' (' + year + ')' : '') + '</strong>') +
    
                                    // Build movie poster image string if exists, else show 'undefined' image that contains 'No Image' text
    								"<br><img src='" + image + "' class='poster' alt='Movie poster' title='Movie Poster' onerror=\"this.onerror=null;this.src='img/undefined.png';\"><br>" +

                                    // Build IMDb image link string
                                    "<a href='https://www.imdb.com/title/" + imdb + "' target='_blank' title='Internet Movie Database'><img src='img/imdb_small.png' alt='IMDb logo' class='site'></a>" +
    
                                    // Build TMDB image link string
                                    "<a href='https://www.themoviedb.org/movie/" + tmdb + "' target='_blank' title='The Movie Database'><img src='img/tmdb_small.png' alt='TMDB logo' class='site'></a>" +
    
                                    // Build YouTube trailer link string
                                    "<a href='https://youtu.be/" + youtube + "' target='_blank' title='Movie Trailer'><img src='img/youtube_small.png' alt='YouTube logo' class='site'></a>" +
    
    								"<p style='text-align:left;'>" + plot + "</p>" +
    							"</div>"
    						);
    
    						shrink(i);
                        }
					}
					
					for (var x=0;x<=i;x++) {
						$('#filmID' + x).height(maxHeight);
					}
				}); 
			};

			function add(value) {
				$.getJSON(apiUrl + "movie.add?identifier=" + value)
				alert("Movie added successfully.");
			};

			function shrink(i) {
				var height        = 500;
				var minWidth      = 230;
				var currentWidth  = $('#filmID' + i).width();
				var currentHeight = $('#filmID' + i).height();

				while (currentWidth > minWidth) {
					$('#filmID' + i).width(currentWidth - 1);
					currentHeight = $('#filmID' + i).height();
					currentWidth  = $('#filmID' + i).width();
					if (currentHeight >= height) {break;}
				}
				if (maxHeight < currentHeight) {maxHeight = currentHeight;}
			};
		</script>
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
