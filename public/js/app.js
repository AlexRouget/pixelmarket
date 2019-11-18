console.log("hello app.js");

//FLASH
$("document").ready(function() {
  setTimeout(function() {
    $("div.alert").remove();
  }, 3000); // 3 secs
});

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

// CHANGE BUTTON

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

  //AVATARS
  var $avatarInput = $("#user_avatar");
  var $avatarInput = $("#post_attachment");
  var $avatarRegisterInput = $("#registration_form_avatar");

  var $avatar1 = $("#avatar_1");
  var $avatar2 = $("#avatar_2");
  var $avatar3 = $("#avatar_3");

  $avatar1.click(function() {
    // var img = 'avatar-red'  => TODO
    selectExistingAvatar($avatar1, img);
  });
  $avatar2.click(function() {
    // var img = 'avatar-green'  => TODO
    selectExistingAvatar($avatar2, img);
  });
  $avatar3.click(function() {
    // var img = 'avatar-blue'  => TODO
    selectExistingAvatar($avatar3, img);
  });

  function selectExistingAvatar(input, img) {
    input.css({
      border: "2px solid green"
    });
    //add img "avatar-red" in input['avatar']
  }

  //avatar IMAGE
  var $avatarLabel = $("#avatar_4");
  var file = $avatarInput.val();
  var reader = new FileReader();
  var image = document.createElement("img");

  $avatarLabel.click(function() {
    $avatarInput.click();
  });

  $avatarLabel.click(function() {
    $avatarRegisterInput.click();
  });

  $avatarInput.change(() => {
    if (file) {
      reader.readAsDataURL(file);
    }

    image.src = reader.result;
    $("#avatars")[0].appendChild(image);
    console.log("File upload");
  });

  // after function because FileReader.readAsDataURL must be an instance of Blob.
  if (file) {
    reader.readAsDataURL(file);
  }
});
