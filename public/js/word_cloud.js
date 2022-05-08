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

    $('.categories').select2({
        placeholder: 'Please Select',
        ajax: {
          url: '/categories-autocomplete',
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

    if ($("#categories_json").val() != undefined) {
        var categories = JSON.parse($("#categories_json").val());
        $.each(categories, function(i, category) {
            let set_category = $("<option selected='selected'></option>").val(category.id).text(category.category);
            $(".categories").append(set_category).trigger('change');
        });
    }

});