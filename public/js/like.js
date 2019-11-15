$(function() {
  // flag
  let isPending = false;

  // On écoute le clic sur la liste entière, plutôt que sur chacun des liens :
  // 1. c'est plus performant, car il n'y a qu'un seul écouteur d'évenement
  // 2. lorsque des posts seront ajoutés dynamiquement dans la liste, les clics seront automatiquement
  //    pris en compte, sans ajouter de nouvel écouteur.
  $(".heart-like").on("click", e => {
    // On veut sélectionner le lien <a> ayant la class "like".
    $link = $(e.target).closest(".like");

    // Si le lien est undefined, c'est qu'on a cliqué ailleurs,
    // donc on sort du gestionnaire de clic
    if (!$link.length) return;

    e.preventDefault();

    // Si une requete AJAX est déjà en cours, on ignore le clic
    if (isPending) return false;
    isPending = true;

    const url = $link.attr("href");

    fetch(url, { headers: { "X-Requested-With": "XMLHttpRequest" } })
      .then(response => {
        if (response.status === 201) {
          // si un like a été créé

          $link
            .attr("href", url.replace("like", "unlike"))
            .find("i")
            .removeClass("far")
            .addClass("fas")
            .addClass("liked");
        } else if (response.status === 204) {
          // si un like a été supprimé
          $link
            .attr("href", url.replace("unlike", "like"))
            .find("i")
            .addClass("far")
            .removeClass("fas")
            .removeClass("liked");
        } else {
          return response.text().then(message => {
            throw new Error(
              message ||
                response.statusText ||
                "Invalid response code : " + response.status
            );
          });
        }

        return response.text();
      })
      .finally(() => {
        isPending = false;
      });
  });
});
