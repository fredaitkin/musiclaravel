$(document).ready(function() {

    $("#lyrics").click(function() {

        let url = '/internalapi/lyrics/artist?artist=' + $("#artist").val() + '&empty=true';

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
                                window.open("/lyrics/" + songs[i].id);
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

    });

});