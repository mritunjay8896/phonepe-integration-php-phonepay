![payregistrion](https://github.com/user-attachments/assets/5848dff6-dcb8-468b-b766-839be42ace6a)
![success](https://github.com/user-attachments/assets/790a92f4-81b8-4107-93fb-ae9ca750fda6)
![pay1](https://github.com/user-attachments/assets/4d213275-8ac7-435b-a62b-6aa43d1a79c7)
![pay](https://github.com/user-attachments/assets/29457091-1367-4ba9-836c-5520078c680c)
#  **Payment and Registration System** 

This project implements a **registration** and **payment system** with subscription plans. It handles user registration, payment initiation, and success response handling. Built with **PHP**, **MySQL**, and integrates with the **PhonePe** payment gateway.

---

## 🗂️ **Project Structure**
├── config.php # Configuration settings for database and payment integration

├── pay.php # Initiates payment process with the selected subscription plan

├── register.php # Handles user registration, stores temporary data, and redirects to payment page 

├── success.php # Handles payment success response and finalizes user registration

├── style.css # Custom CSS for styling the registration and payment pages

└── db_schema.sql # SQL file to create necessary tables in the database



---

## 🛠️ **Installation**

Follow these steps to set up and run the project locally or on your server:

### 1. Clone the repository
Clone this repository to your local machine or server:

```bash
git clone https://github.com/yourusername/projectname.git
2. Setup Database
Create a MySQL database using the provided SQL file.

Create a database on your MySQL server.
Import the db_schema.sql file to create the necessary tables.
sql
Copy code
-- Import the db_schema.sql to create tables.
source ./db_schema.sql;
3. Configure config.php
Update the config.php file with your specific settings:

php
Copy code
define("BASE_URL", "https://yoursiteurl.com/");  // Replace with your live domain
define("API_STATUS", "LIVE");                    // Set to LIVE for production
define("MERCHANTIDLIVE", "your merchant id");    // Live Merchant ID
define("SALTKEYLIVE", "your key");               // Live Salt Key
define("SALTINDEX", "1"); // Don’t change this 
define("REDIRECTURL", "success.php");
define("UATURLPAY", "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay"); // TEST URL
define("LIVEURLPAY", "https://api.phonepe.com/apis/hermes/pg/v1/pay");           // LIVE URL
✨ Features
1. User Registration (register.php)
Users can register with their name, WhatsApp number, email, password, and optional referral code.
Users must select a subscription plan during registration.
On successful registration, a CSRF token and transaction ID are generated and stored in the session for payment processing.
2. Payment Processing (pay.php)
Initiates the payment process by sending a request to the PhonePe API with user details and the payment amount.
After payment, users are redirected to success.php.
3. Payment Success Handling (success.php)
Handles the response from the payment gateway and, if successful, moves user data to the permanent table.
Displays a success message along with transaction details.
🔄 Workflow
User Registration:

User submits a registration form with personal information and subscription plan.
Data is stored in the temporary_user_data table.
User is redirected to pay.php for payment.
Payment Processing:

A payment request is made via the PhonePe API using the transaction ID, amount, and other data.
The user is redirected to complete the payment.
Payment Success:

Once payment is successful, the data from temporary_user_data is moved to the users table.
Temporary data is deleted after successful insertion.
📝 Code Explanation
register.php
Handles user registration and stores data temporarily before payment.
Generates a transaction ID and CSRF token.
Example:

php
Copy code
$transaction_id = 'TXN_' . bin2hex(random_bytes(10));
pay.php
Initiates the payment process by sending a request to the PhonePe API.
Example:

php
Copy code
$checksum = hash("sha256", $payloadEncoded . "/pg/v1/pay" . SALTKEYLIVE) . "###" . SALTINDEX;
success.php
Handles the response and stores data in the permanent users table.
Displays success message.
Example:

php
Copy code
$stmt = $conn->prepare("INSERT INTO users (name, whatsapp_number, email, password, referral_code, subscription_plan) VALUES (?, ?, ?, ?, ?, ?)");
config.php
Stores database credentials, payment gateway details, and other configuration settings.
🔒 Security Considerations
CSRF Protection:

The CSRF token is generated during registration and passed during payment to validate the request.
Password Hashing:

Passwords are securely hashed using password_hash().
Input Validation:

Form inputs are validated and sanitized to prevent SQL injection.
Error Handling:

Detailed error messages are displayed for debugging and troubleshooting.
✅ Testing
Test the system using the UAT (User Acceptance Testing) URL to simulate the payment process.
Once confirmed, switch to the LIVE URL with production credentials.
