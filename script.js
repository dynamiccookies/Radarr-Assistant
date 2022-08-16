var maxHeight = 0;
$(document).ready(function() {

	// When search button is clicked, run the searchNewMovies function
	$('#search').click(searchNewMovies);

	// When textbox is active and any key is pressed...
	$('#titleInput').keypress(function(e){

		// Check if key pressed is Enter key
		if (e.keyCode == 13){

			// Stop page from reloading because Enter key was pressed
			e.preventDefault();

			// Run the searchNewMovies function
			searchNewMovies();
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

	// DEBUGGING: If the 'debug' variable is TRUE, write the 'searchTerm' variable to console
	if (debug) console.log('Search Term: ' + searchTerm);

	// Call API to retreive data
	$.getJSON(url, function(data) {

		// DEBUGGING: If the 'debug' variable is TRUE, write the entire results (data) array to console
		if (debug) {
			console.log('Data Array:');
			console.log(data);
		}

		// Clear loading spinner from screen
		$("#movieDetails").empty();

		// Check for results from API, return error if none
		if (!data.length) {
			$('#movieDetails').html("<h2 class='noresults'>No Results Found - Please try changing your search criteria</h2>");
			return;
		}

		// Reverse sort results by year - newest first
		data.sort(function(a,b) {return a.year-b.year}).reverse();

		// Loop through array of results, 
		for (var i in data) {

			// Declare and set variables from results array
			var file    = data[i].hasFile;
			var image   = data[i].remotePoster;
			var imdb    = data[i].imdbId;
			var plot    = data[i].overview;
			var runtime = data[i].runtime;
			var title   = data[i].title;
			var tmdb    = data[i].tmdbId;
			var year    = data[i].year;
			var youtube = data[i].youTubeTrailerId;

			// Only create movie container if the IMDb AND TMDB IDs exist
			if (imdb && tmdb) {

				// DEBUGGING: If the 'debug' variable is TRUE, 
				if (debug) {
					console.log('Array Item ' + i + ': ');
					console.log({file:file, image:image, imdb:imdb, plot:plot, runtime:runtime, title:title, tmdb:tmdb, year:year, youtube:youtube});
				}

				// Calculate and set hours and minutes from runtime
				var minutes = runtime % 60;
				var hours   = (runtime - minutes) / 60;

				// Build HTML container with movie details and append container to screen
				$('#movieDetails').append(

					// Create container for movie details
					"<div class='film center' id='filmID" + i + "'>" +

						// If file exists in library, show green checkmark icon
						(file ? "<img src='img/dwcheckyes.png' title='Already in Library' class='checkmark'>" : '') +

						// Build movie title string
						'<strong>' + title + (year ? ' (' + year + ')' : '') + '</strong>' +

						// Build movie poster image string if exists, else show 'undefined' image that contains 'No Image' text
						"<br><img src='" + image + "' class='poster' alt='Movie poster' title='Movie Poster' onerror=\"this.onerror=null;this.src='img/undefined.png';\"><br>" +

						// Build runtime string
						'<em>Runtime: ' + (runtime ? (hours ? hours + 'h ' : '') + minutes + 'm' : 'Unknown') + '</em><br>' +

						// Build Plex image link string
						(file ? "<a href='https://app.plex.tv/desktop/#!/search?query=" + searchTerm + "' target='_blank' title='Find Movie on Plex'><img src='img/plex_small.png' alt='Plex logo' class='site'></a>" : '') +

						// Build IMDb image link string
						"<a href='https://www.imdb.com/title/" + imdb + "' target='_blank' title='Internet Movie Database'><img src='img/imdb_small.png' alt='IMDb logo' class='site'></a>" +

						// Build TMDB image link string
						"<a href='https://www.themoviedb.org/movie/" + tmdb + "' target='_blank' title='The Movie Database'><img src='img/tmdb_small.png' alt='TMDB logo' class='site'></a>" +

						// Build YouTube trailer link string
						(youtube ? "<a href='https://youtu.be/" + youtube + "' target='_blank' title='Movie Trailer'><img src='img/youtube_small.png' alt='YouTube logo' class='site'></a>" : '') +

//						"<input type='button' value='" + exists + " Movie' id='" + imdb + "' onClick='add(this.id)' />" +
						"<p style='text-align:left;'>" + plot + '</p>' +
					'</div>'
				);

				// Shrink movie container
				shrink(i);
			}
		}
		
		for (var x = 0; x <= i; x++) {
			$('#filmID' + x).height(maxHeight);
		}
	}); 
}

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
}
