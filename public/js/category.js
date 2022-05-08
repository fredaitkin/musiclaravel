$(document).ready(function() {

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