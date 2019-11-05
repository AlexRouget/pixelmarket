console.log("hello app.js");

// BURGER
var content = document.querySelector("#hamburger-content");
var sidebarBody = document.querySelector("#hamburger-sidebar-body");
var buttonBurger = document.querySelector("#hamburger-button");
var overlay = document.querySelector("#hamburger-overlay");
var activatedClass = "hamburger-activated";

sidebarBody.innerHTML = content.innerHTML;

buttonBurger.addEventListener("click", function(e) {
  e.preventDefault();

  this.parentNode.classList.add(activatedClass);
});

buttonBurger.addEventListener("keydown", function(e) {
  if (this.parentNode.classList.contains(activatedClass)) {
    if (e.repeat === false && e.which === 27)
      this.parentNode.classList.remove(activatedClass);
  }
});

overlay.addEventListener("click", function(e) {
  e.preventDefault();

  this.parentNode.classList.remove(activatedClass);
});

// Change button

var btnContact = document.getElementsByClassName("btn-contact");

var btn_1 = btnContact[0];
var btn_2 = btnContact[1];

function change_2(contact) {
  btn_1.innerText = contact;
  btn_1.classList.remove("btn-green");
}
function change_1(contact) {
  btn_2.innerText = contact;
  btn_2.classList.remove("btn-green");
}

$(function() {
  // console.log("jquery");

  // $("#profil-nav").tabify({
  //   container: ".profile-container",
  //   data: "profil"
  // });

  //NAVBAR
  const $window = $(window);
  const $navbar = $("#navbar");
  $window.on("scroll", function(e) {
    if ($(this).scrollTop() > window.innerHeight / 3) {
      $navbar.removeClass("top");
    } else {
      $navbar.addClass("top");
    }
  });

  //CONFIRM connection
  var $btnCreatePost = $(".btn-create-post");

  $btnCreatePost.click(function() {
    console.log("BAHHHHHHH");
    return confirm("Tu dois te connecter pour créer une annonce");
  });

  //AVATAR
  var $avatarInput = $("#user_avatar");

  var $avatar1 = $("#avatar_1");
  var $avatar2 = $("#avatar_2");
  var $avatar3 = $("#avatar_3");

  $avatar1.click(function() {});

  var $avatarLabel = $("#avatar_4");

  $avatarLabel.click(function() {
    $avatarInput.click();
    uploadAvatar();
  });

  function uploadAvatar(afterSelection) {
    var reader = new FileReader();
    var file = $avatarInput.val();

    console.log("le fichier : " + file);

    $avatarInput.change(() => {
      if (file) {
        reader.readAsDataURL(file);
      }
      console.log("le reader.result : " + reader.result);

      $avatarLabel.css({
        border: "2px solid green",
        background: "url(" + reader.result + ") center"
      });
      console.log("Done");
    });
  }

  function selectValid(input) {
    input.css({
      border: "2px solid green"
    });
    $avatarInput.val() = "../img/icons_avatars/avatar-red.png"

    // EN GROS !!!!!!
  }

  // $("#user_avatar").val();
});
