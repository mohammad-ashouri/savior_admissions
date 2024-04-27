import './bootstrap';
import Swal from "sweetalert2";
import $ from "jquery";

window.Swal = Swal;

export function swalFire(title = null, text, icon, confirmButtonText) {
    Swal.fire({
        title: title, text: text, icon: icon, confirmButtonText: confirmButtonText,
    });
}

export function reloadCaptcha() {
    let captchaImg = document.getElementById('captchaImg');
    let captchaUrl = "/captcha";
    captchaImg.src = captchaUrl + '?' + Date.now();
}


export function validateIranianMobile(mobile) {
    let iranianMobilePattern = /^(\+98|0)?9\d{9}$/;

    return iranianMobilePattern.test(mobile);
}

// Function to check input for English characters
export function checkEnglishCharacters(event) {
    // Get the input value from the event target
    let inputValue = event.target.value;

    // Check if the input contains characters other than English letters
    if (/[^a-zA-Z\s]/.test(inputValue)) {
        return false; // Contains non-English characters
    } else {
        return true; // Contains only English characters
    }
}

// Function to check input for Persian characters
export function checkPersianCharacters(event) {
    // Get the input value from the event target
    let inputValue = event.target.value;

    // Check if the input contains characters other than Persian letters
    return !/[^آ-ی\s]/.test(inputValue);
}

export function validateAddressEntry(event) {
    let inputValue = event.target.value;
    let englishRegex = /^[a-zA-Z0-9-_ ]+$/;
    return englishRegex.test(inputValue);

}
export function validatePasswordEntry(elementId) {
    let inputValue = $("#" + elementId).val();
    let englishRegex = /^[a-zA-Z0-9!@#$%^&*()_+-=]+$/;
    if (!englishRegex.test(inputValue)) {
        $("#" + elementId).val("");
        return false;
    }
    return true;
}

export function checkAge(birthDate,ageInput=15) {
    let today = new Date();
    let birthDateObj = new Date(birthDate);
    let age = today.getFullYear() - birthDateObj.getFullYear();
    let monthDiff = today.getMonth() - birthDateObj.getMonth();
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDateObj.getDate())) {
        age--;
    }
    return age >= ageInput;
}


export function startTimer(duration, display) {
    let timer = duration, minutes, seconds;
    setInterval(function () {
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.text(minutes + ":" + seconds);

        if (--timer < 0) {
            timer = duration;
            location.reload();
        }
    }, 1000);

}

export function resetAllInputValues() {
    $('input:not([name="_token"]):not([name="id"])').each(function () {
        $(this).val(null);
    });
}

export function resetFields() {
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => input.value = "");
    const selectors = document.querySelectorAll('select');
    selectors.forEach(select => select.value = "");
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => textarea.value = "");
}

export function checkEnglishDigits(inputValue) {
    // Regular expression to match English digits
    let englishDigitsRegex = /^[0-9]*$/;
    // Check if the input contains anything other than English digits
    if (!englishDigitsRegex.test(inputValue)) {
        return false; // Contains non-English digits
    } else {
        return true; // Contains only English digits
    }
}

export function resetAllSelectValues() {
    // Select all select elements on the page
    $('select').each(function() {
        // Set the value of each select element to an empty string
        $(this).val("");
    });
}
