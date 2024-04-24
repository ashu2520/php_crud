function validateForm() {
    console.log('validateName:', validateName());
    console.log('validateMobile:', validateMobileNumber());
    console.log('validateGender:', vaildategender());
    console.log('validatelocation:', validatelocation());

    if (!validateName() || !vaildategender() || !validateMobileNumber() || !validatelocation()) {
        alert("Oh! Something went wrong");
        return false;
    }
    else {
        return true;
    }
}

function validateName() {
    var name = document.getElementById("name_input").value.trim();
    var nameRegex = /^[a-zA-Z\s'-]+$/;

    if (name === "") {
        document.getElementById("name_err").innerHTML = "Please enter your name.";
        name_input.style.borderColor = "black";
        return false;
    } else if (!nameRegex.test(name)) {
        document.getElementById("name_err").innerHTML = "Please enter a valid name(letters and spaces only).";
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