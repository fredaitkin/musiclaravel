$(document).ready(function() {

    $('.artists').select2({
        placeholder: 'Please Select',
        ajax: {
          url: '/artist-select-ajax',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
    });

    if ($("#artist_json").val() != undefined) {
        var artists = JSON.parse($("#artist_json").val());
        $.each(artists, function(i, artist) {
            let set_artist = $("<option selected='selected'></option>").val(artist.id).text(artist.artist);
            $(".artists").append(set_artist).trigger('change');
        });
    }

    var song_url = APP_URL + '/song/play/';

    $("input[name='playlist']").click(function() {

        let song_id = $(this).attr('id');
        song_id = song_id.replace("playlist-", "");
        let song_title = $(this).attr('data-title');

        var url = APP_URL + '/playlists?all=true&notIn=' + song_id;
console.log(url);
        fetch(url)
            .then(
                function(response) {
                    if (response.status !== 200) {
                        console.log('Looks like there was a problem. Status Code: ' + response.status);
                        return;
                    }
                    response.json().then(function(data) {
                        console.log(data);
                        display_playlist_form(song_id, song_title, data);
                    });
                }
            )
            .catch(function(err) {
                console.log('Fetch Error: ', err);
        });

    });

    function display_playlist_form(song_id, song_title, playlists) {
        let playlist_form = '<div>';

        playlist_form += '<div id="error_message" class="d-none alert alert-danger alert-dismissible fade show">';
        playlist_form += 'Please select a playlist.';
        playlist_form += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
        playlist_form += '</div>';

        playlist_form += '<div>Add to Existing Playlist</div>';
        playlist_form += '<select id="existing_playlist">';
        playlist_form += '<option value="">Please Select</option>';
        $.each(playlists, function(index, playlist) {
            playlist_form += '<option value="' + playlist.name + '">' + playlist.name + '</option>';
        });
        playlist_form += '</select>';
        playlist_form += '<div>Add to New Playlist</div>';
        playlist_form += '<input id="new_playlist"/>';

        playlist_form += '<input type="hidden" id="song_id" value="' + song_id + '"/>';
        playlist_form += '<input type="hidden" id="song_title" value="' + song_title + '"/>';
        playlist_form += '</div>';

        var url = APP_URL + '/playlists';

        $(playlist_form).dialog({
            title: 'Playlists',
            modal: false,
            width: 500,
            open : function() {
                $('div.ui-dialog').addClass('ui-dialog-jukebox');
                $('div.ui-dialog-buttonpane').addClass('ui-dialog-jukebox');
            },
            buttons: {
                "Add": function() {
                    let playlist = $("#existing_playlist option:selected").val();
                    if (playlist == '') {
                        playlist = $('#new_playlist').val();
                    }
                    if (playlist == '') {
                        $('#error_message').removeClass('d-none');
                    } else {
                        const data = {playlist: playlist, id: $('#song_id').val(), title: $('#song_title').val()};
                        fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                            },
                                body: JSON.stringify(data),
                            })
                            .then(response => response.json())
                            .then(data => {
                                alert('Song has been added');
                                $(this).dialog("close");
                            })
                            .catch((error) => {
                                $('#error_message').removeClass('d-none').text("An error occurring adding the song");
                                console.error('Error:', error);
                            });
                    }
                },
                Cancel: function() {
                    $( this ).dialog( "close" );
                }
            }
        })
    }

    $("input[name='play_album']").click(function() {
        let song_id = $(this).attr('id');
        song_id = song_id.replace("play-album-", "");

        var url = APP_URL + '/songs?id=' + song_id + '&album=true';

        fetch(url)
            .then(
                function(response) {
                    if (response.status !== 200) {
                        console.log('Looks like there was a problem. Status Code: ' + response.status);
                        return;
                    }
                    response.json().then(function(data) {
                        console.log(data);
                        display_jukebox(data[0].album, data);
                    });
                }
            )
            .catch(function(err) {
                console.log('Fetch Error: ', err);
        });

    });

    $("input[name='play']").click(function() {
        let song_id = $(this).attr('id');
        song_id = song_id.replace("play-", "");

        var url = APP_URL + '/songs?id=' + song_id;
        fetch(url)
            .then(
                function(response) {
                    if (response.status !== 200) {
                        console.log('Looks like there was a problem. Status Code: ' + response.status);
                        return;
                    }
                    response.json().then(function(data) {
                        display_jukebox(data[0].album, data);
                    });
                }
            )
            .catch(function(err) {
                console.log('Fetch Error: ', err);
        });

    });

});