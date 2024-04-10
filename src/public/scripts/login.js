// Login page

document.getElementById("login-form").addEventListener("submit", function(event) {
    event.preventDefault();

    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;

    if (email && password) {
      window.location.href = "visitormain.html";
    } else {
      alert("Please fill in both email and password fields.");
    }
  });
