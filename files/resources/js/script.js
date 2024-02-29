// Global state array to hold todo items
var state = [];

// Event listener for checkbox status change
$('#update-todo-status').change(function () {
    // Play audio when checkbox is checked
    if (this.checked) {
        var audio = new Audio('files/resources/audio/complete.wav'); // Specify the path to your sound file
        audio.play(); // Play the sound
    }
});

// Function to initialize the application state
function setDefaultState() {
    var id = generateID(); // Generate a unique ID
    var baseState = {}; // Initialize an empty object for the base state
    baseState[id] = {}; // Assign an empty object to the generated ID within the state
    syncState(baseState); // Sync the modified state to localStorage
}

// Function to generate a unique ID
function generateID() {
    var randLetter = String.fromCharCode(65 + Math.floor(Math.random() * 26)); // Generate a random letter A-Z
    return randLetter + Date.now(); // Return the letter combined with the current timestamp
}

// Function to add a new todo item to the state
function pushToState(title, status, id) {
    var baseState = getState(); // Retrieve the current state
    baseState[id] = {id: id, title: title, status: status}; // Update the state with the new todo item
    syncState(baseState); // Sync the modified state to localStorage
}

// Function to toggle the completion status of a todo item
function setToDone(id) {
    var baseState = getState(); // Retrieve the current state
    // Toggle the status of the specified todo item
    if (baseState[id].status === 'new') {
        baseState[id].status = 'done'
    } else {
        baseState[id].status = 'new';
    }
    syncState(baseState); // Sync the modified state to localStorage
}

// Function to delete a todo item
function deleteTodo(id) {
    $.ajax({
        url: '../../index.php',
        type: 'POST',
        data: {action: 'delete', id: id},
        success: function (response) {
            // Upon successful deletion from the backend, remove the item from the UI and update the state
            var baseState = getState();
            delete baseState[id];
            syncState(baseState);
            $('li[data-id="' + id + '"]').remove();
        },
        error: function (xhr, status, error) {
            console.error("Error deleting todo: ", error);
        }
    });
}

// Function to reset the application state
function resetState() {
    localStorage.setItem("state", null);
}

// Function to sync the current state to localStorage
function syncState(state) {
    localStorage.setItem("state", JSON.stringify(state));
}

// Function to retrieve the current state from localStorage
function getState() {
    var storedState = localStorage.getItem("state");
    return storedState ? JSON.parse(storedState) : {}; // Parse the stored state or return an empty object if none exists
}

// Function to add a new todo item to the UI and state
function addItem(text, status, id, noUpdate) {
    var id = id ? id : generateID();
    var c = status === "done" ? "danger" : "";
    var item =
            '<li data-id="' +
            id +
            '" class="animated flipInX ' +
            c +
            '"><div class="checkbox"><span class="update" id="myUpdateBtn"><i class="fa fa-pencil"></i></span><span class="close"><i class="fa fa-times"></i></span><label><span class="checkbox-mask"></span><input type="checkbox" />' +
            text +
            "</label></div></li>";

    var isError = $(".form-control").hasClass("hidden");

    if (text === "") {
        $(".err")
                .removeClass("hidden")
                .addClass("animated bounceIn");
    } else {
        $(".err").addClass("hidden");
        $(".todo-list").append(item);
    }

    $(".refresh").removeClass("hidden");

    $(".no-items").addClass("hidden");

    $(".form-control")
            .val("")
            .attr("placeholder", "✍️ Add item...");
    setTimeout(function () {
        $(".todo-list li").removeClass("animated flipInX");
    }, 500);

    if (!noUpdate) {
        pushToState(text, "new", id);
    }


    var todoText = text;
    var dueDateText = $("#selected-due-date-adding").text();  // Get the due date value
    var dueDate = dueDateText ? dueDateText : null;


    // AJAX request to add the todo
    $.ajax({
        url: '../../index.php',
        type: 'POST',
        data: {
            action: 'ajouter',
            todo: todoText,
            due_date: dueDate,
            user_username: 'admin',
        },
        success: function (response) {
            // Clear the selected due date after adding the item
            $('#selected-due-date-adding').text(''); // This line clears the content
        },
        error: function (xhr, status, error) {
            // Handle error
            console.error("Error adding todo: ", error);
        }
    });

}

function addItemWithDueDate(text, status, id, dueDate, noUpdate) {

    var today = new Date().setHours(0, 0, 0, 0);
    var due = new Date(dueDate * 1000).setHours(0, 0, 0, 0);
    var dueClass = '';
    var id = id ? id : generateID();
    var c = status === "done" ? "danger" : "";

    if (dueDate) {
        if (due < today) {
            dueClass = 'past-due'; // Class for past due items
        } else if (due === today) {
            dueClass = 'due-today'; // Class for items due today
        }
    }

    var item = '<li data-id="' + id + '" class="animated flipInX ' + c + ' ' + dueClass + '">' +
            '<div class="checkbox"><span class="update" id="myUpdateBtn"><i class="fa fa-pencil"></i></span><span class="close"><i class="fa fa-times"></i></span><label><span class="checkbox-mask"></span><input type="checkbox" />' +
            text +
            (dueDate ? ' <small class="due-date-text">(Due: ' + new Date(dueDate * 1000).toLocaleDateString() + ')</small>' : '') + // Display the due date if available
            '</label></div></li>';

    var isError = $(".form-control").hasClass("hidden");

    if (text === "") {
        $(".err")
                .removeClass("hidden")
                .addClass("animated bounceIn");
    } else {
        $(".err").addClass("hidden");
        $(".todo-list").append(item);
    }

    $(".refresh").removeClass("hidden");

    $(".no-items").addClass("hidden");

    $(".form-control")
            .val("")
            .attr("placeholder", "✍️ Add item...");
    setTimeout(function () {
        $(".todo-list li").removeClass("animated flipInX");
    }, 500);

    if (!noUpdate) {
        pushToState(text, "new", id);
    }
    // Clear the selected due date after adding the item
    $('#selected-due-date-adding').text(''); // This line clears the content
}


function refresh() {
    $(".todo-list li").each(function (i) {
        $(this)
                .delay(70 * i)
                .queue(function () {
                    $(this).addClass("animated bounceOutLeft");
                    $(this).dequeue();
                });
    });

    setTimeout(function () {
        $(".todo-list li").remove();
        $(".no-items").removeClass("hidden");
        $(".err").addClass("hidden");
    }, 800);
}

$(".todo-list").on("click", ".update", function () {


    var itemId = $(this).closest('li').data('id');
    var fullText = $(this).siblings('label').text().trim();
    var status = $(this).closest('li').data('status'); // Assuming 'status' is stored as data attribute

    // Extract todo text and due date from the full text
    var match = fullText.match(/^(.*?)( \(Due: (\d{2}\/\d{2}\/\d{4})\))?$/);
//    var match = fullText.match(/^(.*?)(?:\s*\(Due:\s*(\d{2}\/\d{2}\/\d{4})\))?$/);

    if (match) {
        var itemText = match[1];
        var dueDate = match[3]; // This might be undefined if no due date is present
        // Your existing logic
    } else {
        console.error("No match found for the text:", fullText);
    }

    var modal = document.getElementById("updateModal");
    modal.style.display = "block";
    $('#updateModal #update-todo-text').val(itemText);

    // Set the due date text and value if present
    if (dueDate) {
        $('#updateModal #selected-due-date').text(dueDate);
        $('#updateModal #update-todo-due-date').val(dueDate);
    } else {
        $('#updateModal #selected-due-date').text('');
        $('#updateModal #update-todo-due-date').val('');
    }

    // Set the checkbox based on the status
    $('#updateModal #update-todo-status').prop('checked', status === '1');

    $('#updateModal #update-item-id').val(itemId);
});

// Handle form submission from the modal
$('#update-todo-form').on('submit', function (e) {
    e.preventDefault();
    var updatedText = $('#update-todo-text').val();
    var updatedDueDate = $('#update-todo-due-date').val();
    var updatedStatus = $('#update-todo-status').prop('checked') ? '1' : '0';
    var itemId = $('#update-item-id').val();

// AJAX request to update the todo
    $.ajax({
        url: '../../index.php',
        type: 'POST',
        data: {
            action: 'update',
            id: itemId,
            todo: updatedText,
            due_date: updatedDueDate,
            status: updatedStatus
        },
        success: function (response) {
//            refresh();
            var modal = document.getElementById("updateModal");
            modal.style.display = "none";
            showSuccessToast(); // Show success toast
        },
        error: function (xhr, status, error) {
            console.error("Error updating todo: ", error);
        }
    });
});

function showSuccessToast() {
    var toast = document.getElementById("toastMessage");
    toast.className = "toast-message toast-show";
    toast.textContent = "Update Successful!"; // Set the toast message text
    setTimeout(function () {
        toast.className = toast.className.replace("toast-show", "");
    }, 5000); // Hide after 5 seconds
}



$(function () {
    var err = $(".err"),
            formControl = $(".form-control"),
            isError = formControl.hasClass("hidden");

    if (!isError) {
        formControl.blur(function () {
            err.addClass("hidden");
        });
    }

    $(".add-btn").on("click", function () {
        var itemVal = $(".form-control").val();
        addItem(itemVal);
        formControl.focus();
    });

    $(".refresh").on("click", refresh);

    $(".todo-list").on("click", 'input[type="checkbox"]', function () {
        var li = $(this)
                .parent()
                .parent()
                .parent();
        li.toggleClass("danger");
        li.toggleClass("animated flipInX");


        var audio = new Audio('files/resources/audio/complete.wav'); // Path to your sound file
        audio.play();

        setToDone(li.data().id);

        setTimeout(function () {
            li.removeClass("animated flipInX");
        }, 500);
    });

    $(".todo-list").on("click", ".close", function () {
        var box = $(this)
                .parent()
                .parent();

        if ($(".todo-list li").length == 1) {
            box.removeClass("animated flipInX").addClass("animated                bounceOutLeft");
            setTimeout(function () {
                box.remove();
                $(".no-items").removeClass("hidden");
                $(".refresh").addClass("hidden");
            }, 500);
        } else {
            box.removeClass("animated flipInX").addClass("animated bounceOutLeft");
            setTimeout(function () {
                box.remove();
            }, 500);
        }

        deleteTodo(box.data().id)
    });

    $(".form-control").keypress(function (e) {
        if (e.which == 13) {
            var itemVal = $(".form-control").val();
            addItem(itemVal);
        }
    });
    $(".todo-list").sortable();
    $(".todo-list").disableSelection();
});

var todayContainer = document.querySelector(".today");


var d = new Date();


var weekday = new Array(7);
weekday[0] = "Sunday 🖖";
weekday[1] = "Monday 💪😀";
weekday[2] = "Tuesday 😜";
weekday[3] = "Wednesday 😌☕️";
weekday[4] = "Thursday 🤗";
weekday[5] = "Friday 🍻";
weekday[6] = "Saturday 😴";


var n = weekday[d.getDay()];


var randomWordArray = Array(
        "Oh my, it's ",
        "Whoop, it's ",
        "Happy ",
        "Seems it's ",
        "Awesome, it's ",
        "Have a nice ",
        "Happy fabulous ",
        "Enjoy your "
        );

var randomWord =
        randomWordArray[Math.floor(Math.random() * randomWordArray.length)];


todayContainer.innerHTML = randomWord + n;

$(document).ready(function () {
//    var state = getState();

    if (!state) {
        setDefaultState();
        state = getState();
    }

    Object.keys(state).forEach(function (todoKey) {
        var todo = state[todoKey];
        addItem(todo.title, todo.status, todo.id, true);
    });

    var mins, secs, update;

    init();
    function init() {
        (mins = 25), (secs = 59);
    }


    set();
    function set() {
        $(".mins").text(mins);
    }

    $("#start").on("click", start_timer);
    $("#reset").on("click", reset);
    $("#inc").on("click", inc);
    $("#dec").on("click", dec);

    function start_timer() {

        set();

        $(".dis").attr("disabled", true);

        $(".mins").text(--mins);
        $(".separator").text(":");
        update_timer();

        update = setInterval(update_timer, 1000);
    }

    function update_timer() {
        $(".secs").text(secs);
        --secs;
        if (mins == 0 && secs < 0) {
            reset();
        } else if (secs < 0 && mins > 0) {
            secs = 59;
            --mins;
            $(".mins").text(mins);
        }
    }

    function reset() {
        clearInterval(update);
        $(".secs").text("");
        $(".separator").text("");
        init();
        $(".mins").text(mins);
        $(".dis").attr("disabled", false);
    }

    function inc() {
        mins++;
        $(".mins").text(mins);
    }

    function dec() {
        if (mins > 1) {
            mins--;
            $(".mins").text(mins);
        } else {
            alert("This is the minimum limit.");
        }
    }

    $(document).ready(function () {
        // Initialize the datepicker
        $("#update-todo-due-date").datepicker({
            onSelect: function (dateText) {
                $('#selected-due-date').text(dateText);
                // Additional logic to handle the selected date
            }
        });

        // Click handler to show the datepicker
        $("#due-date-picker-btn").click(function () {
            // Just show the datepicker
            $("#update-todo-due-date").datepicker("show");
        });
    });

});