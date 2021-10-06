<!--Sign-in Modal-->
<div class="modal fade" id="modalAuth" aria-hidden="true" aria-labelledby="modalToggleLabelSigin" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-modal">
                <h5 class="modal-title title-auth" id="modalToggleLabelSigin">
                    <i class="fas fa-lock"></i> Connectez-vous :
                </h5>
                <button type="button"
                        class="btn-close bg-close-modal"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>
            </div>
            <form name="form-signin" class="mt-4" id="form-signin" novalidate>
                <div id="info-submit-danger" class="alert alert-danger d-none mx-3" role="alert"></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="email-signin" class="form-label">Email</label>
                        <div class="input-group">
                            <input type="email" class="form-control" id="email-signin"
                                   aria-describedby="emailHelp"
                                   placeholder="mon@mail.com" name="signin-email">
                            <span class="input-group-text justify-content-center w-45">@</span>
                        </div>
                        <div class="info signin-email text-danger"></div>
                    </div>

                    <div class="mb-3">
                        <label for="password-signin" class="form-label">Mot de passe</label>
                        <div class="input-group elt-password">
                            <input type="password" class="form-control show-password" id="password-signin"
                                   name="signin-password">
                            <span class="input-group-text justify-content-center w-45"><i
                                        class="fas fa-eye-slash"></i></span>
                        </div>
                        <div class="info signin-password text-danger"></div>
                    </div>
                    <input type="hidden" name="isAnnounce" value="" id="isAnnounceID" >
                </div>
            </form>
            <div class="modal-footer justify-content-between">
                <div>
                    <button class="btn-link" data-bs-target="#modalToggleSignup" data-bs-toggle="modal"
                            data-bs-dismiss="modal" id="btn-link-modal1">Créez un compte
                    </button>
                </div>
                <div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Quitter</button>
                    <button type="button" class="btn btn-primary" id="btn-signin">Connexion</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!--Sign-up Modal-->
<div class="modal fade" id="modalToggleSignup" aria-hidden="true" aria-labelledby="modalToggleLabelSignup"
     tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-modal">
                <h5 class="modal-title title-auth" id="modalToggleLabelSigin">
                    <i class="fas fa-user-lock"></i> Insrivez-vous :
                </h5>
                <button type="button"
                        class="btn-close bg-close-modal"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>
            </div>
            <form method="post" class="mt-4" name="form-signup" id="form-signup" novalidate>
                <div id="info-submit-success" class="alert alert-success d-none mx-2" role="alert"></div>
                <div id="info-submit-warning" class="alert alert-warning d-none mx-2" role="alert"></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="pseudo-signup" class="form-label">Pseudo <span
                                    class="signup-pseudo-used text-danger"></span></label>
                        <input type="text" class="form-control" id="pseudo-signup" placeholder="Votre pseudo"
                               name="signup-pseudo">
                        <div class="info signup-pseudo text-danger"></div>
                    </div>
                    <div class="mb-3">
                        <label for="phone-signup" class="form-label">Téléphone</label>
                        <input type="text" class="form-control" id="phone-signup" placeholder="+33612345678"
                               name="signup-phone">
                        <div class="info signup-phone text-danger"></div>
                    </div>
                    <div class="mb-3">
                        <label for="email-signup" class="form-label">Email <span
                                    class="signup-email-used text-danger"></span></label>
                        <div class="input-group">
                            <input type="email" class="form-control" id="email-signup"
                                   aria-describedby="emailHelp"
                                   placeholder="mon@mail.com"
                                   name="signup-email">
                            <span class="input-group-text justify-content-center w-45">@</span>
                        </div>
                        <div class="info signup-email text-danger"></div>
                    </div>
                    <div class="mb-3">
                        <label for="email-signup-confirm" class="form-label">Confirmez votre email</label>
                        <div class="input-group">
                            <input type="email" class="form-control" id="email-signup-confirm"
                                   aria-describedby="emailHelp"
                                   placeholder="mon@mail.com"
                                   name="signup-confirm-email"
                            >
                            <span class="input-group-text justify-content-center w-45">@</span>
                        </div>
                        <div class="info signup-confirm-email text-danger"></div>
                    </div>
                    <div class="mb-3">
                        <label for="password-signup" class="form-label">Mot de passe</label>
                        <div class="small">8 caratères minimum et 60 maximum, 1 majuscule, 1 cartère spécial</div>
                        <div class="input-group elt-password">
                            <input type="password"
                                   class="form-control show-password"
                                   id="password-signup"
                                   name="signup-password"
                            >
                            <span class="input-group-text justify-content-center w-45"><i
                                        class="fas fa-eye-slash"></i></span>
                        </div>
                        <div class="info signup-password text-danger"></div>
                    </div>
                    <div class="mb-3">
                        <label for="password-signup-confirm" class="form-label">Confirmez votre mot de passe</label>
                        <div class="input-group elt-password">
                            <input type="password"
                                   class="form-control show-password"
                                   id="password-signup-confirm"
                                   name="signup-password-confirm"
                            >
                            <span class="input-group-text show-password justify-content-center w-45"><i
                                        class="fas fa-eye-slash"></i></span>
                        </div>
                        <div class="info signup-password-confirm text-danger"></div>
                    </div>
                </div>
            </form>
            <div class="modal-footer justify-content-between">
                <div>
                    <button class="btn-link" data-bs-target="#modalAuth" data-bs-toggle="modal"
                            data-bs-dismiss="modal" id="btn-link-modal2">Connexion
                    </button>
                </div>
                <div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Quitter</button>
                    <button type="button" class="btn btn-primary" id="btn-signup">S'inscrire</button>
                    <button class="btn btn-success d-none" data-bs-target="#modalAuth" data-bs-toggle="modal"
                            data-bs-dismiss="modal" id="btn-signup-success">Connexion
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
