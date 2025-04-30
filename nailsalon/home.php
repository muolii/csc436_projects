<?php
require 'includes/sessions.php';

$page_title = 'Welcome';
include 'includes/header.php';
?>

<main class="container">
  <h2>Welcome to J&amp;T&amp;G Nails & SPA</h2>
  <p>Relax, refresh, and let us pamper you. Choose from our full range of nail and spa services below.</p>

  <div class="home-buttons">
    <?php if (!empty($_SESSION['logged_in'])): ?>
      <a href="appointments.php"       class="btn">View Appointments</a>
    <?php endif; ?>

    <a href="booking.php"             class="btn">Book Appointment</a>
    <a href="services.php"            class="btn">Our Services</a>
    <a href="technicians.php"         class="btn">Meet the Team</a>
  </div>

  <?php if (!empty($_SESSION['logged_in'])): ?>
    <div class="logout-link">
        <a href="logout.php"             class="btn">Log Out</a>
    </div>
  <?php endif; ?>
</main>

<footer class="footer">
    &copy; 2025 J&amp;T&amp;G Nails & SPA
  </footer>
</body>
</html>