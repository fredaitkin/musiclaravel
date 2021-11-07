$(document).ready(function() {

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $( "#variant" ).autocomplete({
        source: function( request, response ) {
          // Fetch data
          $.ajax({
            url:"/word-cloud-autocomplete",
            type: 'post',
            dataType: "json",
            data: {
               _token: CSRF_TOKEN,
               search: request.term
            },
            success: function( data ) {
               response( data );
            }
          });
        },
        select: function (event, ui) {
           // Set selection
           $('#variant').val(ui.item.label);
           $('#variant_of').val(ui.item.value);
           return false;
        }
    });

});