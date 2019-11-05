$(function() {
  var $divAnnonces = $("#mes-annonces");
  var $divFavories = $("#mes-favories");
  var $divInfos = $("#mes-infos");
  var $aAnnonces = $("nav#profile-nav ul li a")[0];
  var $aFavories = $("nav#profile-nav ul li a")[1];
  var $aInfos = $("nav#profile-nav ul li a")[2];

  $("nav#profile-nav ul li a").click(function(e) {
    e.preventDefault();
    var url = $(this).attr("href"); //get the link you want to load data from

    switch (url) {
      case "#annonces":
        $divAnnonces.show();
        $divFavories.hide();
        $divInfos.hide();
        $aFavories.classList.remove("active");
        $aInfos.classList.remove("active");
        e.target.classList.add("active");
        break;
      case "#favories":
        $divAnnonces.hide();
        $divAnnonces.hide();
        $divFavories.show();
        $aInfos.classList.remove("active");
        $aAnnonces.classList.remove("active");
        e.target.classList.add("active");
        break;
      case "#infos":
        $divFavories.hide();
        $divAnnonces.hide();
        $divInfos.show();
        $aFavories.classList.remove("active");
        $aAnnonces.classList.remove("active");
        e.target.classList.add("active");
        break;

      default:
        break;
    }
  });
});
