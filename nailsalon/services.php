<?php
// services.php
include 'includes/sessions.php';
require 'includes/database-connection.php';

function get_all_services(PDO $pdo): array {
    $sql = "SELECT * FROM Service ORDER BY ServiceID;";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}
$services = get_all_services($pdo);

include 'includes/header.php';
?>
<main class="container">
  <h2>Our Services</h2>
  <div class="services-list">
    <?php foreach ($services as $svc): ?>
      <div class="service-card">
        <h3><?= htmlspecialchars($svc['Type']) ?></h3>
        <p><strong>Price:</strong> $<?= htmlspecialchars($svc['Price']) ?></p>
        <a href="booking.php"
           class="btn">Book This Service</a>
      </div>
    <?php endforeach; ?>
  </div>
  <div>
    <a href="home.php" class="btn">Home</a>
    <?php if ($logged_in): ?>
      <a href="logout.php" class="btn">Logout</a>
    <?php else: ?>
      <a href="login.php" class="btn">Login</a>
    <?php endif; ?>
  </div>
</main>
<footer class="footer">
    &copy; 2025 J&amp;T&amp;G Nails & SPA
  </footer>
</body>
</html>