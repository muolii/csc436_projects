<?php
require 'includes/database-connection.php';
include 'includes/sessions.php';    

$page_title = 'Book an Appointment';
include 'includes/header.php';


function get_all_services(PDO $pdo): array {
    $stmt = $pdo->query(
        "SELECT ServiceID, `Type` 
           FROM Service 
         ORDER BY `Type`"
    );
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_all_technicians(PDO $pdo): array {
    $stmt = $pdo->query(
        "SELECT TechnicianID, Name 
           FROM Technician 
         ORDER BY Name"
    );
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_available_timeslots(PDO $pdo, string $date, string $tech_id): array {
  // Build the full day’s slots in 30-minute increments
  $start    = new DateTime("$date 10:00");
  $end      = new DateTime("$date 17:00");
  $inteval  = new DateInterval('PT30M');
  $slots    = [];
  for ($dt = clone $start; $dt < $end; $dt->add($inteval)) {
      $slots[] = $dt->format('H:i:s');
  }

  // Pull out the start_times already booked for that tech on that date
  $sql = "
    SELECT ts.Start_Time
      FROM Appointment a
      JOIN Timeslot   ts ON a.TimeslotID = ts.TimeslotID
     WHERE ts.Date        = :date
       AND a.TechnicianID = :tech_id
  ";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    'date'    => $date,
    'tech_id' => $tech_id,
  ]);
  $booked = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

  // Remove any booked slots
  $available = array_values(array_diff($slots, $booked));

  return $available;
}


$selected_date    = $_POST['date']       ?? '';
$selected_service = $_POST['service']    ?? '';
$selected_tech    = $_POST['technician'] ?? '';

$services    = get_all_services($pdo);
$technicians = get_all_technicians($pdo);

$available_times = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $available_times = get_available_timeslots(
       $pdo,
       $_POST['date'],
       $_POST['technician']
    );
}
?>

<main class="container">
  <h2>Book an Appointment</h2>

  <form method="POST" action="">
    <label for="date">Enter a day:</label>
    <input 
      type="date" 
      id="date" 
      name="date" 
      required
      value="<?= htmlspecialchars($selected_date) ?>"
    >

    <label for="service">Enter a service:</label>
    <select id="service" name="service" required>
      <option value="">— Select service —</option>
      <?php foreach ($services as $s): ?>
        <option 
          value="<?= $s['ServiceID'] ?>"
          <?= $s['ServiceID'] == $selected_service ? 'selected' : '' ?>
        >
          <?= htmlspecialchars($s['Type']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label for="technician">Select a technician:</label>
    <select id="technician" name="technician" required>
      <option value="">— Select technician —</option>
      <?php foreach ($technicians as $t): ?>
        <option 
          value="<?= $t['TechnicianID'] ?>"
          <?= $t['TechnicianID'] == $selected_tech ? 'selected' : '' ?>
        >
          <?= htmlspecialchars($t['Name']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <button type="submit" class="btn">Check Availability</button>
  </form>

  <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
  <div class="availability">
    <h3>Available Times</h3>
    <?php if (empty($available_times)): ?>
      <p>No slots available.</p>
    <?php else: ?>
      <ul>
        <?php foreach ($available_times as $time): ?>
          <li><?= htmlspecialchars(date('g:i A', strtotime($time))) ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>
<?php endif; ?>

  <div class="form-actions">
    <a href="home.php" class="btn">Home</a>
    <a href="?logout=1" class="btn">Log Out</a>
  </div>
</main>

<footer class="footer">
    &copy; 2025 J&amp;T&amp;G Nails & SPA
  </footer>
</body>
</html>