/*
    script.js
    -------
    File specific to javascript generic
 */

/*************************************************
 * ###############################################
 * Event
 * -----
 * @description Display or not display password in form | Change input type="text" or type="password"
 *
 *
 */
// Display Password
document.querySelectorAll(".elt-password").forEach(function (e) {

    let logoPassword = e.querySelector("i");
    let inputPassword = e.querySelector(".show-password");

    logoPassword.addEventListener('click', function (elt) {
        if (logoPassword.classList.contains("fa-eye-slash")) {
            logoPassword.classList.remove("fa-eye-slash");
            logoPassword.classList.add("fa-eye");
            inputPassword.removeAttribute('type');
            inputPassword.setAttribute('type', 'text');
        } else {
            logoPassword.classList.remove("fa-eye");
            logoPassword.classList.add("fa-eye-slash");
            inputPassword.removeAttribute('type');
            inputPassword.setAttribute('type', 'password');
        }
    })
})


/*************************************************
 * ###############################################
 * Management Error
 * -----------------
 * @description Display error for form
 *
 */

/**
 * inputMinAndMaxChar
 * ------------------
 * @param min (int)
 * @param max (int)
 * @param value (int)
 * @param eltError (data-error)
 * @param required (bool)
 * @param isValid (bool)
 */
function inputMinAndMaxChar(min, max, value, eltError, required, isValid = true) {
    let minChar = parseInt(min);
    let maxChar = parseInt(max);

    if (value.length >= maxChar || value.length <= minChar) {
        errors[eltError] = `Le champ soit comprendre entre ${minChar} et ${maxChar}`;
    }
    if (value.length >= minChar && value.length <= maxChar && isValid) {
        errors[eltError] = "";
    }
    if (required && value === "") {
        errors[eltError] = `Le champ est requis`;
    }
}

/**
 * displayError
 * ------------
 * @param dataErrors data-error
 * @param elt e.currentTarget
 */
function displayError(dataErrors, elt) {
    for (const [key, value] of Object.entries(errors)) {
        if (key === dataErrors) {
            elt.querySelector('.info').textContent = value;
        }
    }
}

function inputAuthEmail() {
    if (formSignin['signin-email'].value === "") {
        errorsSignin['email'] = REQUIRED;
        document.querySelector(".signin-email").innerHTML = errorsSignin['email'];
    } else if (!formSignin['signin-email'].value.match(regexEmail)) {
        errorsSignin['email'] = "L'email renseigné n'est pas valide";
        document.querySelector(".signin-email").innerHTML = errorsSignin['email'];
    } else {
        errorsSignin['email'] = "";
        document.querySelector(".signin-email").innerHTML = "";
    }
}

function initXhr() {
    if (window.XMLHttpRequest) {
        return xhr = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        return xhr = new ActiveXObject("Microsoft.XMLHTTP");
    } else {
        console.log("Votre navigateur n'est pas compatible avec AJAX...");
    }
}

/**
 * AJAX
 * AUTH - SIGN IN
 */
function ajaxSignIn (isAnnounceID) {

    document.getElementById("isAnnounceID").value = isAnnounceID
    // email
    inputAuthEmail();

    // password
    if (formSignin['signin-password'].value === "") {
        errorsSignin['password'] = REQUIRED;
        document.querySelector(".signin-password").innerHTML = errorsSignin['password'];
    } else {
        errorsSignin['password'] = "";
        document.querySelector(".signin-password").innerHTML = "";
    }

    if (
        errorsSignin['email'] === "" &&
        errorsSignin['password'] === ""
    ) {
        initXhr();
        const formData = new FormData(formSignin);
        xhr.onreadystatechange = responseAuth;
        xhr.open('POST', 'include/auth/post-auth.php', true);
        xhr.send(formData);

        // response behavior depending on the server
        const errorInfo = document.getElementById("info-submit-danger");

        function responseAuth() {

            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log(xhr.responseText)
                    if (JSON.parse(xhr.responseText).responseAuth === true) {
                        if(JSON.parse(xhr.responseText).isAnnounce === false) {
                            document.location.reload();
                        } else if(JSON.parse(xhr.responseText).isAnnounce === true) {
                            window.location.href='/?announce=116'
                        }
                    } else if (JSON.parse(xhr.responseText).responseAuth === false) {
                        errorInfo.classList.remove("d-none");
                        errorInfo.textContent = "Le mot de passe ou l'adresse email n'est pas valide";
                    } else {
                        errorInfo.classList.remove("d-none");
                        errorInfo.textContent = "Erreur server contacter l'admin du site";
                    }
                } else {
                    console.log('Il y a eu un problème avec la requête.');
                }
            }
        }
    }
}

/**
 * AJAX
 * DELETE BY ID
 */
function ajaxDelete(appendKey, eltSelect) {
    initXhr();
    const formData = new FormData();
    formData.append(appendKey, eltSelect);
    xhr.onreadystatechange = response;
    xhr.open('POST', 'include/actions/delete.php', true);
    xhr.send(formData);

    // response behavior depending on the server
    function response() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // console.log(xhr.responseText)
                document.location.reload();
            } else {
                console.log(xhr.responseText)
                console.log('Il y a eu un problème avec la requête.');
            }
        }
    }
}