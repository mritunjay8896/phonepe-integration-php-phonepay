<?php
// success.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once './config.php';
session_start();

// Log POST and Session data for debugging
file_put_contents('debug_log.txt', "POST: " . print_r($_POST, true) . "\nSESSION: " . print_r($_SESSION, true), FILE_APPEND);

$csrf_token = $_SESSION['csrf_token'] ?? '';
$transaction_id = $_POST['transactionId'] ?? $_SESSION['transaction_id'] ?? ''; // Fallback to POST or Session

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['code'] === 'PAYMENT_SUCCESS') {
    $payment_transaction_id = $_POST['transactionId'];
    $payment_amount = $_POST['amount'];
    $provider_reference_id = $_POST['providerReferenceId'];

    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT * FROM temporary_user_data WHERE transaction_id = ?");
    $stmt->bind_param("s", $transaction_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();

        // Insert user data into the users table
        $stmt = $conn->prepare("INSERT INTO users (name, whatsapp_number, email, password, referral_code, subscription_plan) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $user_data['name'], $user_data['whatsapp_mobile'], $user_data['email'], $user_data['password'], $user_data['referral_code'], $user_data['subscription_plan']);

        if ($stmt->execute()) {
            // Clear temporary user data
            $stmt = $conn->prepare("DELETE FROM temporary_user_data WHERE transaction_id = ?");
            $stmt->bind_param("s", $transaction_id);
            $stmt->execute();

            echo '<div class="card">
                    <div style="border-radius:200px; height:200px; width:200px; background: #F8FAF5; margin:0 auto;">
                        <i class="checkmark success-icon">✓</i>
                    </div>
                    <h1>Success</h1>
                    <p>Transaction ID: ' . htmlspecialchars($payment_transaction_id) . '<br/>
                       PhonePe Reference ID: ' . htmlspecialchars($provider_reference_id) . '<br/>
                       Amount Paid: ₹' . htmlspecialchars($payment_amount / 100) . '</p>
                    <p>Your account has been successfully created. Welcome!</p>
                  </div>';
        } else {
            echo "Error inserting data into users table: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error: No matching transaction data found.";
    }

    $conn->close();
} else {
    echo "Error: Invalid payment data.";
}
?>
