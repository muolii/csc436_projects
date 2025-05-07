<?php
include 'includes/sessions.php';

session_start();

require 'includes/database-connection.php';

// if they’re already logged in, bounce them to the account page
if (!empty($_SESSION['logged_in'])) {
    header('Location: appointments.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username      = $_POST['username'] ?? '';
    $user_password = $_POST['password'] ?? '';

    // look up that username in your technicians table
    $sql  = "SELECT TechnicianID, Name, username, password 
             FROM Technician
             WHERE username = :username 
             LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['username' => $username]);
    $tech = $stmt->fetch();

    // if we found them & the password matches the stored hash:
    if ($tech && $user_password === $tech['password']) {
        session_regenerate_id(true);
        $_SESSION['logged_in'] = true;
        $_SESSION['tech_id']   = $tech['TechnicianID'];
        $_SESSION['tech_name'] = $tech['Name'];
        header('Location: appointments.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<?php include 'includes/header.php'; ?>
<body>
  <header>…</header>
  <main class="container">
    <h2>Login</h2>
    <?php if ($error): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST" action="">
      <label for="username">Username</label>
      <input id="username" name="username" type="text" placeholder="Username" required>

      <label for="password">Password</label>
      <input id="password" name="password" type="password" placeholder="Password" required>

      <button type="submit" class="btn">Login</button>
    </form>
    <a href="signup.php" class="btn">Sign Up</a>
  </main>
  <footer>…</footer>
</body>
</html>
