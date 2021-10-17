$(document).ready(function() {

    $("input[name='songs']").click(function() {
        let word_id = $(this).attr('id');
        word_id = word_id.replace("songs-", "");
        var url = '/internalapi/word-cloud?id=' + word_id;

        fetch(url)
            .then(
                function(response) {
                    if (response.status !== 200) {
                        console.log('Looks like there was a problem. Status Code: ' + response.status);
                        return;
                    }
                    response.json().then(function(data) {
                        display_song_form(data);
                    });
                }
            )
            .catch(function(err) {
                console.log('Fetch Error: ', err);
        });

    });

    function display_song_form(data) {
        let form = '<div>';
        $.each(data, function(i, song) {
            form += '<div><div style="width:50%;float:left">' + song.song.substr(0, 50) + '</div><div style="width:50%;float:left">' + song.artist + '</div></div>';
        });

        form += '</div>';

        $(form).dialog({
          title: 'Songs',
          close: function() {
            $(this).remove()
          },
          modal: false,
          width: 700,
          open : function() {
            $('div.ui-dialog').addClass('ui-dialog-jukebox');
          }
      });
    }

});