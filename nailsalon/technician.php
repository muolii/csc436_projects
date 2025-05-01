<?php
include 'includes/sessions.php';
require 'includes/database-connection.php';

function get_all_technicians(PDO $pdo): array {
    $sql = "SELECT * FROM Technician ORDER BY Name;";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$technicians = get_all_technicians($pdo);
?>

<?php include 'includes/header.php'; ?>
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
    </div>
  </main>

  <footer class="footer">
    &copy; 2025 J&amp;T&amp;G Nails & SPA
  </footer>
</body>
</html>