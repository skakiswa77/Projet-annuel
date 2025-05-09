<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Mot de passe oublié</h4>
            </div>
            <div class="card-body">
                <p>Veuillez entrer votre adresse email. Nous vous enverrons un lien pour réinitialiser votre mot de passe.</p>
                <form action="actions/forgot_password_process.php" method="post">
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= $_SESSION['forgot_password_form_data']['email'] ?? '' ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Réinitialiser mon mot de passe</button>
                </form>
                <div class="mt-3">
                    <a href="index.php?page=login">Retour à la connexion</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php unset($_SESSION['forgot_password_form_data']); ?>