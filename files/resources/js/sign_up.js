// Access form elements by their IDs
let username_input = document.getElementById("username"),
    email_input = document.getElementById("email"),
    password_input = document.getElementById("password"),
    confirm_password_input = document.getElementById("confirm_password"),
    alert_box = document.getElementById("danger-alert"), // To display messages
    form = document.querySelector("form"); // The form itself

// Add an event listener to handle the form submission
form.addEventListener("submit", function (e) {
    e.preventDefault(); // Prevent the default form submission action

    // Collect values from the form inputs
    let username = username_input.value,
        email = email_input.value,
        password = password_input.value,
        confirm_password = confirm_password_input.value;

    // Initialize a new XMLHttpRequest to communicate with the server without reloading the page
    let xhr = new XMLHttpRequest();

    // Define what happens on successful data submission
    xhr.onreadystatechange = function () {
        // Check if request is complete
        if (this.readyState == 4 && this.status == 200) {
            // Parse JSON response from server
            let request_response = JSON.parse(this.responseText);

            // Check if the server responded with a success status
            if (request_response.status == "success") {
                // Redirect to the login page upon successful registration
                window.location.replace("index.php?action=login");
            } else {
                // If registration failed, display the error message
                alert_box.innerText = request_response.message;
                alert_box.classList.remove("invisible"); // Make sure the alert box is visible
            }
        }
    };

    // Open a POST request to the server-side registration handling script
    xhr.open("POST", "index.php?action=register");
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); // Set the content type of the request

    // Send the collected data as URL encoded form data
    xhr.send(`username=${username}&email=${email}&password=${password}&confirm_password=${confirm_password}`);
});