function validateForm() {
    console.log('validateName:', validateName());
    console.log('validateMobileNumber:', validateMobileNumber());
    console.log('validateEmail', validateEmail());
    console.log('validate Subject:', validateSubject());
    console.log('validate Message:', validateMessage());
    if (!validateName() || !validateMobileNumber() || !validateEmail() || !validateSubject() || !validateMessage()) {
        // console.log("here I am")
        return false;
    }
    // alert("asdfghjk");
    return true;
}
function validateName() {
    var name = document.getElementById("name_input").value.trim();
    var nameRegex = /^[a-zA-Z\s'-]+$/;

    if (name === "") {
        document.getElementById("name_err").innerHTML = "Please enter your name.";
        name_input.style.borderColor = "black";
        name_input.style.color = "red";
        return false;
    } else if (!nameRegex.test(name)) {
        document.getElementById("name_err").innerHTML =
            "Please enter a valid name(letters and spaces only).";
        name_input.style.borderColor = "black";
        name_input.style.color = "red";
        return false;
    } else {
        document.getElementById("name_err").innerHTML = "";
        name_input.style.borderColor = "green";
        name_input.style.color = "green";
        return true;
    }
}
// let debounceTimer;
function validateEmail() {
    // clearTimeout(debounceTimer);
    // debounceTimer = setTimeout(() => {
    var email = document.getElementById("email_input").value.trim();
    var emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    
    if (email === "" || !email || email == null || email == " ") {
        document.getElementById("email_err").innerHTML = "Please enter an email address.";
        email_input.style.borderColor = "black";
        email_input.style.color = "red";
        return false;
    } else if(!emailRegex.test(email))
    {
        document.getElementById("email_err").innerHTML = "Please enter a valid email address.";
        email_input.style.borderColor = "black";
        email_input.style.color = "red";
        return false;
    }
    document.getElementById("email_err").innerHTML = "";
    email_input.style.borderColor = "green";
    email_input.style.color = "green";
    return true; 
    // }, 300);
}

function validateMobileNumber() {
    var mobileNumber = document.getElementById("mobile_input").value;
    var mobileRegex = /^[0-9]{10}$/;

    if (mobileNumber === "") {
        document.getElementById("mobile_error").innerHTML = "Please enter mobile number.";
        mobile_input.style.borderColor = "black";
        mobile_input.style.color = "red";
        return false;
    }
    else if (!mobileRegex.test(mobileNumber) || isNaN(mobileNumber)) {
        document.getElementById("mobile_error").innerHTML = "Please enter a valid mobile number of length 10.";
        mobile_input.style.borderColor = "black";
        mobile_input.style.color = "red";
        return false;
    } else {
        document.getElementById("mobile_error").innerHTML = "";
        mobile_input.style.borderColor = "green";
        mobile_input.style.color = "green";

        return true;
    }
}

function validateSubject() {
    var subject = document.getElementById("subject_input").value.trim();
    var subjectRegex = /^(?![_\W])[\w\d].{0,254}$/;

    if (subject === "") {
        document.getElementById("subject_error").innerHTML = "Please enter subject";
        subject_input.style.borderColor = "black";
        return false;
    } else if (!subjectRegex.test(subject)) {
        document.getElementById("subject_error").innerHTML = "Please enter a valid subject.";
            subject_input.style.borderColor = "black";
            subject_input.style.color = "red";

        return false;
    } else {
        document.getElementById("subject_error").innerHTML = "";
        subject_input.style.borderColor = "green";
        subject_input.style.color = "green";
        return true;
    }
}
function validateMessage(){
    var message = document.getElementById("message_input").value.trim();
    const messageRegex = /^(?!(\S{46,}\s*))(?=(\S+\s*){1,128}$).+$/;
    
    if(message === "" || !message || message == null){  
        document.getElementById("message_error").innerHTML = "Message cannot be empty."
        message_input.style.color = "red";
        message_input.style.borderColor = "black";
        return false;
    } // else if (!messageRegex.test(message)) {
    //         document.getElementById("message_error").innerHTML = "Word limit exceeded";
    //         message_input.style.borderColor = "black";
    //         message_input.style.color = "red";

    //     return false;
    // }
    else{
        document.getElementById("message_error").innerHTML = "";
        message_input.style.borderColor = "green";
        message_input.style.color = "green";
        return true;
    }

}



