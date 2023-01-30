$(document).ready(function() {

  var device_type = $("meta[name='device-type']").attr("content");

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

  $("a[name='shuffle_songs']").click(function() {
    var url = APP_URL + '/songs?all';

    fetch(url)
      .then(
        function(response) {
          if (response.status !== 200) {
            console.log('Looks like there was a problem. Status Code: ' + response.status);
            return;
          }
          response.json().then(function(data) {
            var songs = shuffle(data);
            display_jukebox("EVERYBODY SHUFFLIN...", songs, device_type);
          });
        }
      )
      .catch(function(err) {
          console.log('Fetch Error: ', err);
    });

  });

  $("span[name='play_album']").click(function() {
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
              display_jukebox(data[0].album, data, device_type);
            });
          }
      )
      .catch(function(err) {
          console.log('Fetch Error: ', err);
    });

  });

  $("span[name='play']").click(function() {
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
            display_jukebox(data[0].album, data, device_type);
          });
        }
      )
      .catch(function(err) {
        console.log('Fetch Error: ', err);
    });

  });

  $("a[name='play_genre']").click(function() {
    let genre = $(this).parent().prev('td').find('div').text();

    let url = APP_URL + '/songs?genre=' + encodeURIComponent(genre);

    fetch(url)
      .then(
        function(response) {
          if (response.status !== 200) {
            console.log('Looks like there was a problem. Status Code: ' + response.status);
            return;
          }
          response.json().then(function(data) {
            display_jukebox(genre, data, device_type);
          });
        }
      )
      .catch(function(err) {
        console.log('Fetch Error: ', err);
    });

  });

  $("#get_lyrics").off().on("click", function() {
      if ($("#artist").val().length > 0) {
        let url = '/songs?artist=' + $("#artist").val() +
          '&lyrics_empty=true' +
          '&exact_match=' + $('#exact_match').is(':checked') +
          '&exempt=' + $('#exempt').val();

        fetch(url)
          .then(
            function(response) {
              if (response.status !== 200) {
                console.log('Looks like there was a problem. Status Code: ' + response.status);
                return;
              }
              response.json().then(function(songs) {
                if (songs.length == 0) {
                  alert('No missing lyrics');
                } else {
                  for (i = 0; i < songs.length; i++) {
                    var search = $("#artist").val() + ' ' + songs[i].title + ' lyrics';
                    window.open("https://www.google.com/search?q=" + encodeURIComponent(search), '_blank');
                    window.open("songs?lyrics=true & id=" + songs[i].id);
                    if (i == 2) {
                      break;
                    }
                  }
                }
              });
            }
          )
          .catch(function(err) {
            console.log('Fetch Error: ', err);
        });
      }

  });

  $("button[name='reset']").click(function() {
    $(this).parent().parent().find('input').val('');
    $(this).parent().parent().find('input').focus();
  });

  $("#shuffle").click(function() {
    var url = APP_URL + '/songs?all';

    fetch(url)
      .then(
        function(response) {

          if (response.status !== 200) {
            console.log('Looks like there was a problem. Status Code: ' + response.status);
            return;
          }
          response.json().then(function(data) {
            var songs = shuffle(data);
            display_jukebox("EVERYBODY SHUFFLIN...", songs, device_type);
          });
        }
      )
      .catch(function(err) {
          console.log('Fetch Error: ', err);
    });

  });

});

function shuffle(array) {
  var currentIndex = array.length, temporaryValue, randomIndex;

  // While there remain elements to shuffle
  while(0 !== currentIndex) {
    // Pick a remaining element...
    randomIndex = Math.floor(Math.random() * currentIndex);
    currentIndex -= 1;

    // And swap it with the current element
    temporaryValue = array[currentIndex];
    array[currentIndex] = array[randomIndex];
    array[randomIndex] = temporaryValue;
  }

  return array;
}

function get_lyrics(song) {
  lyrics = song.lyrics;
  if (lyrics === undefined || lyrics == 'unavailable') {
    lyrics = 'No lyrics set...';
  }
  return lyrics;
}

function display_jukebox(title, songs, device_type) {
  let song_url = APP_URL + '/song/play/';
  let jukebox_form = '<div class="audio">';
  jukebox_form += '<figure>';
  jukebox_form += '<audio controls src="' + song_url + songs[0].id + '">Your browser does not support the<code>audio</code> element.</audio>';
  jukebox_form += '</figure>';
  jukebox_form += '<button class="next">Next</button>';

  jukebox_form += '<div>';
  for (i = 0; i < songs.length; i++) {
    var artist = '';
      if (songs[i].artists) {
        artist = ' - ' + songs[i].artists[0].artist;
        if (artist == 'Compilations' && songs[i].notes) {
          if (songs[i].notes.indexOf('Artist=') > -1) {
            artist = songs[i].notes;
            artist = artist.replace('Artist=', '');
            idx = artist.indexOf(';');
            if (idx > -1) {
              artist = ' - ' + artist.substring(0,idx);
            }
          }
        }
      }
      jukebox_form += '<span id="song-' + songs[i].id + '">' + songs[i].title + artist + '</span><br>';
  }
  jukebox_form += '</div>';
  jukebox_form += '</div>';

  $(jukebox_form).dialog({
    title: title,
    close: function() {
      $(this).remove()
    },
    modal: false,
    width: device_type == 'desktop' ? 500 : 330,
    open : function() {

      $('div.ui-dialog').addClass('ui-dialog-jukebox');

      // Remove song that is already set
      song = songs.shift();
      // Add css styling
      let previous_id = song.id;
      $("#song-" + previous_id).addClass('font-weight-bold');
      $("span.ui-dialog-title").html(song.title);
      // Play
      let audio = $(this).find('audio').get(0);
      let next = $(this).find('button.next').get(0);

      audio.addEventListener('ended',function() {
        previous_id = next_song(audio, next, previous_id);
      });

      next.addEventListener('click', function() {
        previous_id = next_song(audio, next, previous_id);
      });

      if (songs.length == 0) {
        $('button.next').hide();
      }

      play(audio, next);

      function play(audio, next) {
        var playPromise = audio.play();
        // In browsers that don’t yet support this functionality,
        // playPromise won’t be defined.
        if (playPromise !== undefined) {
          playPromise.then(function() {
            // Automatic playback started!
          }).catch(function(error) {
            // Automatic playback failed, try next song.
            next.click();
          });
        }
      }

      function next_song(audio, next, previous_id) {
        // Get next song
        song = songs.shift();
        if (song !== undefined) {
          audio.src = song_url + song.id;
          $("#current-song").text(song.title);
          $("#song-" + previous_id).removeClass('font-weight-bold');
          previous_id = song.id;
          $("#song-" + previous_id).addClass('font-weight-bold');
          $("span.ui-dialog-title").html(song.title);
          audio.pause();
          audio.load();
          play(audio, next);
          return previous_id;
        } else {
          next.disabled = true;
        }
      }

    }

  });
}

