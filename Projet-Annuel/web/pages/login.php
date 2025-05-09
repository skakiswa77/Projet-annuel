<?php

$formData = $_SESSION['login_form_data'] ?? [];
unset($_SESSION['login_form_data']);
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Connexion</h4>
            </div>
            <div class="card-body">
                <form action="actions/login_process.php" method="post" id="loginForm">
                    <div class="mb-3">
                        <label for="role" class="form-label">Je suis</label>
                        <select class="form-select" id="role" name="role" required onchange="toggleAdminCode()">
                            <option value="" selected disabled>Choisir un rôle</option>
                            <option value="employee" <?= isset($formData['role']) && $formData['role'] === 'employee' ? 'selected' : '' ?>>Salarié</option>
                            <option value="company_admin" <?= isset($formData['role']) && $formData['role'] === 'company_admin' ? 'selected' : '' ?>>Entreprise</option>
                            <option value="provider" <?= isset($formData['role']) && $formData['role'] === 'provider' ? 'selected' : '' ?>>Prestataire</option>
                            <option value="admin" <?= isset($formData['role']) && $formData['role'] === 'admin' ? 'selected' : '' ?>>Administrateur</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?= $formData['email'] ?? '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3" id="adminCodeGroup" style="display: none;">
                        <label for="admin_code" class="form-label">Code administrateur</label>
                        <input type="text" class="form-control" id="admin_code" name="admin_code">
                        <div class="form-text">Ce code vous a été envoyé par email.</div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Se souvenir de moi</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                </form>
                <div class="mt-3">
                    <a href="index.php?page=forgot_password">Mot de passe oublié?</a>
                </div>
                <hr>
                <div class="text-center">
                    <p>Pas encore de compte? <a href="index.php?page=register">S'inscrire</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleAdminCode() {
        const roleSelect = document.getElementById('role');
        const adminCodeGroup = document.getElementById('adminCodeGroup');
        const adminCodeInput = document.getElementById('admin_code');

        if (roleSelect.value === 'admin') {
            adminCodeGroup.style.display = 'block';
            adminCodeInput.required = true;
        } else {
            adminCodeGroup.style.display = 'none';
            adminCodeInput.required = false;
        }
    }

    document.addEventListener('DOMContentLoaded', toggleAdminCode);
</script>