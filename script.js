var maxHeight = 0;

$(document).ready(function() {

    // Get query string values and store in 'parm' object
    const parm = new Proxy(new URLSearchParams(window.location.search), {get: (searchParams, prop) => searchParams.get(prop),});

    // If 'search' query string parameter exists...
    if (parm.search) {

        // Set searchbox text equal to query string value
        $('#titleInput').val(parm.search);

        // Run searchNewMovies() function passing query string value as search term
        searchNewMovies(parm.search);
    }

	// Increase size of search box on smaller screens
    if ($(window).width() < 1000) $('#titleInput').width('75%');

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

function searchNewMovies(search = null) {

	// Show loading spinner on screen while search completes
	// Pure CSS loading spinner from loading.io
	$("#movieDetails").html("<div class='container'><div class='lds-spinner center'><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>");

	// Declare/set variables
	// 'searchTerm' equals 'search' value if not null, else get textbox value
	// Build API URL call using 'apiUrl' and 'searchTerm', store in 'url'
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
		data.sort(function(a,b) {return a.year - b.year}).reverse();

		// Loop through array of results, 
		for (var i in data) {

			// Declare and set variables from results array
			var collection = data[i].collection;
			var file       = data[i].hasFile;
			var image      = data[i].remotePoster;
			var imdb       = data[i].imdbId;
			var plot       = data[i].overview;
			var rating     = data[i].ratings.value;
			var runtime    = data[i].runtime;
			var title      = data[i].title;
			var tmdb       = data[i].tmdbId;
			var year       = data[i].year;
			var youtube    = data[i].youTubeTrailerId;

			// Only create movie container if the IMDb AND TMDB IDs exist
			if (imdb && tmdb) {

				// DEBUGGING: If the 'debug' variable is TRUE, 
				if (debug) {
					console.log('Array Item ' + i + ': ');
					console.log({
					    collection : collection,
					    file       : file,
					    image      : image,
					    imdb       : imdb,
					    plot       : plot,
					    rating     : rating,
					    runtime    : runtime,
					    title      : title,
					    tmdb       : tmdb,
					    year       : year,
					    youtube    : youtube
					});
				}

				// Calculate and set hours and minutes from runtime
				var minutes = runtime % 60;
				var hours   = (runtime - minutes) / 60;

				// Build HTML container with movie details and append container to screen
				$('#movieDetails').append(

					// Create container for movie details
					"<div class='film' id='filmID" + i + "'>" +

						// If file exists in library, show green checkmark icon
						(file ? "<img src='img/dwcheckyes.png' title='Already in Library' class='checkmark'>" : '') +

						// Build movie title string
						"<p class='title'>" + (title.substr(0, title.indexOf(':')).length > 8 ? title.replace(":", ": <br>") : title) + (year ? ' (' + year + ')' : '') + '</p>' +

						// Build movie poster image string if exists, else show 'undefined' image that contains 'No Image' text
						"<img src='" + image + "' class='poster' alt='Movie poster' title='Movie Poster' onerror=\"this.onerror=null;this.src='img/undefined.png';\">" +

						// Build runtime string
						"<p class='runtime'>Runtime: " + (runtime ? (hours ? hours + 'h ' : '') + minutes + 'm' : 'Unknown') + '</p>' +

						// Build rating stars string
						(rating ? "<span class='stars' title='Rated: " + +(rating / 2).toFixed(1) + "/5 stars'><span style='width:" + ((rating / 2) * 16) + "px;'></span></span><br>" : '') +

						// Build Plex image link string
						(file ? "<a href='https://app.plex.tv/desktop/#!/search?query=" + searchTerm + "' target='_blank' title='Find Movie on Plex'><img src='img/plex_small.png' alt='Plex logo' class='site'></a>" : '') +

						// Build IMDb image link string
						"<a href='https://www.imdb.com/title/" + imdb + "' target='_blank' title='Internet Movie Database'><img src='img/imdb_small.png' alt='IMDb logo' class='site'></a>" +

						// Build TMDB image link string
						"<a href='https://www.themoviedb.org/movie/" + tmdb + "' target='_blank' title='The Movie Database'><img src='img/tmdb_small.png' alt='TMDB logo' class='site'></a>" +

						// Build YouTube trailer link string
						(youtube ? "<a href='https://youtu.be/" + youtube + "' target='_blank' title='Movie Trailer'><img src='img/youtube_small.png' alt='YouTube logo' class='site'></a>" : '') +

//						"<input type='button' value='" + exists + " Movie' id='" + imdb + "' onClick='add(this.id)' />" +
						"<p class='plot'>" + plot + '</p>' +
					'</div>'
				);
			}
		}
	}); 
}

}
