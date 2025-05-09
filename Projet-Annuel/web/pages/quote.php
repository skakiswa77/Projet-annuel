<div class="container py-5">
    <div class="row mb-5">
        <div class="col-md-8">
            <h1 class="mb-4">Demande de devis</h1>
            <p class="lead">Choisissez les prestations adaptées à votre entreprise et obtenez un devis personnalisé.</p>
        </div>
        <div class="col-md-4 text-end">
            <img src="assets/images/quote.jpg" alt="Demande de devis" class="img-fluid" style="max-height: 150px;">
        </div>
    </div>

    <form action="actions/quote_process.php" method="post" id="quoteForm">
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Informations de l'entreprise</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="company_name" class="form-label">Nom de l'entreprise</label>
                                <input type="text" class="form-control" id="company_name" name="company_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="company_email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="company_email" name="company_email" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="company_phone" class="form-label">Téléphone</label>
                                <input type="tel" class="form-control" id="company_phone" name="company_phone" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="employees_count" class="form-label">Nombre de salariés</label>
                                <input type="number" class="form-control" id="employees_count" name="employees_count" min="1" required onchange="updateQuote()">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="company_address" class="form-label">Adresse</label>
                                <input type="text" class="form-control" id="company_address" name="company_address">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contact_name" class="form-label">Nom du contact</label>
                                <input type="text" class="form-control" id="contact_name" name="contact_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contact_position" class="form-label">Fonction</label>
                                <input type="text" class="form-control" id="contact_position" name="contact_position">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Choisissez votre formule</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="plan" id="plan_starter" value="starter" checked onchange="updateQuote()">
                            <label class="form-check-label" for="plan_starter">
                                <strong>Formule Starter</strong> - 180€ par salarié par an
                                <ul>
                                    <li>Jusqu'à 30 salariés</li>
                                    <li>2 activités par mois</li>
                                    <li>1 RDV médical par mois</li>
                                    <li>6 questions au chatbot</li>
                                    <li>Accès illimité aux fiches pratiques</li>
                                </ul>
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="plan" id="plan_basic" value="basic" onchange="updateQuote()">
                            <label class="form-check-label" for="plan_basic">
                                <strong>Formule Basic</strong> - 150€ par salarié par an
                                <ul>
                                    <li>Jusqu'à 250 salariés</li>
                                    <li>3 activités par mois</li>
                                    <li>2 RDV médicaux par mois</li>
                                    <li>20 questions au chatbot</li>
                                    <li>Conseils hebdomadaires</li>
                                </ul>
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="plan" id="plan_premium" value="premium" onchange="updateQuote()">
                            <label class="form-check-label" for="plan_premium">
                                <strong>Formule Premium</strong> - 100€ par salarié par an
                                <ul>
                                    <li>À partir de 251 salariés</li>
                                    <li>4 activités par mois</li>
                                    <li>3 RDV médicaux par mois</li>
                                    <li>Questions illimitées au chatbot</li>
                                    <li>Conseils personnalisés</li>
                                </ul>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Options supplémentaires</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="options[]" id="option_events" value="extra_events" onchange="updateQuote()">
                            <label class="form-check-label" for="option_events">
                                <strong>Événements supplémentaires</strong> - 500€ par mois
                                <p class="text-muted">Ajoutez 2 activités supplémentaires par mois</p>
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="options[]" id="option_appointments" value="extra_appointments" onchange="updateQuote()">
                            <label class="form-check-label" for="option_appointments">
                                <strong>Rendez-vous médicaux supplémentaires</strong> - 300€ par mois
                                <p class="text-muted">Ajoutez 2 rendez-vous médicaux supplémentaires par mois</p>

                                <strong>Rendez-vous médicaux supplémentaires</strong> - 300€ par mois
                                <p class="text-muted">Ajoutez 2 rendez-vous médicaux supplémentaires par mois</p>
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="options[]" id="option_workshops" value="workshops" onchange="updateQuote()">
                            <label class="form-check-label" for="option_workshops">
                                <strong>Ateliers bien-être sur site</strong> - 800€ par mois
                                <p class="text-muted">Organisation d'ateliers mensuels de bien-être dans vos locaux</p>
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="options[]" id="option_custom" value="custom" onchange="updateQuote()">
                            <label class="form-check-label" for="option_custom">
                                <strong>Programme personnalisé</strong> - Sur devis
                                <p class="text-muted">Création d'un programme sur mesure adapté aux besoins spécifiques de votre entreprise</p>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Commentaires ou exigences particulières</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <textarea class="form-control" id="comments" name="comments" rows="4" placeholder="Précisez toute exigence particulière ou information supplémentaire qui pourrait nous aider à établir un devis adapté à vos besoins."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Récapitulatif du devis</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h6>Formule sélectionnée:</h6>
                            <p id="summary_plan" class="fw-bold">Formule Starter</p>
                        </div>

                        <div class="mb-4">
                            <h6>Options additionnelles:</h6>
                            <ul id="summary_options" class="list-unstyled">
                                <li class="text-muted fst-italic">Aucune option sélectionnée</li>
                            </ul>
                        </div>

                        <div class="mb-4">
                            <h6>Détails du calcul:</h6>
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <td>Abonnement annuel</td>
                                        <td class="text-end" id="summary_subscription">0 €</td>
                                    </tr>
                                    <tr>
                                        <td>Options (par mois)</td>
                                        <td class="text-end" id="summary_options_cost">0 €</td>
                                    </tr>
                                    <tr>
                                        <td>Options (par an)</td>
                                        <td class="text-end" id="summary_options_annual">0 €</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="table-primary">
                                        <th>Total annuel estimé</th>
                                        <th class="text-end" id="summary_total">0 €</th>
                                    </tr>
                                </tfoot>
                            </table>
                            <p class="text-muted small">* Ce devis est une estimation et pourra être ajusté selon vos besoins exacts.</p>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">Demander un devis détaillé</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function updateQuote() {
        const employeesCount = parseInt(document.getElementById('employees_count').value) || 0;
        let pricePerEmployee = 0;
        let planName = '';
        let maxEmployees = 0;
        
        if (document.getElementById('plan_starter').checked) {
            pricePerEmployee = 180;
            planName = 'Formule Starter';
            maxEmployees = 30;
        } else if (document.getElementById('plan_basic').checked) {
            pricePerEmployee = 150;
            planName = 'Formule Basic';
            maxEmployees = 250;
        } else if (document.getElementById('plan_premium').checked) {
            pricePerEmployee = 100;
            planName = 'Formule Premium';
            maxEmployees = Number.MAX_SAFE_INTEGER;
        }
        
        if (employeesCount > maxEmployees && maxEmployees !== Number.MAX_SAFE_INTEGER) {
            alert(`La formule sélectionnée est limitée à ${maxEmployees} employés. Veuillez choisir une formule supérieure.`);
            if (maxEmployees === 30) {
                document.getElementById('plan_basic').checked = true;
            } else if (maxEmployees === 250) {
                document.getElementById('plan_premium').checked = true;
            }
            updateQuote();
            return;
        }
        

        const subscriptionCost = employeesCount * pricePerEmployee;
        

        let optionsCost = 0;
        let optionsHtml = '';
        
        if (document.getElementById('option_events').checked) {
            optionsCost += 500;
            optionsHtml += '<li>Événements supplémentaires: 500€/mois</li>';
        }
        
        if (document.getElementById('option_appointments').checked) {
            optionsCost += 300;
            optionsHtml += '<li>Rendez-vous médicaux supplémentaires: 300€/mois</li>';
        }
        
        if (document.getElementById('option_workshops').checked) {
            optionsCost += 800;
            optionsHtml += '<li>Ateliers bien-être sur site: 800€/mois</li>';
        }
        
        if (document.getElementById('option_custom').checked) {
            optionsHtml += '<li>Programme personnalisé: Sur devis</li>';
        }
        
        if (optionsHtml === '') {
            optionsHtml = '<li class="text-muted fst-italic">Aucune option sélectionnée</li>';
        }
        

        const optionsAnnualCost = optionsCost * 12;
        

        const totalCost = subscriptionCost + optionsAnnualCost;
        

        document.getElementById('summary_plan').textContent = planName;
        document.getElementById('summary_options').innerHTML = optionsHtml;
        document.getElementById('summary_subscription').textContent = formatCurrency(subscriptionCost);
        document.getElementById('summary_options_cost').textContent = formatCurrency(optionsCost);
        document.getElementById('summary_options_annual').textContent = formatCurrency(optionsAnnualCost);
        document.getElementById('summary_total').textContent = formatCurrency(totalCost);
    }
    
    function formatCurrency(amount) {
        return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(amount);
    }
    

    document.addEventListener('DOMContentLoaded', updateQuote);
</script>