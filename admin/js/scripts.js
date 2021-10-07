/*!
    * Start Bootstrap - SB Admin v7.0.1 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2021 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
    */
//
// Scripts
// 

window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

});

/**
 * Edit Announce Category
 */
function editCategory(id) {
    document.querySelector(`[data-annonceCategory=title-${id}]`).removeAttribute('disabled');
    document.querySelector(`[data-annonceCategory=keyword-${id}]`).removeAttribute('disabled');
    document.querySelector(`[data-annonceCategory=edit-${id}]`).classList.add('d-none');
    document.querySelector(`[data-annonceCategory=valid-${id}]`).classList.remove('d-none');
}
function validEditCategory(id) {
    let inputTitle = document.querySelector(`[data-annonceCategory=title-${id}]`);
    let inputKeyWord = document.querySelector(`[data-annonceCategory=keyword-${id}]`);
    document.querySelector(`[data-annonceCategory=edit-${id}]`).classList.remove('d-none');
    document.querySelector(`[data-annonceCategory=valid-${id}]`).classList.add('d-none');

    inputTitle.setAttribute('disabled', '');
    inputKeyWord.setAttribute('disabled', '');


    if (window.XMLHttpRequest) {
        xhr = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
    } else {
        console.log("Votre navigateur n'est pas compatible avec AJAX...");
    }

    const formData = new FormData();
    formData.append('inputTitle', inputTitle.value);
    formData.append('inputKeyWord', inputKeyWord.value);
    formData.append('id_categorie', id)
    xhr.open('POST', 'request/edit.php', true);
    xhr.send(formData);

}


/**
 * DELETE BY ID AJAX
 */
function initXhr() {
    if (window.XMLHttpRequest) {
        return xhr = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        return xhr = new ActiveXObject("Microsoft.XMLHTTP");
    } else {
        console.log("Votre navigateur n'est pas compatible avec AJAX...");
    }
}

function ajaxDelete(appendKey, eltSelect) {
    initXhr();

    const formData = new FormData();
    formData.append(appendKey, eltSelect);
    xhr.onreadystatechange = response;
    xhr.open('POST', 'request/delete.php', true);
    xhr.send(formData);

    // response behavior depending on the server
    function response() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // console.log(xhr.responseText)
                document.location.reload();
            } else {
                console.log('Il y a eu un problème avec la requête.');
            }
        }
    }
}

// Delete USER
function delUser(id) {
    ajaxDelete('deleteUserById', id);
}

// Delete Category
function delCategory(id) {
    ajaxDelete("deleteCategoryById", id);
}


const logout = document.getElementById("logout");
if (logout) {
    logout.addEventListener('click', function () {
        initXhr();
        xhr.onreadystatechange = responseLogout;
        xhr.open('GET', `/../include/auth/post-logout.php?action=logout`, true);
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
