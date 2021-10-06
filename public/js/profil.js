/*
    profil.js
    -------
    File specific to javascript update profile
 */

const pseudoProfile = document.getElementById("pseudo-profile");
const emailProfile = document.getElementById("email-profile");
/**
 * #########
 * DATA FORM
 * #########
 */
let errors = {
    "errorPseudo": "",
    "errorTelephone": "",
    "errorNom": "",
    "errorPrenom": "",
    "errorEmail": "",
    "errorMdp": "",
    "errorConfirmMdp": "",
};
document.querySelectorAll(".group-value").forEach((e) => {
    e.addEventListener('focusout', (e) => {
        let dataAttr = e.currentTarget.querySelector('[data-error]').dataset.error;
        let inputValue = e.currentTarget.querySelector('input').value;
        validInputProfile(dataAttr, inputValue , e.currentTarget)
    })
})

/**
 * Match Password
 */
function matchPassword() {
    const formData = document.getElementById("formProfile");
    if (formData['mdp'].value !== formData['mdpConfirm'].value) {
        errors['errorConfirmMdp'] = "Les mots de passe ne correspondent pas";
        document.querySelector("[data-error=errorConfirmMdp]").textContent = errorsSignup['password-confirm'];
    }
}


/**
 * Submit
 */
let submit = document.getElementById("validSubmitProfile");
submit.addEventListener('click', () => {
    document.querySelectorAll(".group-value").forEach((e) => {
        let dataAttr = e.querySelector("[data-error]")
        let inputValue = e.querySelector('input').value;
        validInputProfile(dataAttr, inputValue , e)
    })
    const formProfile = document.getElementById('formProfile');
    if (
        errors.errorPseudo === "" &&
        errors.errorTelephone === "" &&
        errors.errorNom === "" &&
        errors.errorPrenom === "" &&
        errors.errorEmail === "" &&
        errors.errorMdp === "" &&
        errors.errorConfirmMdp === ""
    ) {
        formProfile.submit();
    }
});

/**
 * Function input
 */
function validInputProfile(dataAttr, inputValue , currentValue) {

    if (dataAttr === 'errorPseudo') {
        if(pseudoProfile.value !== sessionPseudo) {
            ajaxProfileCheckIsUsed(responsePseudoProfile, 'checkPseudo', pseudoProfile.value);
        }
        inputMinAndMaxChar(5, 20, inputValue, dataAttr, true);
        displayError(dataAttr, currentValue);
    }

    if (dataAttr === 'errorTelephone') {
        let isValidPhone = true;
        if (!inputValue.match(regexPhone)) {
            errors[dataAttr] = `Le numéro de téléphone n'est pas valide`;
            isValidPhone = false;
        }
        inputMinAndMaxChar(10, 20, inputValue, dataAttr, true, isValidPhone);
        displayError(dataAttr, currentValue);
    }

    if (dataAttr === 'errorNom') {
        inputMinAndMaxChar(0, 20, inputValue, dataAttr, false);
        displayError(dataAttr, currentValue);
    }

    if (dataAttr === 'errorPrenom') {
        inputMinAndMaxChar(0, 20, inputValue, dataAttr, false);
        displayError(dataAttr, currentValue);
    }

    if (dataAttr === 'errorEmail') {
        let isValidEmail = true;
        if (!inputValue.match(regexEmail)) {
            errors[dataAttr] = `L'email n'est pas valide`;
            isValidEmail = false;
        }
        if(emailProfile.value !== sessionEmail) {
            ajaxProfileCheckIsUsed(responseEmailProfile, 'checkEmail', emailProfile.value);

        }
        inputMinAndMaxChar(0, 50, inputValue, dataAttr, false, isValidEmail);
        displayError(dataAttr, currentValue);
    }

    if (dataAttr === 'errorMdp' || dataAttr === 'errorConfirmMdp') {
        let isValidMdp = true;
        if (!inputValue.match(regexPassword)) {
            errors[dataAttr] = `Le mot de passe n'est pas valide`;
            isValidMdp = false;
        }
        inputMinAndMaxChar(8, 60, inputValue, dataAttr, false, isValidMdp);
        matchPassword();
        displayError(dataAttr, currentValue);
    }
}



// Delete USER
function delUser(id) {
    ajaxDelete('deleteUserBySESSION_ID', id);
}


/**
 * ###########################
 *     || -- INIT -- ||
 * CHECK EMAIL/PSEUDO IS USED
 *        -- AJAX --
 */
function ajaxProfileCheckIsUsed(response, appendKey, value) {
    initXhr();
    const formDataProfile = new FormData();
    formDataProfile.append(appendKey, value);
    xhr.onreadystatechange = response;
    xhr.open('POST', 'include/auth/post-isused.php', true);
    xhr.send(formDataProfile);
}

/**
 * ###################
 * CHECK EMAIL IS USED
 *      -- AJAX --
 * ###################
 */
function responseEmailProfile() {
    if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.readyState === 4 && xhr.status === 200) {
            if (JSON.parse(xhr.responseText).responseEmail === true) {
                errors.errorEmail = "L'email existe déjà";
                document.querySelector("[data-error=errorEmail]").textContent = errors['errorEmail'];
            }
        } else {
            console.log('Il y a eu un problème avec la requête.');
        }
    }
}
/**
 * ####################
 * CHECK PSEUDO IS USED
 *      -- AJAX --
 * ####################
 */
function responsePseudoProfile() {
    if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.readyState === 4 && xhr.status === 200) {
            if (JSON.parse(xhr.responseText).responsePseudo === true) {
                errors['errorPseudo'] = "Le pseudo existe déjà";
                document.querySelector("[data-error=errorPseudo]").textContent = errors['errorPseudo'];
            }
        } else {
            console.log('Il y a eu un problème avec la requête.');
        }
    }
}