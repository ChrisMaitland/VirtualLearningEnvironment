function checkPasswordsValid() {
    if (document.getElementById('pw1').value.length < 6 || document.getElementById('pw2').value.length < 6
            || document.getElementById('pw1').value.length > 15 || document.getElementById('pw2').value.length > 15) {
        document.getElementById('message').style.color = 'red';
        document.getElementById('message').innerHTML = 'Password must be between 6 and 15 characters';
        document.getElementById('submit').disabled = true;
        return false;
    } else if (document.getElementById('pw1').value == document.getElementById('pw2').value) {
        document.getElementById('message').style.color = 'green';
        document.getElementById('message').innerHTML = 'Passwords match';
        document.getElementById('submit').disabled = false;
        return true;
    } else {
        document.getElementById('message').style.color = 'red';
        document.getElementById('message').innerHTML = 'Passwords do not match';
        document.getElementById('submit').disabled = true;
        return false;
    }
}

function validate() {
    //check email not null and length
    if (document.getElementById('Email').value.length == 0 || document.getElementById('Email').value.length > 100) {
        return false;
    }
    //N.B. email format check is already handled by type restriction
    //check name not null and length
    if (document.getElementById('Name').value.length == 0 || document.getElementById('Name').value.length > 50) {
        return false;
    }
    //check passwords are valid length
    if (checkPasswordsValid() == false) {
        return false;
    }
    //N.B. passwords matching check is already handled when passwords are changed

    return true;
}