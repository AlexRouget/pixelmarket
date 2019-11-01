document.addEventListener("DOMContentLoaded", () => {
  var myInput = document.getElementById("user_password_first");
  var letter = document.getElementById("letter");
  var capital = document.getElementById("capital");
  var number = document.getElementById("number");
  var length = document.getElementById("length");

  // When the user clicks on the password field, show the message box
  myInput.onfocus = function() {
    document.getElementById("message").style.display = "block";
  };

  // When the user clicks outside of the password field, hide the message box
  myInput.onblur = function() {
    document.getElementById("message").style.display = "none";
  };

  // When the user starts to type something inside the password field
  myInput.onkeyup = function() {
    // Validate lowercase letters
    var lowerCaseLetters = /[a-z]/g;
    if (myInput.value.match(lowerCaseLetters)) {
      letter.classList.remove("invalid");
      letter.classList.add("valid");
    } else {
      letter.classList.remove("valid");
      letter.classList.add("invalid");
    }

    // Validate capital letters
    var upperCaseLetters = /[A-Z]/g;
    if (myInput.value.match(upperCaseLetters)) {
      capital.classList.remove("invalid");
      capital.classList.add("valid");
    } else {
      capital.classList.remove("valid");
      capital.classList.add("invalid");
    }

    // Validate numbers
    var numbers = /[0-9]/g;
    if (myInput.value.match(numbers)) {
      number.classList.remove("invalid");
      number.classList.add("valid");
    } else {
      number.classList.remove("valid");
      number.classList.add("invalid");
    }

    // Validate length
    if (myInput.value.length >= 8) {
      length.classList.remove("invalid");
      length.classList.add("valid");
    } else {
      length.classList.remove("valid");
      length.classList.add("invalid");
    }
  };

  var $password = $("#user_password_first"),
    $confirmation = $("#user_password_second"),
    $email = $("#user_email"),
    $submit = $("#user_submit"),
    $reset = $("#reset"),
    $erreur = $("#erreur"),
    $champPsw = $(".champ-password"),
    $champEmail = $(".champ-email");

  var emailFormat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
  var passwordFormat = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/g;

  $champEmail.keyup(function() {
    if (
      !typeof $(this).val() === "string" ||
      $(this).val().length < 8 ||
      $(this)
        .val()
        .match(emailFormat) === null
    ) {
      // si la chaîne de caractères est inférieure à 5
      $(this).css({
        // on rend le champ rouge
        borderColor: "red",
        color: "red"
      });
    } else {
      $(this).css({
        // si tout est bon, on le rend vert
        borderColor: "green",
        color: "green"
      });
    }
  });
  $champPsw.keyup(function() {
    if (
      !typeof $(this).val() === "string" ||
      $(this).val().length < 8 ||
      $(this)
        .val()
        .match(passwordFormat) === null
    ) {
      // si la chaîne de caractères est inférieure à 5
      $(this).css({
        // on rend le champ rouge
        borderColor: "red",
        color: "red"
      });
    } else {
      $(this).css({
        // si tout est bon, on le rend vert
        borderColor: "green",
        color: "green"
      });
    }
  });

  $confirmation.keyup(function() {
    if ($(this).val() != $password.val()) {
      // si la confirmation est différente du mot de passe
      $(this).css({
        // on rend le champ rouge
        borderColor: "red",
        color: "red"
      });
    } else {
      $(this).css({
        // si tout est bon, on le rend vert
        borderColor: "green",
        color: "green"
      });
    }
  });

  $submit.click(function(e) {
    console.log($email);

    console.log(checkEmpty($email));
    console.log(!typeof $email.val() === "string");
    console.log($email.val().length < 8);
    console.log($email.val().match(emailFormat) === null);

    if (
      checkEmpty($password) ||
      !typeof $password.val() === "string" ||
      $password.val().length < 8 ||
      $password.val().match(passwordFormat) === null
    ) {
      console.log(!checkEmpty($password));
      alert("bad password");
      return false;
    } else if (checkEmpty($confirmation)) {
      alert("bad password confirmation");
      return false;
    } else if (
      checkEmpty($email) ||
      !typeof $email.val() === "string" ||
      $email.val().length < 8 ||
      $email.val().match(emailFormat) === null
    ) {
      alert("bad email");
      return false;
    } else {
      return true;
    }
  });

  $reset.click(function() {
    $champPsw.css({
      // on remet le style des champs comme on l'avait défini dans le style CSS
      borderColor: "#ccc",
      color: "#555"
    });
    $champEmail.css({
      borderColor: "#ccc",
      color: "#555"
    });

    $erreur.css("display", "none"); // on prend soin de cacher le message d'erreur
    $erreur.css("display", "none");
  });

  function checkEmpty(champ) {
    if (champ.val() == "") {
      // si le champ est vide
      $erreur.css("display", "block"); // on affiche le message d'erreur
      champ.css({
        // on rend le champ rouge
        borderColor: "red",
        color: "red"
      });
    }
  }
});
