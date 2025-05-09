<?php

$formData = $_SESSION['register_form_data'] ?? [];
unset($_SESSION['register_form_data']);
?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Inscription</h4>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs mb-4" id="registerTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="company-tab" data-bs-toggle="tab" data-bs-target="#company"
                            type="button" role="tab" aria-controls="company" aria-selected="true">Entreprise</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="provider-tab" data-bs-toggle="tab" data-bs-target="#provider"
                            type="button" role="tab" aria-controls="provider" aria-selected="false">Prestataire</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="admin-tab" data-bs-toggle="tab" data-bs-target="#admin"
                            type="button" role="tab" aria-controls="admin" aria-selected="false">Administrateur</button>
                    </li>
                </ul>

                <div class="tab-content" id="registerTabsContent">

                    <div class="tab-pane fade show active" id="company" role="tabpanel" aria-labelledby="company-tab">
                        <form action="actions/register_process.php" method="post" id="companyForm">
                            <input type="hidden" name="role" value="company_admin">

                            <div class="alert alert-info">
                                <p><strong>Remarque:</strong> Cette inscription est destinée aux entreprises qui
                                    souhaitent utiliser les services de Business Care.</p>
                            </div>

                            <h5 class="mb-3">Informations de l'entreprise</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="company_name" class="form-label">Nom de l'entreprise</label>
                                    <input type="text" class="form-control" id="company_name" name="company_name"
                                        value="<?= $formData['company_name'] ?? '' ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="company_email" class="form-label">Email de l'entreprise</label>
                                    <input type="email" class="form-control" id="company_email" name="company_email"
                                        value="<?= $formData['company_email'] ?? '' ?>" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="company_phone" class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control" id="company_phone" name="company_phone"
                                        value="<?= $formData['company_phone'] ?? '' ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="company_address" class="form-label">Adresse</label>
                                    <input type="text" class="form-control" id="company_address" name="company_address"
                                        value="<?= $formData['company_address'] ?? '' ?>">
                                </div>
                            </div>

                            <hr>
                            <h5 class="mb-3">Informations de l'administrateur</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">Prénom</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                        value="<?= $formData['first_name'] ?? '' ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Nom</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name"
                                        value="<?= $formData['last_name'] ?? '' ?>" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="admin_email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="admin_email" name="admin_email"
                                        value="<?= $formData['admin_email'] ?? '' ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="admin_phone" class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control" id="admin_phone" name="admin_phone"
                                        value="<?= $formData['admin_phone'] ?? '' ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="gender" class="form-label">Genre</label>
                                    <select class="form-select" id="gender" name="gender" required>
                                        <option value="" selected disabled>Choisir</option>
                                        <option value="M" <?= isset($formData['gender']) && $formData['gender'] === 'M' ? 'selected' : '' ?>>Homme</option>
                                        <option value="F" <?= isset($formData['gender']) && $formData['gender'] === 'F' ? 'selected' : '' ?>>Femme</option>
                                        <option value="O" <?= isset($formData['gender']) && $formData['gender'] === 'O' ? 'selected' : '' ?>>Autre</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="birthdate" class="form-label">Date de naissance</label>
                                    <input type="date" class="form-control" id="birthdate" name="birthdate"
                                        value="<?= $formData['birthdate'] ?? '' ?>" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="address" class="form-label">Adresse personnelle</label>
                                    <input type="text" class="form-control" id="address" name="address"
                                        value="<?= $formData['address'] ?? '' ?>">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="postal_code" class="form-label">Code postal</label>
                                    <input type="text" class="form-control" id="postal_code" name="postal_code"
                                        value="<?= $formData['postal_code'] ?? '' ?>">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="city" class="form-label">Ville</label>
                                    <input type="text" class="form-control" id="city" name="city"
                                        value="<?= $formData['city'] ?? '' ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Mot de passe</label>
                                    <input type="password" class="form-control" id="password" name="password" required
                                        minlength="8">
                                    <div class="form-text">Le mot de passe doit contenir au moins 8 caractères.</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                                    <input type="password" class="form-control" id="confirm_password"
                                        name="confirm_password" required>
                                </div>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">J'accepte les <a href="index.php?page=terms"
                                        target="_blank">conditions d'utilisation</a> et la <a
                                        href="index.php?page=privacy" target="_blank">politique de
                                        confidentialité</a></label>
                            </div>
                            <button type="submit" class="btn btn-primary">S'inscrire</button>
                        </form>
                    </div>


                    <div class="tab-pane fade" id="provider" role="tabpanel" aria-labelledby="provider-tab">
                        <form action="actions/register_process.php" method="post" id="providerForm">
                            <input type="hidden" name="role" value="provider">

                            <div class="alert alert-info">
                                <p><strong>Remarque:</strong> Cette inscription est destinée aux prestataires qui
                                    souhaitent proposer leurs services via Business Care.</p>
                            </div>

                            <h5 class="mb-3">Informations personnelles</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="provider_first_name" class="form-label">Prénom</label>
                                    <input type="text" class="form-control" id="provider_first_name" name="first_name"
                                        value="<?= $formData['first_name'] ?? '' ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="provider_last_name" class="form-label">Nom</label>
                                    <input type="text" class="form-control" id="provider_last_name" name="last_name"
                                        value="<?= $formData['last_name'] ?? '' ?>" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="provider_email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="provider_email" name="admin_email"
                                        value="<?= $formData['admin_email'] ?? '' ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="provider_phone" class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control" id="provider_phone" name="admin_phone"
                                        value="<?= $formData['admin_phone'] ?? '' ?>" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="provider_gender" class="form-label">Genre</label>
                                    <select class="form-select" id="provider_gender" name="gender" required>
                                        <option value="" selected disabled>Choisir</option>
                                        <option value="M" <?= isset($formData['gender']) && $formData['gender'] === 'M' ? 'selected' : '' ?>>Homme</option>
                                        <option value="F" <?= isset($formData['gender']) && $formData['gender'] === 'F' ? 'selected' : '' ?>>Femme</option>
                                        <option value="O" <?= isset($formData['gender']) && $formData['gender'] === 'O' ? 'selected' : '' ?>>Autre</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="provider_birthdate" class="form-label">Date de naissance</label>
                                    <input type="date" class="form-control" id="provider_birthdate" name="birthdate"
                                        value="<?= $formData['birthdate'] ?? '' ?>" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="provider_address" class="form-label">Adresse</label>
                                    <input type="text" class="form-control" id="provider_address" name="address"
                                        value="<?= $formData['address'] ?? '' ?>" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="provider_postal_code" class="form-label">Code postal</label>
                                    <input type="text" class="form-control" id="provider_postal_code" name="postal_code"
                                        value="<?= $formData['postal_code'] ?? '' ?>" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="provider_city" class="form-label">Ville</label>
                                    <input type="text" class="form-control" id="provider_city" name="city"
                                        value="<?= $formData['city'] ?? '' ?>" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="provider_password" class="form-label">Mot de passe</label>
                                    <input type="password" class="form-control" id="provider_password" name="password"
                                        required minlength="8">
                                    <div class="form-text">Le mot de passe doit contenir au moins 8 caractères.</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="provider_confirm_password" class="form-label">Confirmer le mot de
                                        passe</label>
                                    <input type="password" class="form-control" id="provider_confirm_password"
                                        name="confirm_password" required>
                                </div>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="provider_terms" name="terms"
                                    required>
                                <label class="form-check-label" for="provider_terms">J'accepte les <a
                                        href="index.php?page=terms" target="_blank">conditions d'utilisation</a> et la
                                    <a href="index.php?page=privacy" target="_blank">politique de
                                        confidentialité</a></label>
                            </div>
                            <button type="submit" class="btn btn-primary">S'inscrire</button>
                        </form>
                    </div>


                    <div class="tab-pane fade" id="admin" role="tabpanel" aria-labelledby="admin-tab">
                        <form action="actions/register_process.php" method="post" id="adminForm">
                            <input type="hidden" name="role" value="admin">

                            <div class="alert alert-warning">
                                <p><strong>Attention:</strong> Cette inscription est réservée aux administrateurs de
                                    Business Care. Votre demande devra être approuvée par un administrateur existant
                                    avant de pouvoir vous connecter.</p>
                            </div>

                            <h5 class="mb-3">Informations personnelles</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="admin_first_name" class="form-label">Prénom</label>
                                    <input type="text" class="form-control" id="admin_first_name" name="first_name"
                                        value="<?= $formData['first_name'] ?? '' ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="admin_last_name" class="form-label">Nom</label>
                                    <input type="text" class="form-control" id="admin_last_name" name="last_name"
                                        value="<?= $formData['last_name'] ?? '' ?>" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="admin_admin_email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="admin_admin_email" name="admin_email"
                                        value="<?= $formData['admin_email'] ?? '' ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="admin_admin_phone" class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control" id="admin_admin_phone" name="admin_phone"
                                        value="<?= $formData['admin_phone'] ?? '' ?>" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="admin_gender" class="form-label">Genre</label>
                                    <select class="form-select" id="admin_gender" name="gender" required>
                                        <option value="" selected disabled>Choisir</option>
                                        <option value="M" <?= isset($formData['gender']) && $formData['gender'] === 'M' ? 'selected' : '' ?>>Homme</option>
                                        <option value="F" <?= isset($formData['gender']) && $formData['gender'] === 'F' ? 'selected' : '' ?>>Femme</option>
                                        <option value="O" <?= isset($formData['gender']) && $formData['gender'] === 'O' ? 'selected' : '' ?>>Autre</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="admin_birthdate" class="form-label">Date de naissance</label>
                                    <input type="date" class="form-control" id="admin_birthdate" name="birthdate"
                                        value="<?= $formData['birthdate'] ?? '' ?>" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="admin_address" class="form-label">Adresse</label>
                                    <input type="text" class="form-control" id="admin_address" name="address"
                                        value="<?= $formData['address'] ?? '' ?>" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="admin_postal_code" class="form-label">Code postal</label>
                                    <input type="text" class="form-control" id="admin_postal_code" name="postal_code"
                                        value="<?= $formData['postal_code'] ?? '' ?>" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="admin_city" class="form-label">Ville</label>
                                    <input type="text" class="form-control" id="admin_city" name="city"
                                        value="<?= $formData['city'] ?? '' ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="request_admin_code"
                                            name="request_admin_code" checked required>
                                        <label class="form-check-label" for="request_admin_code">
                                            Je confirme demander un accès administrateur et comprends qu'un code de
                                            vérification me sera envoyé par email si ma demande est approuvée par
                                            l'équipe Business Care.
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="admin_password" class="form-label">Mot de passe</label>
                                    <input type="password" class="form-control" id="admin_password" name="password"
                                        required minlength="8">
                                    <div class="form-text">Le mot de passe doit contenir au moins 8 caractères.</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="admin_confirm_password" class="form-label">Confirmer le mot de
                                        passe</label>
                                    <input type="password" class="form-control" id="admin_confirm_password"
                                        name="confirm_password" required>
                                </div>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="admin_terms" name="terms" required>
                                <label class="form-check-label" for="admin_terms">J'accepte les <a href="index.php?page=terms" target="_blank">conditions d'utilisation</a> et la<a href="index.php?page=privacy" target="_blank">politique de confidentialité</a></label>
                            </div>
                            <button type="submit" class="btn btn-primary">S'inscrire</button>
                        </form>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const forms = document.querySelectorAll('form');

                            forms.forEach(form => {
                                form.addEventListener('submit', function (event) {
                                    const passwordId = form.id === 'companyForm' ? 'password' :
                                        form.id === 'providerForm' ? 'provider_password' : 'admin_password';
                                    const confirmPasswordId = form.id === 'companyForm' ? 'confirm_password' :
                                        form.id === 'providerForm' ? 'provider_confirm_password' : 'admin_confirm_password';

                                    const password = document.getElementById(passwordId).value;
                                    const confirmPassword = document.getElementById(confirmPasswordId).value;

                                    if (password !== confirmPassword) {
                                        event.preventDefault();
                                        alert('Les mots de passe ne correspondent pas.');
                                    }
                                });
                            });
                        });
                    </script>