$(document).ready(function() {

    $("#capitalize").click(function() {
        $("#word").val($("#word").val()[0].toUpperCase() + $("#word").val().slice(1));
    });

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

    $(function() {
        var items;
        fetch('/categories/ajax')
            .then(
                function(response) {
                    if (response.status !== 200) {
                        console.log('Looks like there was a problem. Status Code: ' + response.status);
                        return;
                    }
                    response.json().then(function(data) {
                        var categories = $("#category_ids").val().split(',');
                        $.each( categories, function( index, value ) {
                          $.each( data, function( key, category ) {
                            if (category != undefined && category.id == value) {
                              data.splice(key, 1);
                            }
                          });
                        });
                        items = data;
                    });
                }
            )
            .catch(function(err) {
                console.log('Fetch Error: ', err);
        });

        function split( val ) {
          return val.split( /,\s*/ );
        }
        function extractLast( term ) {
          return split( term ).pop();
        }
        function removeFromAvailableCategories(id) {
           $.each( items, function( key, category ) {
              if (category.id == id) {
                items.splice(key, 1);
                return false;
              }
          });
        }
        $( "#category_display" )
          .autocomplete({
            minLength: 0,
            source: function( request, response ) {
              response( $.ui.autocomplete.filter(
                items, extractLast( request.term ) ) );
            },
            focus: function() {
              return false;
            },
            select: function( event, ui ) {

              var terms = split( this.value );
              // remove the current input
              terms.pop();
              // add the selected item
              terms.push( ui.item.value );
              this.value = terms.join( ", " );
              // Remove from future search
              removeFromAvailableCategories(ui.item.id);

              $("#category_ids").val(function() {
                  if (this.value.length == 0) {
                      return ui.item.id;
                  } else {
                  return this.value + ',' + ui.item.id;
                  }

              });

              return false;
            }
        });
    });

});