/*
    search.js
    -------
    File specific to javascript module search
 */

const barSearch = document.getElementById("bar-search");
if (barSearch) {
    const inputRange = document.querySelector("#customRange");
    inputRange.addEventListener('input', function() {
        document.querySelector('#labelCustomRange').innerHTML = "Prix maximum: "+ inputRange.value + " €";
    })

}

