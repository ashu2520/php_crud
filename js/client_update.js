function validateForm() {
    console.log('validateName:', validateName());
    console.log('validateMobile:', validateName());
    console.log('validateGender:', vaildategender());
    
    if (!validateName() || !validateName() || !vaildategender() ) {
        alert("Oh! Something went wrong");
        return false;
    }
    else{
        return true;
    }
}
function validateName() {
    var name = document.getElementById("name_input").value.trim();
    var nameRegex = /^[a-zA-Z\s'-]+$/;

    if (name === "") {
        document.getElementById("name_err").innerHTML = "Please Enter Your Name.";
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

function validateMobileNumber() {
    // clearTimeout(debounceTimerMobile);
    // debounceTimerMobile = setTimeout(() => {
    var mobileNumber = document.getElementById("mobile_input").value;
    var mobileRegex = /^[0-9]{10}$/;

    if (mobileNumber === "") {
        document.getElementById("mobile_error").innerHTML = "Please Enter Mobile Number.";
        mobile_input.style.borderColor = "black";
        return false;
    }
    else if (!mobileRegex.test(mobileNumber) || isNaN(mobileNumber)) {
        document.getElementById("mobile_error").innerHTML = "Please enter a valid mobile number of length 10.";
        mobile_input.style.borderColor = "black";
        return false;
    } else {
        document.getElementById("mobile_error").innerHTML = "";
        mobile_input.style.borderColor = "green";
        return true;
    }
// }, 300);
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