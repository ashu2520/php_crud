async function validateForm() {
    console.log('validateName:', validateFirst_Name());
    console.log('validateAge:', validateAge());
    console.log('validateEmail', await validateEmail());
    console.log('validateGender:', vaildategender());
    console.log('validate_user_Type:', validateUser_Type());
    console.log('validatePassword:', validatePassword());
    
    if (!validateFirst_Name() || !vaildategender() || !validateAge() || !(await validateEmail())  || !validateUser_Type() || !validatePassword() ) {
        // console.log("here I am")
        alert("I am here");
        return false;
    }
    
    // alert("asdfghjk");
    document.getElementById("main").submit();
}
function validateInput(inputId, errorId) {
    var inputValue = document.getElementById(inputId).value.trim();
    var regex = /^[a-zA-Z\s'-]+$/;

    if (inputValue === "") {
        document.getElementById(errorId).innerHTML = "Please Enter a Value.";
        document.getElementById(inputId).style.borderColor = "black";
        return false;
    } else if (!regex.test(inputValue)) {
        document.getElementById(errorId).innerHTML = "Please enter a valid input(letters and spaces only).";
        document.getElementById(inputId).style.borderColor = "black";
        return false;
    } else {
        document.getElementById(errorId).innerHTML = "";
        document.getElementById(inputId).style.borderColor = "green";
        return true;
    }
}

function validateFirst_Name() {
    return validateInput("First_Name_input", "First_Name_err");
}

function validateLast_Name() {
    return validateInput("Last_Name_input", "Last_Name_err",);
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

function validateAge() {
    var age = document.getElementById('Age_input').value.trim();
    var regex = /^[1-9][0-9]*$/;

    if (age === "") {
        document.getElementById("Age_error").innerHTML = "Please Enter a Value.";
        document.getElementById("Age_input").style.borderColor = "black";
        return false;
    } else if (!regex.test(age)) {
        document.getElementById("Age_error").innerHTML = "Please enter a valid input.";
        document.getElementById("Age_input").style.borderColor = "black";
        return false;
    } else {
        document.getElementById("Age_error").innerHTML = "";
        document.getElementById("Age_input").style.borderColor = "green";
        return true;
    }
}

URL = "client_email_validation.php"
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
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

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
function validateUser_Type() {
    return validateInput("User_Type_input", "User_Type_err");
}

function validatePassword() {
    var password = document.getElementById('password_input').value;
    var passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^\da-zA-Z\s]).{8,}$/;
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

    if (password !== confirm_password) {
        document.getElementById("confirm_password_err").innerHTML = "Password Missmatched.";
        confirm_password_input.style.borderColor = "black";
        return false;
    } else {
        document.getElementById("confirm_password_err").innerHTML = "";
        confirm_password_input.style.borderColor = "green";
        return true;
    }
}