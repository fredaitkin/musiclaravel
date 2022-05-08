$(document).ready(function() {

    $("a[name='play']").click(function() {

        let playlist = $(this).parent().prev('td').find('div').text();

        let url = APP_URL + '/playlists?playlist=' + encodeURIComponent(playlist);

        fetch(url)
            .then(
                function(response) {
                    if (response.status !== 200) {
                        console.log('Looks like there was a problem. Status Code: ' + response.status);
                        return;
                    }
                    response.json().then(function(data) {
                        display_jukebox(playlist, JSON.parse(data[0].playlist));
                    });
                }
            )
            .catch(function(err) {
                console.log('Fetch Error: ', err);
        });

    });

});
