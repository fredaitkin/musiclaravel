$(document).ready(function() {

    $("#album").change(function() {

        var url = APP_URL + '/songs?album=' + encodeURIComponent($('#album').val());

        fetch(url)
            .then(
                function(response) {
                    if (response.status !== 200) {
                        console.log('Looks like there was a problem. Status Code: ' + response.status);
                        return;
                    }
                    response.json().then(function(data) {
                        var ol = '<ol id="songs" style="list-style-type:none">';
                        $.each(data, function(k, song) {
                            ol += '<li><a href="/song/' + song.id + '">' + song.title + '</a></li>';
                        });
                        ol += '</ol>';
                        $("#songs").replaceWith(ol);
                    });
                }
            )
            .catch(function(err) {
                console.log('Fetch Error: ', err);
        });

    });

    $("input[name='play_songs']").click(function() {
        let artist_id = $(this).attr('id');
        let id = artist_id.replace("play-songs-", "");
        let artist = $(this).closest('tr').find('div[name="artist_name"]').text();

        let url = APP_URL + '/artists?id=' + id;

        fetch(url)
            .then(
                function(response) {
                    if (response.status !== 200) {
                        console.log('Looks like there was a problem. Status Code: ' + response.status);
                        return;
                    }
                    response.json().then(function(data) {
                        display_jukebox(artist, data[0].songs);
                    });
                }
            )
            .catch(function(err) {
                console.log('Fetch Error: ', err);
        });

    });

});