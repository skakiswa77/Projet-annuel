<div class="container py-5">
    <div class="row mb-5">
        <div class="col-lg-8">
            <h1 class="mb-4">Contactez-nous</h1>
            <p class="lead">Vous avez des questions sur nos services ou vous souhaitez obtenir un devis personnalisé ? N'hésitez pas à nous contacter.</p>
        </div>
        <div class="col-lg-4 text-end">
            <img src="assets/images/contact.jpg" alt="Contact" class="img-fluid" style="max-height: 150px;">
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="mb-4">Formulaire de contact</h2>
                    <form action="actions/contact_process.php" method="post" id="contactForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nom et prénom</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Téléphone</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="company" class="form-label">Entreprise</label>
                                <input type="text" class="form-control" id="company" name="company">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Sujet</label>
                            <select class="form-select" id="subject" name="subject" required>
                                <option value="" selected disabled>Choisir un sujet</option>
                                <option value="devis">Demande de devis</option>
                                <option value="info">Demande d'informations</option>
                                <option value="partnership">Proposition de partenariat</option>
                                <option value="recruitment">Recrutement</option>
                                <option value="support">Support technique</option>
                                <option value="other">Autre</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="privacy" name="privacy" required>
                            <label class="form-check-label" for="privacy">J'accepte que mes données soient utilisées pour traiter ma demande conformément à la <a href="index.php?page=privacy" target="_blank">politique de confidentialité</a>.</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Envoyer</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="card-title mb-4">Nos coordonnées</h3>
                    <div class="d-flex mb-3">
                        <div class="bg-primary text-white rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-map-marker-alt fa-lg"></i>
                        </div>
                        <div>
                            <h5>Adresse</h5>
                            <p class="mb-0">110, rue de Rivoli</p>
                            <p>75001 Paris</p>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="bg-primary text-white rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-phone fa-lg"></i>
                        </div>
                        <div>
                            <h5>Téléphone</h5>
                            <p class="mb-0"><a href="tel:+33123456789" class="text-dark">07 68 16 39 48</a></p>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="bg-primary text-white rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-envelope fa-lg"></i>
                        </div>
                        <div>
                            <h5>Email</h5>
                            <p class="mb-0"><a href="mailto:businesscareams@gmail.com" class="text-dark">businesscareams@gmail.com</a></p>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="bg-primary text-white rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-clock fa-lg"></i>
                        </div>
                        <div>
                            <h5>Horaires</h5>
                            <p class="mb-0">Lundi - Vendredi : 9h00 - 18h00</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="card-title mb-3">Suivez-nous</h3>
                    <div class="d-flex justify-content-around">
                        <a href="#" class="text-primary" title="Facebook">
                            <i class="fab fa-facebook-square fa-2x"></i>
                        </a>
                        <a href="#" class="text-primary" title="Twitter">
                            <i class="fab fa-twitter-square fa-2x"></i>
                        </a>
                        <a href="#" class="text-primary" title="LinkedIn">
                            <i class="fab fa-linkedin fa-2x"></i>
                        </a>
                        <a href="#" class="text-primary" title="Instagram">
                            <i class="fab fa-instagram-square fa-2x"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <h2 class="mb-4">Nos bureaux</h2>
        </div>
        

        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Paris (Siège social)</h3>
                </div>
                <div class="card-body">
                    <div class="ratio ratio-16x9 mb-3">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2624.9916256937604!2d2.3381203!3d48.8615491!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66e28501db14f%3A0x8cf7420fe358dd75!2s110%20Rue%20de%20Rivoli%2C%2075001%20Paris!5e0!3m2!1sfr!2sfr!4v1701961714968!5m2!1sfr!2sfr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                    <p><strong>Adresse :</strong> 110, rue de Rivoli, 75001 Paris</p>
                    <p><strong>Téléphone :</strong> <a href="tel:+33123456789" class="text-dark">+33 1 23 45 67 89</a></p>
                    <p><strong>Email :</strong> <a href="mailto:paris@business-care.fr" class="text-dark">paris@business-care.fr</a></p> 
                    <p><strong>Accès :</strong> Métro Châtelet-Les Halles (lignes 1, 4, 7, 11, 14, RER A, B, D)</p>
                </div>
            </div>
        </div>
        

        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Troyes</h3>
                </div>
                <div class="card-body">
                    <div class="ratio ratio-16x9 mb-3">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2646.982076976071!2d4.091292!3d48.43084895!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47ee993d0eddca89%3A0xb90f1138df86e1b!2s13%20Rue%20Antoine%20Parmentier%2C%2010000%20Troyes!5e0!3m2!1sfr!2sfr!4v1701961800968!5m2!1sfr!2sfr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                    <p><strong>Adresse :</strong> 13, rue Antoine Parmentier, 10000 Troyes</p>
                    <p><strong>Téléphone :</strong> <a href="tel:+33325123456" class="text-dark">+33 3 25 12 34 56</a></p>
                    <p><strong>Email :</strong> <a href="mailto:troyes@business-care.fr" class="text-dark">troyes@business-care.fr</a></p>
                </div>
            </div>
        </div>
        

        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Nice</h3>
                </div>
                <div class="card-body">
                    <div class="ratio ratio-16x9 mb-3">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2886.097867742652!2d7.2668197!3d43.6945445!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12cddaa7be09e337%3A0x9e52cbfd43ea9ac6!2s8%20Rue%20Beaumont%2C%2006300%20Nice!5e0!3m2!1sfr!2sfr!4v1701961900968!5m2!1sfr!2sfr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                    <p><strong>Adresse :</strong> 8, rue Beaumont, 06300 Nice</p>
                    <p><strong>Téléphone :</strong> <a href="tel:+33493123456" class="text-dark">+33 4 93 12 34 56</a></p>
                    <p><strong>Email :</strong> <a href="mailto:nice@business-care.fr" class="text-dark">nice@business-care.fr</a></p>
                </div>
            </div>
        </div>
        

        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Biarritz</h3>
                </div>
                <div class="card-body">
                    <div class="ratio ratio-16x9 mb-3">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2896.7547835353434!2d-1.5594628841618152!3d43.47964136724008!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd516d4a35c1f2f3%3A0x62c24f58b5a6fc31!2s47%20Avenue%20du%20Pr%C3%A9sident%20J.F.%20Kennedy%2C%2064200%20Biarritz!5e0!3m2!1sfr!2sfr!4v1701962000968!5m2!1sfr!2sfr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                    <p><strong>Adresse :</strong> 47, rue Lisboa, 64200 Biarritz</p>
                    <p><strong>Téléphone :</strong> <a href="tel:+33559123456" class="text-dark">+33 5 59 12 34 56</a></p>
                    <p><strong>Email :</strong> <a href="mailto:biarritz@business-care.fr" class="text-dark">biarritz@business-care.fr</a></p>
                </div>
            </div>
        </div>
    </div>
</div>