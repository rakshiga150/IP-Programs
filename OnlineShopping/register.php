<?php

include "includes/db.php";

session_start();

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if (
        empty($name) ||
        empty($email) ||
        empty($password)
    ) {

        $message = "Please fill all required fields.";
        $messageType = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $messageType = "error";

    } elseif ($password !== $confirmPassword) {

        $message = "Passwords do not match.";
        $messageType = "error";

    } elseif (strlen($password) < 6) {

        $message = "Password must contain at least 6 characters.";
        $messageType = "error";

    } else {

        $check = $conn->prepare(
            "SELECT id FROM users WHERE email = ?"
        );

        $check->bind_param("s", $email);
        $check->execute();

        $result = $check->get_result();

        if ($result->num_rows > 0) {

            $message = "Email is already registered.";
            $messageType = "error";

        } else {

            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $stmt = $conn->prepare(
                "INSERT INTO users
                (name, email, password)
                VALUES (?, ?, ?)"
            );

            $stmt->bind_param(
                "sss",
                $name,
                $email,
                $hashedPassword
            );

            if ($stmt->execute()) {

                $message = "Registration successful! You can now login.";
                $messageType = "success";

            } else {

                $message = "Registration failed.";
                $messageType = "error";

            }

        }

    }

}

include "includes/header.php";

?>

<div class="auth-container">

    <div class="auth-card">

        <div class="auth-icon">
            ✨
        </div>

        <h1>Create Account</h1>

        <p class="auth-subtitle">
            Join ShopSphere and start shopping.
        </p>

        <?php if ($message): ?>

            <div class="alert <?= $messageType ?>">
                <?= htmlspecialchars($message) ?>
            </div>

        <?php endif; ?>


        <form method="POST">

            <label>Full Name</label>

            <input
                type="text"
                name="name"
                placeholder="Enter your name"
                required>


            <label>Email Address</label>

            <input
                type="email"
                name="email"
                placeholder="Enter your email"
                required>


            <label>Password</label>

            <input
                type="password"
                name="password"
                placeholder="Minimum 6 characters"
                required>


            <label>Confirm Password</label>

            <input
                type="password"
                name="confirm_password"
                placeholder="Re-enter password"
                required>


            <button type="submit" class="auth-btn">
                Create Account
            </button>

        </form>


        <p class="auth-footer">
            Already have an account?
            <a href="login.php">Login</a>
        </p>

    </div>

</div>


<?php include "includes/footer.php"; ?>