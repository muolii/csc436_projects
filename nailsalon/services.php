<?php
require 'includes/database-connection.php';

$service_id = $_GET['service_id'] ?? '';

function get_service_info(PDO $pdo, string $service_id): array|false {
    $sql = "
        SELECT *
        FROM Service
        WHERE ServiceID = :service_id
        LIMIT 1;
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['service_id' => $service_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

$service = $service_id ? get_service_info($pdo, $service_id) : false;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($service['Name'] ?? 'Service Details') ?> - J&amp;T&amp;G Nails & SPA</title>
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
    <?php if ($service): ?>
      <div class="service-details-container">
        <div class="service-image">
          <img src="<?= htmlspecialchars($service['ImagePath']) ?>" alt="<?= htmlspecialchars($service['Name']) ?>">
        </div>
        <div class="service-details">
          <h2><?= htmlspecialchars($service['Name']) ?></h2>
          <p><strong>Description:</strong> <?= htmlspecialchars($service['Description']) ?></p>
          <p><strong>Price:</strong> $<?= htmlspecialchars($service['Price']) ?></p>
          <a href="booking.php?service_id=<?= urlencode($service_id) ?>" class="btn">Book This Service</a>
        </div>
      </div>
    <?php else: ?>
      <p>Sorry, this service was not found.</p>
    <?php endif; ?>

    <a href="services.php" class="btn">Back to Services</a>
  </main>

  <footer class="footer">
    &copy; 2025 J&amp;T&amp;G Nails & SPA
  </footer>
</body>
</html>