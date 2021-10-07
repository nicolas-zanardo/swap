</div>
<?php
require_once __DIR__ . "/component/modal-auth.php";

?>
<footer class="py-4 mt-5 bg-light mt-auto">
    <div class="container-fluid px-4">
        <div class="d-flex align-items-center justify-content-between small flex-wrap-reverse">
            <div class="text-muted">IFOCOP - TP &copy; SWAP 2021</div>
            <div class="text-dark d-flex">
                <a href="https://github.com/nicolas-zanardo/swap" target="'_blank"  class="text-dark align-items-center d-flex"><i class="fab fa-github fa-2x me-1"></i> check my git repository</a>
                <a href="public/files/swap-master.zip"  class="btn btn-secondary mx-3">Télécharger le code .zip</a>
            </div>
        </div>
    </div>
</footer>
<script>
    var sessionID = '<?= $_SESSION['user']['id_membre'] ?? 'null' ?>';
    var sessionEmail = '<?= $_SESSION['user']['email'] ?? 'null' ?>';
    var sessionPseudo = '<?= $_SESSION['user']['pseudo'] ?? 'null' ?>';
</script>


<!--bootstrap-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4"
        crossorigin="anonymous">
</script>

<!--sweetalert2-->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!--Data-table-->
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>


<!--Algolia-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/places.js/1.19.0/places.min.js"
        integrity="sha512-6TSW8PEc+JET/TBENLNC9gKJuEkyhQZ8SEidKis9m0DS+3J3axHbbwYoLeVg8lAJ/1CglDy847pzTCZ0rqmrVg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- JS elements -->
<?php if (isset($eltJS)) : ?>
    <?php foreach ($eltJS as $value) : ?>
        <script src="/public/js/<?= $value ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>
</body>
</html>
