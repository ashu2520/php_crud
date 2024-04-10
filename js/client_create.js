async function validateForm() {
    console.log('validateName:', validateName());
    console.log('validateMobileNumber:', validateMobileNumber());
    console.log('validateEmail', await validateEmail());
    console.log('validateGender:', vaildategender());
    // console.log('validatelocation:', validatelocation());
    console.log('validatePosition:', validatePosition());
    console.log('validateRole:', validateRole());

    console.log('validatePassword:', validatePassword());
    console.log('validateConfirmPassword:', validateConfirmPassword());
    
    if (!validateName() || !validateMobileNumber() || !(await validateEmail()) || !vaildategender() || !validatePosition() || !validateRole() || !validatePassword() || !validateConfirmPassword()) {
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

function validateMobileNumber() {
    var mobileNumber = document.getElementById("mobile_input").value;
    var mobileRegex = /^[0-9]{10}$/;

    if (mobileNumber === "") {
        document.getElementById("mobile_error").innerHTML = "Please enter mobile number.";
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
}

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
    var passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^\da-zA-Z\s]).{8,}$/;
    validateConfirmPassword();
    if (!passwordRegex.test(password)) {
        document.getElementById("passworderr").innerHTML = "Enter the combination of at least 8 numbers, letters, and punctuation marks.";
        password_input.style.borderColor = "black";
        return false;
    } else {
        document.getElementById("passworderr").innerHTML = "";
        password_input.style.borderColor = "green";
        return true;
    }
}

function validateConfirmPassword() {
    var password = document.getElementById('password_input').value;
    var confirm_password = document.getElementById('confirm_password_input').value;

    if (confirm_password === "" || password !== confirm_password) {
        document.getElementById("confirm_password_err").innerHTML = "Password missmatched.";
        confirm_password_input.style.borderColor = "black";
        return false;
    } else {
        document.getElementById("confirm_password_err").innerHTML = "";
        confirm_password_input.style.borderColor = "green";
        return true;
    }
}