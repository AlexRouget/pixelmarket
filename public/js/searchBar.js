$(function() {
  load_data();
  function load_data(query) {
    $.ajax({
      url: "?search[search]",
      method: "POST",
      data: { query: query },
      success: function(data) {
        $("#results_search").html(data);
      }
    });
  }
  $("#search_search").keyup(function() {
    var search = $(this).val();
    if (search != "") {
      load_data(search);
    } else {
      load_data();
    }
  });
});
