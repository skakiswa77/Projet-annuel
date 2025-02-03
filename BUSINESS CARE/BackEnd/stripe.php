<?php
require_once '../config.php';
require_once '../db.php'; // Connexion à la base de données
require_once '../vendor/autoload.php';

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? '';

    try {
        if ($type === 'payment') {
            // Paiement unique pour une facture
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $_POST['amount'], 
                'currency' => 'eur',
                'payment_method' => $_POST['payment_method_id'],
                'confirm' => true,
            ]);

            // Enregistrement en base de données
            $stmt = $pdo->prepare("INSERT INTO payments (user_id, amount, stripe_payment_id, status, date) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([
                $_POST['user_id'],
                $_POST['amount'] / 100, 
                $paymentIntent->id,
                'paid'
            ]);

            // Génération de la facture PDF
            require_once 'generate_invoice.php';
            generateInvoice($paymentIntent->id, $_POST['user_id'], $_POST['amount'] / 100);

            echo json_encode(['success' => true, 'paymentIntent' => $paymentIntent]);

        } elseif ($type === 'subscription') {
            // Création d'un abonnement
            $customer = \Stripe\Customer::create([
                'email' => $_POST['email'],
                'payment_method' => $_POST['payment_method_id'],
                'invoice_settings' => ['default_payment_method' => $_POST['payment_method_id']],
            ]);

            $subscription = \Stripe\Subscription::create([
                'customer' => $customer->id,
                'items' => [['price' => $_POST['price_id']]], 
                'expand' => ['latest_invoice.payment_intent'],
            ]);

            // Enregistrement de l'abonnement en base de données
            $stmt = $pdo->prepare("INSERT INTO subscriptions (user_id, stripe_customer_id, stripe_subscription_id, plan, status, start_date) VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->execute([
                $_POST['user_id'],
                $customer->id,
                $subscription->id,
                $_POST['plan'],
                'active'
            ]);

            echo json_encode(['success' => true, 'subscription' => $subscription]);

        } else {
            throw new Exception("Type de transaction non valide.");
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?>
