<?php
define("BASE_URL", "https://cvbunch.com/"); // Replace with your live domain
define("API_STATUS", "LIVE"); // Set to LIVE for production
define("MERCHANTIDLIVE", "M22664QSUY4FH"); // Live Merchant ID
define("SALTKEYLIVE", "4a40518c-58eb-48dc-b841-481e2f52846c"); // Live Salt Key
define("SALTINDEX", "1");
define("REDIRECTURL", "success.php");
define("UATURLPAY", "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay");
define("LIVEURLPAY", "https://api.phonepe.com/apis/hermes/pg/v1/pay");
?>


<?php
// Database Connection Script
$host = 'localhost';
$db = 'u374655946_testdb';
$user = 'u374655946_test';
$pass = 'Mish8896.,d';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}




?>
