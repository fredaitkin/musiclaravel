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

  $("span[name='playlist']").click(function() {

    let song_id = $(this).attr('id');
    song_id = song_id.replace("playlist-", "");
    let song_title = $(this).attr('data-title');

    var url = APP_URL + '/playlists?all=true&notIn=' + song_id;

    fetch(url)
      .then(
        function(response) {
          if (response.status !== 200) {
            console.log('Looks like there was a problem. Status Code: ' + response.status);
            return;
          }
          response.json().then(function(data) {
            display_playlist_form(song_id, song_title, data);
          });
        }
      )
      .catch(function(err) {
          console.log('Fetch Error: ', err);
    });

  });

});

function display_playlist_form(song_id, song_title, playlists) {
  let playlist_form = '<div>';

  playlist_form += '<div id="error_message" class="d-none alert alert-danger alert-dismissible fade show">';
  playlist_form += '</div>';
  playlist_form += '<div id="success_message" class="d-none alert alert-success alert-dismissible fade show">';
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
    width: '85%',
    open : function() {
      $('div.ui-dialog').addClass('ui-dialog-jukebox');
      $('div.ui-dialog-buttonpane').addClass('ui-dialog-jukebox');
    },
    buttons: {
      "Add": function() {
          $('#error_message').addClass('d-none');
          $('#success_message').addClass('d-none')
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
                if (data.status_code == 200) {
                    $('#success_message').removeClass('d-none').text("Song has been added.");
                } else {
                    $('#error_message').removeClass('d-none').html(data.errors.join('<br>'));
                }
              })
              .catch((error) => {
                $('#error_message').removeClass('d-none').html("An error occurring adding the song");
                console.error('Error:', error);
            });
          }
        },
        Close: function() {
            $( this ).dialog( "close" );
        }
      }
  })
}
