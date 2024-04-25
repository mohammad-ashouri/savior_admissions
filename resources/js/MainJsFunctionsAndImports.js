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

export function isEnglish(text) {
    let englishRegex = /^[a-zA-Z]+$/;
    return englishRegex.test(text);
}

export function validateEnglishInput(elementId, elementName) {
    let inputValue = $("#" + elementId).val();
    if (!isEnglish(inputValue)) {
        $("#" + elementId).val("");
        return false;
    }
    return true;
}

export function validatePasswordEntry(elementId, elementName) {
    let inputValue = $("#" + elementId).val();
    let englishRegex = /^[a-zA-Z0-9!@#$%^&*()_+-=]+$/;
    if (!englishRegex.test(inputValue)) {
        $("#" + elementId).val("");
        return false;
    }
    return true;
}

export function checkAge(birthDate) {
    let today = new Date();
    let birthDateObj = new Date(birthDate);
    let age = today.getFullYear() - birthDateObj.getFullYear();
    let monthDiff = today.getMonth() - birthDateObj.getMonth();
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDateObj.getDate())) {
        age--;
    }
    return age >= 15;
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
