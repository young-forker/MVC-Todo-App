// Define variables to access the form, username input, password input, and the alert display element
let form = document.querySelector("form");
let username = document.getElementById("username");
let password = document.getElementById("password");
let danger_alert = document.getElementById("danger-alert");

// Add event listener for the form's submit event
form.addEventListener("submit", function (e) {
  // Prevent the default form submission behavior
  e.preventDefault();

  // Retrieve the values entered for username and password
  let username_value = username.value;
  let password_value = password.value;

  // Initialize a new XMLHttpRequest to handle the login process
  let xhr = new XMLHttpRequest();

  // Define what happens on successful data submission
  xhr.onreadystatechange = function () {
    // Check if request is complete and response is ready
    if (this.readyState == 4 && this.status == 200) {
      // Parse JSON response from server
      let responseData = JSON.parse(xhr.responseText);

      // Check if response indicates an error
      if (responseData.stat == "error") {
        // Display error message in danger alert div
        danger_alert.innerText = responseData.message;
        // Make the danger alert visible
        danger_alert.classList.add("d-block");
        danger_alert.classList.remove("invisible");
      } else {
        // Hide the danger alert
        danger_alert.classList.add("invisible");
        // Redirect user to the main page upon successful login
        window.location.replace("index.php?action=main");
      }
    }
  };

  // Setup and send an asynchronous POST request to the authentication endpoint
  xhr.open("POST", "index.php?action=authentification", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  // Send the username and password as URL encoded form data
  xhr.send(`username=${username_value}&password=${password_value}`);
});