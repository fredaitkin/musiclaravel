$(document).ready(function() {

	$("button[name='lyric_retrieve']").click(function() {

        let url = APP_URL + '/songs/lyrics?artist=' + $('#artist').val() + '&song=' + $('#song').val();

        fetch(url)
          .then(
            function(response) {
              if (response.status !== 200) {
                console.log('Looks like there was a problem. Status Code: ' + response.status);
                return;
              }
              response.json().then(function(data) {
                $('#lyrics').val(htmlDecode(data));
              });
            }
          )
          .catch(function(err) {
            console.log('Fetch Error: ', err);
        });

        function htmlDecode(input) {
            input = input.replaceAll("<br/>", "\n");
            var doc = new DOMParser().parseFromString(input, "text/html");
            return doc.documentElement.textContent;
        }

	});

});