<?php
// register.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once './config.php'; // Include config file for DB constants
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $whatsapp_mobile = trim($_POST['whatsapp_mobile'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT); // Hash password
    $subscription_plan = intval($_POST['subscription_plan'] ?? 1);
    $referral_code = trim($_POST['referral_code'] ?? '');

    // Validate required fields
    if (empty($name) || empty($whatsapp_mobile) || empty($email) || empty($_POST['password'])) {
        die("Error: All fields are required.");
    }

    // Generate CSRF token, session ID, and transaction ID
    $csrf_token = bin2hex(random_bytes(32));
    $session_id = session_id();
    $transaction_id = 'TXN_' . bin2hex(random_bytes(10));

    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO temporary_user_data (name, csrf, session, whatsapp_mobile, email, password, subscription_plan, referral_code, transaction_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssiss", $name, $csrf_token, $session_id, $whatsapp_mobile, $email, $password, $subscription_plan, $referral_code, $transaction_id);

    if ($stmt->execute()) {
        // Store critical details in session and redirect to payment page
        $_SESSION['transaction_id'] = $transaction_id;
        $_SESSION['csrf_token'] = $csrf_token;
        $_SESSION['user_id'] = $conn->insert_id;
        header('Location: pay.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>



  <div class="registration-container">
        <!-- Fixed text section -->
        <div class="text-section">
            <h2>Welcome to the Journey of Success! 🌟</h2>
            <p>Start Your Registration Now and <font style="color:#d80000;"><strong>Take the Oath</strong></font></p>
            <p>That In Just <strong>5 Months</strong>, You'll Land a High-Paying Job! 🚀 <br> This is Your Path to a Brighter Future — Let’s Make It Happen!</p>
        </div>
        <!-- Scrollable form section -->
        <div class="form-section">
            <form method="POST" action="register.php" class="form-container">
                <!-- Name Input -->
                <div class="form-group">
                    <label for="name">Name</label>
                    <input id="name" type="text" name="name" required autofocus class="form-input">
                </div>
                <!-- WhatsApp Mobile & Email Side by Side -->
                <div class="form-group inline-group">
                    <div class="inline-input">
                        <label for="whatsapp_mobile">WhatsApp Mobile</label>
                        <input id="whatsapp_mobile" type="text" name="whatsapp_mobile" required class="form-input">
                    </div>
                    <div class="inline-input">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" required class="form-input">
                    </div>
                </div>
                <!-- Password & Confirm Password Side by Side -->
                <div class="form-group inline-group">
                    <div class="inline-input">
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password" required class="form-input">
                    </div>
                    <div class="inline-input">
                        <label for="password_confirmation">Confirm Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required class="form-input">
                    </div>
                </div>
                <!-- Referral Code (Optional) -->
                <div class="form-group">
                    <label for="referral_code">Referral Code (Optional)</label>
                    <input id="referral_code" type="text" name="referral_code" class="form-input">
                </div>
                <!-- Subscription Plans -->
                <div class="form-group">
                    <label>Subscription Plans</label>
                    <div class="subscription-plans">
                        <div class="card">
                            <input type="radio" id="plan_1_rupee" name="subscription_plan" value="1" required>
                            <label for="plan_1_rupee">
                                <h4>₹1 / 1 day</h4>
                                <p> ₹1 / day</p>
                            </label>
                        </div>
                        <div class="card">
                            <input type="radio" id="plan_12_days" name="subscription_plan" value="50" required>
                            <label for="plan_12_days">
                                <h4>₹50 / 12 days</h4>
                                <p> ₹4.1 / day</p>
                            </label>
                        </div>
                        <div class="card">
                            <input type="radio" id="plan_month" name="subscription_plan" value="60">
                            <label for="plan_month">
                                <h4>₹60 / month</h4>
                                <p>₹2 / day</p>
                            </label>
                        </div>
                        <div class="card">
                            <input type="radio" id="plan_year" name="subscription_plan" value="200">
                            <label for="plan_year">
                                <h4>₹200 / year</h4>
                                <p>₹0.54 / day</p>
                            </label>
                        </div>
                    </div>
                </div>
                <!-- Submit Button -->
                <div class="form-group">
                    <button type="submit" class="submit-button">Register</button>
                </div>
            </form>
        </div>
    </div>
