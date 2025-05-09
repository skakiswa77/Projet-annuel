<?php
$token = $_GET['token'] ?? '';
if (empty($token)) {
    setAlert('Token manquant. Veuillez utiliser le lien fourni dans l\'email de réinitialisation.', 'danger');
    redirect('index.php?page=login');
}


$userId = validateResetToken($token);
if (!$userId) {
    setAlert('Token invalide ou expiré. Veuillez demander une nouvelle réinitialisation.', 'danger');
    redirect('index.php?page=forgot_password');
}
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Réinitialisation du mot de passe</h4>
            </div>
            <div class="card-body">
                <form action="actions/reset_password_process.php" method="post">
                    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                    <div class="mb-3">
                        <label for="password" class="form-label">Nouveau mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" required minlength="8">
                        <div class="form-text">Le mot de passe doit contenir au moins 8 caractères.</div>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Réinitialiser mon mot de passe</button>
                </form>
            </div>
        </div>
    </div>
</div>