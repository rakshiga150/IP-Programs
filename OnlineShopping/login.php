<?php

include "includes/db.php";

session_start();

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare(
        "SELECT * FROM users WHERE email = ?"
    );

    $stmt->bind_param("s", $email);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {

    header("Location: admin/dashboard.php");
    exit;

} elseif ($user['role'] === 'customer') {

    header("Location: index.php");
    exit;

} else {

    $message = "Invalid account role.";
    $messageType = "error";

}

        } else {

            $message = "Incorrect password.";
            $messageType = "error";

        }

    } else {

        $message = "Account not found.";
        $messageType = "error";

    }

}

include "includes/header.php";

?>

<div class="auth-container">

    <div class="auth-card">

        <div class="auth-icon">
            🔐
        </div>

        <h1>Welcome Back</h1>

        <p class="auth-subtitle">
            Login to continue shopping.
        </p>


        <?php if ($message): ?>

            <div class="alert <?= $messageType ?>">
                <?= htmlspecialchars($message) ?>
            </div>

        <?php endif; ?>


        <form method="POST">

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
                placeholder="Enter your password"
                required>


            <button type="submit" class="auth-btn">
                Login
            </button>

        </form>


        <p class="auth-footer">

            Don't have an account?

            <a href="register.php">
                Create Account
            </a>

        </p>

    </div>

</div>


<?php include "includes/footer.php"; ?>