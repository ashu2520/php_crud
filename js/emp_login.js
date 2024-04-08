
let debounceTimer;
function validateEmail() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
    var email = document.getElementById("email_input").value.trim();
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (email === "" || !emailRegex.test(email)) {
        document.getElementById("email_err").innerHTML = "Invalid Username";
        email_input.style.borderColor = "black";
        return false;
    } 
    document.getElementById("email_err").innerHTML = "";
    return true; 
    }, 300);
}

// For Flash Messages
setTimeout(function () {
    document.getElementById("flash-message").style.display = 'none';
  }, 10000);
  
  setTimeout(function () {
    document.getElementById("error-message").style.display = 'none';
  }, 3000);