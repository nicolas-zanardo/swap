let pathPhoto1;
let pathPhoto2;
let pathPhoto3;
let pathPhoto4;
let pathPhoto5;
let announceID;


const announce = document.querySelector("#announce");
const allAnnounces = document.querySelector("#all-announces");

document.querySelector("#close-announce").addEventListener("click", (e) => {
  e.stopPropagation();
  announce.classList.toggle("d-none");
  allAnnounces.classList.toggle("d-none");
});

document.getElementById("displayInfoContact").addEventListener("click", () => {
  document.getElementById("blocInfoContact").classList.toggle("d-none");
});

function getAnnounce(id) {
  window.location.href = `?announce=${id}`;
}


let strLocation = window.location.href;
let splitHref = strLocation.split("=");

let xhr;
if (window.XMLHttpRequest) {
  xhr = new XMLHttpRequest();
} else if (window.ActiveXObject) {
  xhr = new ActiveXObject("Microsoft.XMLHTTP");
} else {
  console.log("Votre navigateur n'est pas compatible avec AJAX...");
}

const formData = new FormData();
formData.append('announceID', splitHref[1]);
xhr.onreadystatechange = response;
xhr.open('POST', 'include/actions/getDataAnnounce.php', true);
xhr.send(formData);

function response() {

    if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.readyState === 4 && xhr.status === 200) { 
          if(xhr.responseText) {
            initGETUser(JSON.parse(xhr.responseText));
          }
        } else {
            console.log('Il y a eu un problème avec la requête.');
        }
    }
  
}


function initGETUser(user) {
 
  pathPhoto1 = `public/images/annonces/${user.id_membre}/${user.id_annonce}-1-${user.photo1}`;
  pathPhoto2 = `public/images/annonces/${user.id_membre}/${user.id_annonce}-2-${user.photo2}`;
  pathPhoto3 = `public/images/annonces/${user.id_membre}/${user.id_annonce}-3-${user.photo3}`;
  pathPhoto4 = `public/images/annonces/${user.id_membre}/${user.id_annonce}-4-${user.photo4}`;
  pathPhoto5 = `public/images/annonces/${user.id_membre}/${user.id_annonce}-5-${user.photo5}`;
}


/**
 * Display img Announce
 */
document.querySelectorAll(".ajaxImg").forEach((elt) => {
  elt.addEventListener("click", () => {
    switch (elt.id) {
      case "photo1":
        document
          .querySelector("#img-principal>img")
          .setAttribute("src", pathPhoto1);
        break;
      case "photo2":
        document
          .querySelector("#img-principal>img")
          .setAttribute("src", pathPhoto2);
        break;
      case "photo3":
        document
          .querySelector("#img-principal>img")
          .setAttribute("src", pathPhoto3);
        break;
      case "photo4":
        document
          .querySelector("#img-principal>img")
          .setAttribute("src", pathPhoto4);
        break;
      case "photo5":
        document
          .querySelector("#img-principal>img")
          .setAttribute("src", pathPhoto5);
        break;
    }
  });
});
//Display Map On GET['announce']
window.addEventListener("load", () => {
  const mapGET = document.getElementById("map");
  if (mapGET) {
    const latitudeGPS = document.getElementById("latitude-gps").textContent;
    const longitudeGPS = document.getElementById("longitude-gps").textContent;
    displayMap(latitudeGPS, longitudeGPS, "map");
  }
});

/**
 * ol https://openlayers.org/en/latest/apidoc/module-ol_Map-Map.html
 * @param lat latitude
 * @param lng longitude
 * @param target idMap
 */
function displayMap(lat, lng, target) {
  let map = new ol.Map({
    target: target,
    layers: [
      new ol.layer.Tile({
        source: new ol.source.OSM(),
      }),
    ],
    view: new ol.View({
      center: ol.proj.fromLonLat([lat, lng]),
      zoom: 13,
    }),
  });
}

/**
 * POST Question
 */
document.getElementById("valid-question").addEventListener("click", () => {
  const formQuestion = document.getElementById("form-question");

  if (document.querySelector('input[name="question"]') !== "") {
    const formDataQuestion = new FormData(formQuestion);
    formDataQuestion.append("announceID", "false");
    xhr.onreadystatechange = responseSubmit;
    xhr.open("POST", "include/actions/question.php", true);
    xhr.send(formDataQuestion);
  }

  function responseSubmit() {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.readyState === 4 && xhr.status === 200) {
        if (xhr.responseText === "true") {
          document.getElementById("form-question").classList.add("d-none");
          document.getElementById("message-send-ok").classList.remove("d-none");
          window.location.reload();
        }
      } else {
        console.log("Il y a eu un problème avec la requête.");
      }
    }
  }
});

/**
 * NOTE
 */
document.getElementById("btnNote").addEventListener("click", () => {
  const formNote = document.getElementById("formNote");

  const formDataNote = new FormData(formNote);
  formDataNote.append("validate", "false");
  xhr.onreadystatechange = responseNote;
  xhr.open("POST", "include/actions/notes.php", true);
  xhr.send(formDataNote);

  function responseNote() {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.readyState === 4 && xhr.status === 200) {
        if (xhr.responseText) {
          window.location.reload();
        }
      } else {
        console.log("Il y a eu un problème avec la requête.");
      }
    }
  }
});
