

<?php
session_start();
require_once './config.php';

$transaction_id = $_SESSION['transaction_id'] ?? '';
$csrf_token = $_SESSION['csrf_token'] ?? '';

if (empty($transaction_id) || empty($csrf_token)) {
    die("Error: Invalid session. Please try registering again.");
}

// Retrieve user details for payment
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT * FROM temporary_user_data WHERE transaction_id = ?");
$stmt->bind_param("s", $transaction_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Error: Invalid transaction.");
}

$user_data = $result->fetch_assoc();
$stmt->close();

// Prepare payment request
$merchantId = MERCHANTIDLIVE;
$amount = intval($user_data['subscription_plan'] * 100);
$callbackUrl = BASE_URL . "success.php";

$payload = [
    "merchantId" => $merchantId,
    "merchantTransactionId" => $transaction_id,
    "merchantUserId" => uniqid("USER_"),
    "amount" => $amount,
    "redirectUrl" => $callbackUrl,
    "redirectMode" => "POST",
    "callbackUrl" => $callbackUrl,
    "mobileNumber" => $user_data['whatsapp_mobile'],
    "paymentInstrument" => ["type" => "PAY_PAGE"]
];

$payloadEncoded = base64_encode(json_encode($payload));
$checksum = hash("sha256", $payloadEncoded . "/pg/v1/pay" . SALTKEYLIVE) . "###" . SALTINDEX;

$requestBody = json_encode(["request" => $payloadEncoded]);

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => LIVEURLPAY,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $requestBody,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "X-VERIFY: $checksum"
    ]
]);

$response = curl_exec($curl);
if ($response === false) {
    die("Payment initiation failed: " . curl_error($curl));
}
curl_close($curl);

$res = json_decode($response, true);
if ($res['success'] === true) {
    $payUrl = $res['data']['instrumentResponse']['redirectInfo']['url'];
    header("Location: $payUrl");
    exit();
} else {
    die("Error: " . ($res['message'] ?? "Payment failed."));
}
?>


