$(function() {
  //------------------------------------------------
  // Pagination tout au long de la page
  //------------------------------------------------

  const $list = $("#posts-list-ajax");
  const $loader = $(`
        <div class="card loader">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    `);

  let nextPage = 2;
  let isLoading = false;
  let isDone = false;

  function loadPosts() {
    if (isLoading || isDone) return;
    isLoading = true;
    $list.append($loader);

    fetch(`?p=${nextPage}`, {
      headers: { "X-Requested-With": "XMLHttpRequest" }
    })
      .then(response => {
        if (parseInt(response.headers.get("X-PixelMarket-Is-Last-Page"))) {
          isDone = true;
        }
        return response.text();
      })
      .then(bodyText => {
        $list
          .children()
          .last()
          .remove();

        $list.append(bodyText);
        nextPage++;
      })
      .catch(console.log("catch"))
      .finally(() => {
        isLoading = false;
      });
  }

  $("#pagination").on("click", e => {
    e.preventDefault();
    loadPosts();
  });

  //------------------------------------------------
  // MON CODE
  //------------------------------------------------
  //
  // $(function () {
  //
  //     const list = $('#posts-list-ajax');
  //     const $loader = $(`
  //         <div class="card loader">
  //             <div class="spinner-border text-primary" role="status">
  //                 <span class="sr-only">Loading...</span>
  //             </div>
  //         </div>
  //     `);
  //
  //     let nextPage = 2;
  //     let isLoading = false;
  //     let isDone = false;
  //
  //     $('#pagination').on('click', (evt) => {
  //
  //         var next = $(evt.target).closest('#next');
  //         var list = $('#posts-list-ajax');
  //         // donc on sort du gestionnaire de clic
  //         evt.preventDefault();
  //         // Si le lien est undefined, c'est qu'on a cliqué ailleurs,
  //         // on recoit les bons url selon le click l'autre est undefined
  //
  //         fetch(`/posts?p=${nextPage}`, {headers: {'X-Requested-With': 'XMLHttpRequest'}})
  //             .then(response => response.text())
  //             .then((text) => {
  //                 list.append(text);
  //                 nextPage++;
  //             })
  //
  //             .catch((error) => {
  //                 //...
  //                 console.log(error);
  //                 Toast
  //                     .setMessage(error.message)
  //                     .error();
  //             })
  //             .finally(() => {
  //                 isLoading = false;
  //             });
  //     });
  // });

  //------------------------------------------------
  // SCROLL EN BAS
  //------------------------------------------------

  const $window = $(window);
  $window.on("scroll", function(e) {
    const currentScroll =
      $("body").scrollTop() || $("html").scrollTop() || $window.scrollTop();
    const endOfPage = currentScroll + $window.height();
    const heightDoc = $(document).height();

    if (endOfPage == heightDoc) {
      loadPosts();
    }
  });
});
