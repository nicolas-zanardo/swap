/*
    auth.js
    -------
    File specific to javascript authentication
 */

// CONST
const REQUIRED = "Veuillez renseigner ce champ";
const formSignup = document.getElementById("form-signup");
const formSignin = document.getElementById("form-signin");
let xhr;
// REGEX
const regexEmail = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]{2,}[.][a-zA-Z]{2,4}$/;
const regexPhone = /^((\+)?[0-9]{10,20})$/;
const regexPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)[a-zA-Z0-9_$ \W]{8,60}$/

/**
 * #############
 * SIGN-IN
 * @description Manage Modal form Auth
 * ############
 */
    // ERRORS
let errorsSignin = {
        'email': "",
        'password': ""
    };
document.getElementById("email-signin").addEventListener('focusout', function () {
    inputAuthEmail();
})
document.getElementById("btn-signin").addEventListener('click', function () {
    ajaxSignIn ("false");
});


/**
 * #############
 * SIGN-UP
 * @description Manage Modal form inscription
 * #############
 */

const minCharPseudo = 5;
// ERRORS
let errorsSignup = {
    'pseudo': "",
    'pseudo-used': "",
    'phone': "",
    'email': "",
    'email-used': "",
    'email-confirm': "",
    'password': "",
    "password-confirm": ""
};

/**
 * Manage input "keyup"
 */
const pseudoSignup = document.getElementById("pseudo-signup");
pseudoSignup.addEventListener("keyup", function () {
    if (pseudoSignup.value.length >= minCharPseudo) {
        ajaxPseudoPOST(responsePseudo);
    }
})
const emailSignup = document.getElementById("email-signup");
emailSignup.addEventListener("keyup", function () {
    if (formSignup['signup-email'].value.match(regexEmail)) {
        ajaxEmailPOST(responseEmailAuth);
    }
})


/**
 * Manage input "focusout"
 */
document.getElementById("pseudo-signup").addEventListener("focusout", function () {
    inputPseudo();
});
document.getElementById("phone-signup").addEventListener('focusout', function () {
    inputPhone();
});
document.getElementById("email-signup").addEventListener('focusout', function () {
    inputEmail();
})
document.getElementById("email-signup-confirm").addEventListener('focusout', function () {
    inputEmailConfirm();
})
document.getElementById("password-signup").addEventListener('focusout', function () {
    inputPassword();
})
document.getElementById("password-signup-confirm").addEventListener('focusout', function () {
    inputPasswordConfirm();
})


/**
 * Manage Submit
 */
document.getElementById("btn-signup").addEventListener('click', function () {
    // pseudo
    inputPseudo();
    // phone
    inputPhone();
    // email
    inputEmail();
    // email confirm
    inputEmailConfirm();
    // password
    inputPassword();
    // password confirm
    inputPasswordConfirm();

    // AJAX - Check value is already used
    ajaxPseudoPOST(responsePseudo);
    ajaxEmailPOST(responseEmailAuth);


    if (
        errorsSignup['pseudo'] === "" &&
        errorsSignup['pseudo-used'] === "" &&
        errorsSignup['email'] === "" &&
        errorsSignup['phone'] === "" &&
        errorsSignup['email'] === "" &&
        errorsSignup['email-confirm'] === "" &&
        errorsSignup['email-used'] === "" &&
        errorsSignup['password'] === "" &&
        errorsSignup['password-confirm'] === ""
    ) {
        initXhr();
        const formData = new FormData(formSignup);
        xhr.onreadystatechange = responseSubmit;
        xhr.open('POST', 'include/auth/post-newuser.php', true);
        xhr.send(formData);

        // response behavior depending on the server
        function responseSubmit() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    if (JSON.parse(xhr.responseText).responseSubmit === true) {
                        // Message
                        let info = document.getElementById('info-submit-success');
                        info.classList.remove("d-none");
                        info.innerHTML = "Votre compte a bie été crée";
                        // Hide Form
                        document.querySelector('[name="form-signup"] .modal-body').classList.add("d-none");
                        // Change btn Auth
                        document.getElementById('btn-signup').classList.add("d-none");
                        document.getElementById('btn-signup-success').classList.remove("d-none");

                    } else {
                        let info = document.getElementById('info-submit-warning');
                        info.classList.remove("d-none");
                        info.innerHTML = "Un problème est survenue lors de l'authentification";
                    }

                } else {
                    console.log('Il y a eu un problème avec la requête.');
                }
            }
        }
    }
});

/**
 * function condition all input
 */
function inputPseudo() {
    if (formSignup['signup-pseudo'].value === "") {
        errorsSignup['pseudo'] = REQUIRED;
        document.querySelector(".signup-pseudo").innerHTML = errorsSignup['pseudo'];
    } else if (formSignup['signup-pseudo'].value.length < minCharPseudo) {
        errorsSignup['pseudo'] = `Le pseudo doit avoir ${minCharPseudo} caratères`;
        document.querySelector(".signup-pseudo").innerHTML = errorsSignup['pseudo'];
    } else {
        errorsSignup['pseudo'] = "";
        document.querySelector(".signup-pseudo").innerHTML = "";
    }
}

function inputPhone() {
    if (formSignup['signup-phone'].value === "") {
        errorsSignup['phone'] = REQUIRED;
        document.querySelector(".signup-phone").innerHTML = errorsSignup['phone'];
    } else if (!formSignup['signup-phone'].value.match(regexPhone)) {
        errorsSignup['phone'] = "Veuillez renseigner entre 10 et 20 chiffres";
        document.querySelector(".signup-phone").innerHTML = errorsSignup['phone'];
    } else {
        errorsSignup['phone'] = "";
        document.querySelector(".signup-phone").innerHTML = "";
    }
}

function inputEmail() {
    if (formSignup['signup-email'].value === "") {
        errorsSignup['email'] = REQUIRED;
        document.querySelector(".signup-email").innerHTML = errorsSignup['email'];
    } else if (!formSignup['signup-email'].value.match(regexEmail)) {
        errorsSignup['email'] = "L'email renseigné n'est pas valide";
        document.querySelector(".signup-email").innerHTML = errorsSignup['email'];
    } else {
        errorsSignup['email'] = "";
        document.querySelector(".signup-email").innerHTML = "";
    }
}

function inputEmailConfirm() {
    if (formSignup['signup-confirm-email'].value === "") {
        errorsSignup['email-confirm'] = REQUIRED;
        document.querySelector(".signup-confirm-email").innerHTML = errorsSignup['email-confirm'];
    } else if (formSignup['signup-email'].value !== formSignup['email-signup-confirm'].value) {
        errorsSignup['email-confirm'] = "Les email ne correspondent pas";
        document.querySelector(".signup-confirm-email").innerHTML = errorsSignup['email-confirm'];
    } else {
        errorsSignup['email-confirm'] = "";
        document.querySelector(".signup-confirm-email").innerHTML = "";
    }
}

function inputPassword() {
    if (formSignup['signup-password'].value === "") {
        errorsSignup['password'] = REQUIRED;
        document.querySelector(".signup-password").innerHTML = errorsSignup['password'];
    } else if (!formSignup['signup-password'].value.match(regexPassword)) {
        errorsSignup['password'] = "Le mot de passe n'est pas valide";
        document.querySelector(".signup-password").innerHTML = errorsSignup['password'];
    } else {
        errorsSignup['password'] = "";
        document.querySelector(".signup-password").innerHTML = "";
    }
}

function inputPasswordConfirm() {
    if (formSignup['signup-password-confirm'].value === "") {
        errorsSignup['password-confirm'] = REQUIRED;
        document.querySelector(".signup-password-confirm").innerHTML = errorsSignup['password-confirm'];
    } else if (formSignup['signup-password'].value !== formSignup['signup-password-confirm'].value) {
        errorsSignup['password-confirm'] = "Les mots de passe ne correspondent pas";
        document.querySelector(".signup-password-confirm").innerHTML = errorsSignup['password-confirm'];
    } else {
        errorsSignup['password-confirm'] = "";
        document.querySelector(".signup-password-confirm").innerHTML = "";
    }
}



//###### CHECK PSEUDO IS EXIST
function ajaxPseudoPOST(responsePseudo) {
    initXhr();
    const formData = new FormData(formSignup);
    formData.append('checkPseudo', pseudoSignup.value);
    xhr.onreadystatechange = responsePseudo;
    xhr.open('POST', 'include/auth/post-isused.php', true);
    xhr.send(formData);
}

// response behavior depending on the server
function responsePseudo() {
    if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.readyState === 4 && xhr.status === 200) {
            if (JSON.parse(xhr.responseText).responsePseudo === true) {
                errorsSignup['pseudo-used'] = "Le pseudo existe déjà";
                document.querySelector(".signup-pseudo-used").innerHTML = errorsSignup['pseudo-used'];
            } else {
                errorsSignup['pseudo-used'] = "";
                document.querySelector(".signup-pseudo-used").innerHTML = errorsSignup['pseudo-used'];
            }
        } else {
            console.log('Il y a eu un problème avec la requête.');
        }
    }
}
//###### END


//###### CHECK EMAIL IS EXIST
function ajaxEmailPOST(responseEmail) {
    initXhr();
    const formData = new FormData(formSignup);
    formData.append('checkEmail', emailSignup.value);
    xhr.onreadystatechange = responseEmail;
    xhr.open('POST', 'include/auth/post-isused.php', true);
    xhr.send(formData);
}

function responseEmailAuth() {
    if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.readyState === 4 && xhr.status === 200) {
            if (JSON.parse(xhr.responseText).responseEmail === true) {
                errorsSignup['email-used'] = "L'email existe déjà";
                document.querySelector(".signup-email-used").innerHTML = errorsSignup['email-used'];

            } else {
                errorsSignup['email-used'] = "";
                document.querySelector(".signup-email-used").innerHTML = errorsSignup['email-used'];
            }
        } else {
            console.log('Il y a eu un problème avec la requête.');
        }
    }
}
//###### END


/**
 * ######
 * Logout
 */
const logout = document.getElementById("logout");
if (logout) {
    logout.addEventListener('click', function () {
        initXhr();
        xhr.onreadystatechange = responseLogout;
        xhr.open('GET', `include/auth/post-logout.php?action=logout`, true);
        xhr.send(null);

        // response behavior depending on the server
        function responseLogout() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.location.reload();
                } else {
                    console.log('Il y a eu un problème avec la requête.');
                }
            }
        }
    })
}