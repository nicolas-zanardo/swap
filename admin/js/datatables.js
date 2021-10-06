window.addEventListener('DOMContentLoaded', event => {
    // Simple-DataTables
    // https://github.com/fiduswriter/Simple-DataTables/wiki

    const datatablesSimple = document.getElementById('datatablesSimple');
    if (datatablesSimple) {
        new simpleDatatables.DataTable(datatablesSimple);
    }

    const datatablesUsers = document.getElementById('datatablesUsers');
    if (datatablesUsers) {
        new simpleDatatables.DataTable(datatablesUsers);
    }
});
