<?php
require 'includes/database-connection.php';

function get_all_technicians(PDO $pdo): array {
    $sql = "SELECT * FROM Technician ORDER BY Name;";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$technicians = get_all_technicians($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Technicians - J&amp;T&amp;G Nails & SPA</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <h1 class="logo">J&amp;T&amp;G Nails & SPA</h1>
    <nav>
      <a href="home.php">Home</a>
      <a href="appointments.php">Appointments</a>
      <a href="booking.php">Book</a>
      <a href="services.php">Services</a>
      <a href="technicians.php">Technicians</a>
      <a href="login.php">Login</a>
      <a href="signup.php">Sign Up</a>
    </nav>
  </header>

  <main class="container">
    <h2>Our Technicians</h2>
    <div class="tech-list">
      <?php foreach ($technicians as $tech): ?>
        <div class="tech-card">
          <h3><?= htmlspecialchars($tech['Name']) ?></h3>
        </div>
      <?php endforeach; ?>
    </div>
    <div>
      <a href="home.php" class="btn">Home</a>
      <a href="logout.php" class="btn">Logout</a>
    </div>
  </main>

  <footer class="footer">
    &copy; 2025 J&amp;T&amp;G Nails & SPA
  </footer>
</body>
</html>