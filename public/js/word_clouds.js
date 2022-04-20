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

    $("input[name='dictionary']").click(function() {
        let word = $(this).attr('id');
        word = word.replace("dictionary-", "");
        var url = '/internalapi/dictionary?word=' + word;

        fetch(url)
            .then(
                function(response) {
                    if (response.status !== 200) {
                        console.log('Looks like there was a problem. Status Code: ' + response.status);
                        return;
                    }
                    response.json().then(function(data) {
                        display_dictionary_form(word, data);
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
            form += '<div>' +
                '<div style="width:50%;float:left" id="' + song.id + '" class="song"><a class="songs" href="#">' + song.song.substr(0, 50) + '</a></div>' +
                '<div style="width:50%;float:left">' + song.artist + '</div>' +
                '<div id="modal-' + song.id + '" style="display:none;white-space:pre;">' + song.lyrics + '</div>' +
                '</div>';
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

        $('.song').click(function() {
            var modal_div = 'modal-' + $(this).attr('id');
            $('#' + modal_div).dialog({
                modal : true ,
                title: $(this).text(),
                height : 700,
                width : 400,
                open : function() {
                    $("[aria-describedby=" + modal_div + "]").addClass('ui-dialog-lyrics');
                },
            });
        });
    }

    function display_dictionary_form(word, data) {
        let form = '<div>';
        $.each(data, function(i, dictionary) {
            $.each(dictionary.glossary, function(j, glossary) {
                form += '<div style="width:10%;float:left">' + glossary.type + '</div>';
                form += '<div style="width:90%;float:left">' + glossary.definition + '</div>';
            });
        });
        form += '</div>';

        $(form).dialog({
          title: word,
          close: function() {
            $(this).remove()
          },
          modal: false,
          width: "85%",
          open : function() {
            $('div.ui-dialog').addClass('ui-dialog-jukebox');
          }
      });

    }

});