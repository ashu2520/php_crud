async function validateForm() {
    console.log('validateName:', validateName());
    console.log('validateMobileNumber:', validateMobileNumber());
    console.log('validateEmail', await validateEmail());
    console.log('validateGender:', vaildategender());
    console.log('validatelocation:', validatelocation());
    console.log('validatePosition:', validatePosition());
    console.log('validateRole:', validateRole());
    console.log('validatePassword:', validatePassword());
    // console.log('validateConfirmPassword:', validateConfirmPassword());

    if (!validateName() || !validateMobileNumber() || !(await validateEmail()) || !vaildategender() || !validatelocation() || !validatePosition() || !validateRole() || !validatePassword()) {
        return false;
    }

    document.getElementById("main").submit();
}
function validateName() {
    var name = document.getElementById("name_input").value.trim();
    var nameRegex = /^[a-zA-Z\s'-]+$/;

    if (name === "") {
        document.getElementById("name_err").innerHTML = "Please enter your name.";
        name_input.style.borderColor = "black";
        return false;
    } else if (!nameRegex.test(name)) {
        document.getElementById("name_err").innerHTML =
            "Please enter a valid name(letters and spaces only).";
        name_input.style.borderColor = "black";

        return false;
    } else {
        document.getElementById("name_err").innerHTML = "";
        name_input.style.borderColor = "green";
        return true;
    }
}
// Mobile Input validation
$(document).ready(function () {
    $('#mobile_input').blur(validateMobileNumber);
});
function validateMobileNumber() {
    var mobileNumber = $("#mobile_input").val().trim();
    var placeholder = $("#mobile_input").attr('placeholder');
    var mobileRegex = /^[1-9]\d*$/;
    console.log(mobileNumber);
   
    if (mobileNumber == "" || mobileNumber.length !== placeholder.length) {
        $("#mobile_input").css("border-color", "black");
        $("#mobile_error").html("Please enter mobile number."); 
        return false; 
    } 
    mobileNumber = mobileNumber.replace(/[\(\)\s-]/g, "");
    console.log(mobileNumber);

    if (!mobileRegex.test(mobileNumber)) {
        $("#mobile_input").css("border-color", "black");
        $("#mobile_error").html("Number should not start with 0."); 
        return false;
    }
     else {
        $("#mobile_input").css("border-color", "green");
        $("#mobile_error").html(""); 
        return true; 
    }
}

// Mobile Input Maskings
window.onload = function () {
    $(document).ready(function () {
        // $("#mobile_input").inputmask('####-###-###');
        function updatePlaceholder() {
            let country_id = $('#country_code').val();
            console.log(country_id);

            $.ajax({
                url: 'countries.php',
                type: 'POST',
                contentType: 'application/x-www-form-urlencoded',
                data: { country_id: country_id },
                success: function (response) {
                    // console.log(response); 
                    response = JSON.parse(response); // decode the JSON into key value-pair
                    console.log(response);

                    $("#mobile_input").attr('placeholder', response.ph_mask);
                    $("#mobile_input").inputmask(response.ph_mask);
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText); // Log any errors
                }
            });
        }

        updatePlaceholder()
        // Call the updatePlaceholder function when the select element changes
        $('#country_code').change(updatePlaceholder);
    });
};


URL = "email_validation.php"
async function isDuplicateEmail(email = '') {
    try {
        const response = await fetch(URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `email=${email}`,
        });

        const data = await response.json();
        // console.log(data);
        return data.exists; // true if email exists, false otherwise
    } catch (error) {
        console.log('Error checking email:', error);
        return false; // Handle error scenario
    }

}
let debounceTimer;
function validateEmail() {
    return new Promise(resolve => {
        clearTimeout(debounceTimer); // Clear the previous timer

        debounceTimer = setTimeout(async () => {
            var email = document.getElementById("email_input").value.trim();
            var emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

            if (email == "" || !email || email == null || email == " ") {
                document.getElementById("email_err").innerHTML = "Please enter an email address.";
                email_input.style.borderColor = "black";
                resolve(false);
            } else if (!emailRegex.test(email)) {
                document.getElementById("email_err").innerHTML = "Please enter a valid email address.";
                email_input.style.borderColor = "black";
                resolve(false);
            } else {
                const isDuplicate = await isDuplicateEmail(email);
                if (isDuplicate) {
                    document.getElementById("email_err").innerHTML = "Email address already exists.";
                    email_input.style.borderColor = "black";
                    resolve(false);
                } else {
                    // This block ensures that the promise is resolved with true
                    document.getElementById("email_err").innerHTML = "";
                    email_input.style.borderColor = "green";
                    resolve(true);
                }
            }
        }, 300);
    });
}

function vaildategender() {
    var genderMale = document.getElementById('gender_male').checked;
    var genderFemale = document.getElementById('gender_female').checked;
    // The .checked is used to check whether a checkbox or radio button is checked or not. 
    if (!genderMale && !genderFemale) {
        document.getElementById("gender_error").innerHTML = "Value cannot be empty.";
        // email_input.style.borderColor = "black";
        return false;
    }
    else {
        document.getElementById("gender_error").innerHTML = "";
        return true;
    }
}

function validatelocation() {
    let country = document.getElementById('country_select').value;
    let state = document.getElementById('state_select').value;
    console.log(state);
    if (country != "" && country != "Select Country" && state != "" && state != "Select State") {
        document.getElementById("location_error").innerHTML = "";
       document.getElementById('country_select').style.borderColor = "green";
       document.getElementById('state_select').style.borderColor = "green";
        return true;
    } else {
        document.getElementById("location_error").innerHTML = "Please select your loaction.";
        document.getElementById('country_select').style.borderColor = "black";
       document.getElementById('state_select').style.borderColor = "black";
        return false;
    }
}

function loadCountry() {
    var country = document.getElementById('country_select').value;
    var state = document.getElementById('state_select');
    state.innerHTML = "";
    let option = document.createElement('option');
    option.textContent = "Select State";
    state.appendChild(option);

    state.disabled = true;
    console.log(country);
    if (country != "" && country != "Select Country") {
        state.disabled = false;

        fetch('state.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `country_code=${country}`,
        })
            .then(response => response.json())
            .then(data => {
                data.forEach(function (stateData) {
                    // console.log(stateData);
                    var option = document.createElement('option');
                    option.value = stateData.state_id;
                    // console.log(option.value);
                    option.textContent = stateData.state_name;
                    state.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
}

function validatePosition() {
    var positionInput = document.getElementById("User_type_input");
    var position = positionInput.value.trim();

    if (position === "Select Position") {
        document.getElementById("position_err").innerHTML = "Please select a position.";
        positionInput.style.borderColor = "black";
        return false;
    } else {
        document.getElementById("position_err").innerHTML = "";
        positionInput.style.borderColor = "green";
        return true;
    }
}

function validateRole() {
    var roleInput = document.getElementById("role_input");
    var role = roleInput.value.trim();

    if (role === "Select Role") {
        document.getElementById("role_error").textContent = "Please select a role.";
        roleInput.style.borderColor = "black";
        return false;
    } else {
        document.getElementById("role_error").textContent = "";
        roleInput.style.borderColor = "green";
        return true;
    }
}

function validatePassword() {
    var password = document.getElementById('password_input').value;
    var confirm_password = document.getElementById('confirm_password_input').value;
    var lower_regex = /[a-z]/;
    var upper_regex = /[A-Z]/;
    var num_regex = /\d/;
    var special_regex = /[^a-zA-Z0-9\s]/;
    var length_regex = /^.{8,16}$/;

    if (lower_regex.test(password)) {
        $("#password-lowercase i").removeClass("fa-xmark").addClass("fa-check").css("color", "green");
        $("#password-lowercase").css("color", "green");
    } else {
        $("#password-lowercase i").removeClass("fa-check").addClass("fa-xmark").css("color", "red");
        $("#password-lowercase").css("color", "red");
    }

    if (upper_regex.test(password)) {
        $("#password-uppercase i").removeClass("fa-xmark").addClass("fa-check").css("color", "green");
        $("#password-uppercase").css("color", "green");
    } else {
        $("#password-uppercase i").removeClass("fa-check").addClass("fa-xmark").css("color", "red");
        $("#password-uppercase").css("color", "red");
    }

    if (num_regex.test(password)) {
        $("#password-number i").removeClass("fa-xmark").addClass("fa-check").css("color", "green");
        $("#password-number").css("color", "green");
    } else {
        $("#password-number i").removeClass("fa-check").addClass("fa-xmark").css("color", "red");
        $("#password-number").css("color", "red");
    }

    if (special_regex.test(password)) {
        $("#password-special i").removeClass("fa-xmark").addClass("fa-check").css("color", "green");
        $("#password-special").css("color", "green");
    } else {
        $("#password-special i").removeClass("fa-check").addClass("fa-xmark").css("color", "red");
        $("#password-special").css("color", "red");

    }

    if (length_regex.test(password)) {
        $("#password-length i").removeClass("fa-xmark").addClass("fa-check").css("color", "green");
        $("#password-length").css("color", "green");
    } else {
        $("#password-length i").removeClass("fa-check").addClass("fa-xmark").css("color", "red");
        $("#password-length").css("color", "red");
    }

    // Confirm Password validation...
    var confirm_password_flag = false;
    if (confirm_password === "") {
        $("#confirm_password_err").html("");
        $("#confirm_password_input").css("border-color", "black");
        confirm_password_flag = false;

    } else if (password === confirm_password) {
        $("#confirm_password_err").html("");
        $("#confirm_password_input").css("border-color", "green");
        confirm_password_flag = true;
    } else {
        $("#confirm_password_err").html("Password Missmatched.");
        $("#confirm_password_input").css("border-color", "black");
        confirm_password_flag = false;
    }


    if (lower_regex.test(password) && upper_regex.test(password) && num_regex.test(password) && special_regex.test(password) && length_regex.test(password)) {
        $('#password_input').css("border-color", "green");
        $('.tool-tip-create').css("border-color", "green");
        $('#password-check').css('color', 'green');
        $('.tool-tip-create').addClass('green-border');

        if (confirm_password_flag)
            return true;
        else
            return false;
    } else {
        $('#password_input').css("border-color", "black");
        $('.tool-tip-create').css("border-color", "red");
        $('#password-check').css('color', 'red');
        $('.tool-tip-create').removeClass('green-border');
        return false;
    }
}
setTimeout(function () {
    document.getElementById("error-message").style.display = 'none';
  }, 3000);