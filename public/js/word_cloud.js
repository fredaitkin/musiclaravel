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
                        var info = '';
                        $.each(data, function(index, song) {
                            info += song.song + ' : ' + song.artist + "\r\n";
                        });
                        alert(info);
                    });
                }
            )
            .catch(function(err) {
                console.log('Fetch Error: ', err);
        });

    });

});