var maxHeight = 0;

$(document).ready(function() {

	// Get query string values and store in 'parm' object
	const parm = new Proxy(new URLSearchParams(window.location.search), {get: (searchParams, prop) => searchParams.get(prop),});

	// If 'search' query string parameter exists OR 'searchTerm' exists...
	if (parm.search || searchTerm) {

		// Set searchbox text equal to 'searchTerm' if it exists, else use query string value
		$('#titleInput').val((searchTerm ? searchTerm : parm.search));

		// Run searchNewMovies() function passing 'searchTerm' if it exists, else query string value as search term
		searchNewMovies((searchTerm ? searchTerm : parm.search));
	}

	if ($(window).width() < 1000) $('#titleInput').width('65%');

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

	// Run the share() function when the share icon is clicked
	$('#share').click(function(){

		// Declare/set variables from address bar and build share URL with search box text
		var host       = window.location.host;
		var port       = window.location.port;
		var protocol   = window.location.protocol;
		var path       = window.location.pathname;
		var searchTerm = document.getElementById('titleInput').value;
		var shareUrl   = protocol + '//' + host + (port ? ':' + port : '') + path + '?search=' + encodeURIComponent(searchTerm);

		// Prompt share URL to screen for user
		prompt('Copy this URL to share:', shareUrl);
	});

	$('#movieDetails').on('click', '.plot', function(){alert($(this).text());});

	if (movieAdded !== '') {
		if (movieAdded) {alert('Movie added successfully!');}
		else {alert('ERROR: Please contact the administrator.');}
	}
});

function searchNewMovies(search = null) {

	// Declare/set variables
	// 'searchTerm' equals 'search' value if not null, else get textbox value
	// Build API URL call using 'radarrApiUrl' and 'searchTerm', store in 'url'
	var searchTerm = document.getElementById('titleInput').value;
	var url        = atob(radarrApiUrl) + '&term=' + searchTerm;

	// DEBUGGING: If the 'debug' variable is TRUE, write the 'searchTerm' variable to console
	if (debug) console.log('Search Term: ' + searchTerm);

    // Set hidden input value to 'searchTerm'
    $('#form_search_term').val(searchTerm);

	// Show loading spinner on screen while search completes
	// Pure CSS loading spinner from loading.io
	$('#movieDetails').html("<div class='container'><div class='lds-spinner center'><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>");

	// Show share button icon when results are found
	$('#share').css('display','none');

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
			var added      = data[i].added;
			var collection = data[i].collection;
			var file       = data[i].hasFile;
			var image      = data[i].remotePoster;
			var imdb       = data[i].imdbId;
			var movieId    = (data[i].id !== undefined ? data[i].id : '');
			var plot       = data[i].overview;
			var rating     = data[i].ratings.value;
			var runtime    = data[i].runtime;
			var title      = data[i].title;
			var tmdb       = data[i].tmdbId;
			var votes      = data[i].ratings.votes;
			var year       = data[i].year;
			var youtube    = data[i].youTubeTrailerId;

			// Only create movie container if the IMDb AND TMDB IDs exist
			if (imdb && tmdb) {

				// DEBUGGING: If the 'debug' variable is TRUE, 
				if (debug) {
					console.log('Array Item ' + i + ': ');
					console.log({
						added      :added,
						collection :collection,
						file       :file,
						image      :image,
						imdb       :imdb,
						movieId    :movieId,
						plot       :plot,
						rating     :rating,
						runtime    :runtime,
						title      :title,
						tmdb       :tmdb,
						votes      :votes,
						year       :year,
						youtube    :youtube
					});
				}

				// Build HTML container with movie details and append container to screen
				$('#movieDetails').append(

					// Create container for movie details
					"<div class='film' id='filmID" + i + "'>" +

						// If file exists in library, show green checkmark icon
						(file ? "<img src='img/dwcheckyes.png' title='Already in Library' class='checkmark'>" : '') +

						// Build movie title string
						"<p class='title'>" + (title.substr(0, title.indexOf(':')).length > 8 ? title.replace(":", ": <br>") : title) + (year ? ' (' + year + ')' : '') + '</p>' +

						// Build movie poster image string if exists, else show 'undefined' image that contains 'No Image' text
						"<img src='" + image + "' class='poster' alt='Movie Poster' title='Movie Poster' onerror=\"this.onerror=null;this.src='img/undefined.png';\">" +

						// Build runtime string
						"<p class='runtime'>Runtime: " + calcRuntime(runtime) + '</p>' +

						// Build rating stars string
						(rating ? "<span class='stars' title='Rated: " + +(rating / 2).toFixed(1) + '/5 stars - ' + votes.toLocaleString('en-US') + " votes'><span style='width:" + ((rating / 2) * 16) + "px;'></span></span><br>" : '') +

						// Build Plex image link string
						(file ? "<a href='https://app.plex.tv/desktop/#!/search?query=" + searchTerm + "' target='_blank' title='Find Movie on Plex'><img src='img/plex_small.png' alt='Plex logo' class='site'></a>" : '') +

						// Build IMDb image link string
						"<a href='https://www.imdb.com/title/" + imdb + "' target='_blank' title='Internet Movie Database'><img src='img/imdb_small.png' alt='IMDb logo' class='site'></a>" +

						// Build TMDB image link string
						"<a href='https://www.themoviedb.org/movie/" + tmdb + "' target='_blank' title='The Movie Database'><img src='img/tmdb_small.png' alt='TMDB logo' class='site'></a>" +

						// Build YouTube trailer link string
						(youtube ? "<a href='https://youtu.be/" + youtube + "' target='_blank' title='Movie Trailer'><img src='img/youtube_small.png' alt='YouTube logo' class='site'></a>" : '') +

						// Build movie button and hidden input values
						"<form method='post'>" + movieButton(added, file, tmdb) + 
						"<input type='hidden' id='movieId'    name='movieId'    value='" + movieId + "'>" +
						"<input type='hidden' id='searchTerm' name='searchTerm' value='" + searchTerm + "'>" +
						"<input type='hidden' id='title'      name='title'      value='" + title + "'>" +
						"<input type='hidden' id='tmdbId'     name='tmdbId'     value='" + tmdb + "'></form>" +

						// Build plot text
						"<p class='plot' title='Click to view full plot'>" + plot + '</p>' +
					'</div>'
				);

				// Declare variables to get movie title line height for determining number of lines for plot
				// 'lines' holds the -webkit-line-clamp value set in the switch
				// 'clientHeight' holds the title element's height value
				var lines;
				var clientHeight = $('#filmID' + i + ' .title')[0].clientHeight;

				// Set 'lines' variable based on line height - Each line in title is ~20
				if      (clientHeight < 30) {lines = "10";}
				else if (clientHeight < 50) {lines = "9";}
				else if (clientHeight < 70) {lines = "8";}
				else if (clientHeight < 90) {lines = "7";}
				else                        {lines = "6";}

				// Set number of lines for plot            
				$('#filmID' + i + ' .plot').css('-webkit-line-clamp', lines);
			}
		}

		// Show share button icon when results are found
		$('#share').css('display','inline');
	});
}

function calcRuntime(runtime) {

	// If runtime exists...
	if (runtime) {

		// Calculate and set hours and minutes from runtime
		var minutes = runtime % 60;
		var hours   = (runtime - minutes) / 60;

		// If hours is greater than 0, build hours in '1h ' format
		if (hours) {hours = hours + 'h ';}
		else {hours = '';}

		// Return runtime string in '1h 23m' format or '45m' if less than one hour
		return hours + minutes + 'm';

	// Else return 'Unknown'
	} else {return 'Unknown';}
}
function movieButton(added, file, tmdb) {

	// Declare and set blank the 'buttonHTML' variable to hold the <input> element
	var buttonHTML = '';

	// If 'added' date is greater than 0001-01-01, movie exists in queue or library
	added  = (added > '1/1/0001' ? true : false);

	// If current movie object equals tmdbId global variable OR movie has been added to the queue AND NOT downloaded to the library...
	if ((tmdb == tmdbId) || (added && !file)) {
		buttonHTML = "<input type='submit' class='movie-queue movie-button' name='queue_movie' value='Searching for Movie' disabled/>";

	// If movie has been added to the queue AND downloaded to the library...
	} else if (added && file) {
		buttonHTML = "<input type='submit' class='movie-readd movie-button' name='readd_movie' value='Re-Add Movie' />";

	// Else if movie has NOT been added to the queue AND NOT downloaded to the library...
	} else if (!added && !file) {
		buttonHTML = "<input type='submit' class='movie-add movie-button' name='add_movie' value='Add Movie' />";

	// DEBUGGING: Else, if the 'debug' variable is TRUE, write error to console
	} else {
		if (debug) console.log('Movie Button Error: added=' + added + ' file=' + file);
	}

	// Return the created <input> element from the function
	return buttonHTML;
}
