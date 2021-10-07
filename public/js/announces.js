/**
 * ##################
 * Form Add Announces
 * ##################
 */
let errors = {
    "errorTitre": "",
    "errorDescription_courte": "",
    "errorDescription_longue": "",
    "errorCategory": "",
    "errorPrix": "",
    "errorPhoto": "",
    "errorAdresse": "",
    "errorVille": "",
    "errorPays": "",
    "errorCp": "",
};

function inputValue(e) {
    if(e.querySelector('input#photo_1')) {
        return e.querySelector('input#nom_principal').value;
    }
    if (e.querySelector('input')) {
        return e.querySelector('input').value;
    }
    if (e.querySelector('textarea')) {
        return e.querySelector('textarea').value;
    }
    if (e.querySelector('select')) {
        return e.querySelector('select').value;
    }
}

document.querySelectorAll(".group-value").forEach((e) => {
    e.addEventListener('focusout', (e) => {
        let dataAttr = e.currentTarget.querySelector('[data-error]').dataset.error;
        validInputAnnounce(dataAttr, inputValue(e.currentTarget), e.currentTarget)
    })
});


function validInputAnnounce(dataAttr, inputValue, currentValue) {
    if (dataAttr === 'errorTitre') {
        inputMinAndMaxChar(10, 100, inputValue, dataAttr, true);
        displayError(dataAttr, currentValue);
    }

    if (dataAttr === 'errorDescription_courte') {
        inputMinAndMaxChar(50, 255, inputValue, dataAttr, true);
        displayError(dataAttr, currentValue);
    }

    if (dataAttr === 'errorDescription_longue') {
        inputMinAndMaxChar(100, 1000, inputValue, dataAttr, true);
        displayError(dataAttr, currentValue);
    }

    if (dataAttr === 'errorCategory') {
        inputMinAndMaxChar(0, 11, inputValue, dataAttr, true);
        displayError(dataAttr, currentValue);
    }

    if (dataAttr === 'errorPrix') {
        inputMinAndMaxChar(0, 11, inputValue, dataAttr, true);
        displayError(dataAttr, currentValue);
    }

    if (dataAttr === 'errorPhoto') {
        inputMinAndMaxChar(0, 50, inputValue, dataAttr, true);
        displayError(dataAttr, currentValue);
    }

    if (dataAttr === 'errorAdresse') {
        inputMinAndMaxChar(1, 50, inputValue, dataAttr, true);
        displayError(dataAttr, currentValue);
    }

    if (dataAttr === 'errorVille') {
        inputMinAndMaxChar(1, 20, inputValue, dataAttr, true);
        displayError(dataAttr, currentValue);
    }

    if (dataAttr === 'errorPays') {
        inputMinAndMaxChar(1, 20, inputValue, dataAttr, true);
        displayError(dataAttr, currentValue);
    }

    if (dataAttr === 'errorCp') {
        inputMinAndMaxChar(1, 11, inputValue, dataAttr, true);
        displayError(dataAttr, currentValue);
    }

}

let submit = document.getElementById("create-announce");
submit.addEventListener('click', () => {

    document.querySelectorAll(".group-value").forEach((elt) => {
        let dataAttr = elt.querySelector("[data-error]").dataset.error;
        validInputAnnounce(dataAttr, inputValue(elt), elt)
    })


    const formAnnounceObj = document.getElementById('formAnnounce').elements;
    const formAnnounce = document.getElementById('formAnnounce');
    // const formData = document.forms['formAnnounce']
    if (
        errors.errorTitre === "" &&
        errors.errorDescription_courte === "" &&
        errors.errorDescription_longue === "" &&
        errors.errorCategory === "" &&
        errors.errorPrix === "" &&
        errors.errorPhoto === "" &&
        errors.errorAdresse === "" &&
        errors.errorVille === "" &&
        errors.errorPays === "" &&
        errors.errorCp === ""
    ) {
        let obj = {};
        for (let i = 0; i < formAnnounceObj.length; i++) {
            let item = formAnnounceObj.item(i);
            obj[item.name] = item.value;
        }
        console.log(obj)
        formAnnounce.submit();
    }
});

/**
 * #########
 * IMG FORM
 * #########
 */
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll(".photo-announce").forEach(elt => {

        let eltDataImg = elt.dataset.img;
        let reader = new FileReader();
        if (elt.querySelector(".input-img-name").value !== '') {
            elt.querySelector(".logo").classList.add("d-none");
            elt.querySelector('label').style.backgroundSize = `cover`;
            elt.querySelector('label').style.backgroundPosition = 'center';
            if(!elt.classList.contains('required-img')) {
                elt.querySelector('button').classList.remove('d-none');
                elt.querySelector('label').classList.add('align-items-end');
                elt.querySelector('label').classList.remove('align-items-center');
            }
        }

        // Input File
        elt.addEventListener('change', event => {
            let file = event.target.files[0];
            let ext = ['image/jpeg', 'image/png'];
            if (ext.includes(file.type)) {
                reader.readAsDataURL(file);
                reader.onload = (progressEvent) => {
                    elt.querySelector(".logo").classList.add("d-none");
                    elt.querySelector('label').style.backgroundSize = `cover`;
                    elt.querySelector('label').style.backgroundPosition = 'center';
                    elt.querySelector('label').style.backgroundImage = `url(${progressEvent.target.result})`;
                    elt.querySelector(`[name=data-${eltDataImg}]`).value = progressEvent.target.result;
                    elt.querySelector(`[name=nom-${eltDataImg}]`).value = file.name;

                    if (!elt.querySelector("[data-img-logo=img-1]")) {
                        elt.querySelector('label').classList.remove('align-items-center');
                        elt.querySelector('label').classList.add('align-items-end');
                        elt.querySelector('button').classList.remove('d-none');
                    }
                }
            }
        })

        // Delete img and value file
        elt.querySelectorAll(".btn-delete-img").forEach(eltDel => {
            eltDel.addEventListener('click', (event) => {
                elt.querySelector('input').value = "";
                elt.querySelector('label').removeAttribute('style');
                elt.querySelector(".logo").classList.remove("d-none");
                elt.querySelector('label').classList.remove('align-items-end');
                elt.querySelector('label').classList.add('align-items-center');
                elt.querySelector('button').classList.add('d-none');
                elt.querySelector(`[name=data-${eltDataImg}]`).value = "";
                elt.querySelector(`[name=nom-${eltDataImg}]`).value = "";
            })
        });
    });


});


/**
 * #################
 * Place Algolia API
 * #################
 */
(function () {
    let placesAutocomplete = places({

        container: document.querySelector('#form-address'),
        templates: {
            value: function (suggestion) {
                return suggestion.name;
            }
        }
    }).configure({
        type: 'address',
        aroundLatLngViaIP: false,
    });
    placesAutocomplete.on('change', function resultSelected(e) {
        document.querySelector('#form-city').value = e.suggestion.city || 'aucune ville';
        document.querySelector('.info-city').textContent = e.suggestion.city || 'aucune ville';
        document.querySelector('#form-country').value = e.suggestion.country || 'aucun pays';
        document.querySelector('.info-pays').textContent = e.suggestion.country || 'aucun pays';
        document.querySelector('#form-zip').value = e.suggestion.postcode || 'aucun code postal';
        document.querySelector('.info-zip').textContent = e.suggestion.postcode || 'aucun code postal';
        document.querySelector('#latlngLat').value = e.suggestion.latlng.lat;
        document.querySelector('#latlngLog').value = e.suggestion.latlng.lng;
    });
})();


/*
 * #################
 * DELETE ANNOUNCE
 * #################
 */

function deleteAnnounce(id) {
    ajaxDelete('deleteAnnounce', id);
}



