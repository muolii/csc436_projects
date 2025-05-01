<?php
require 'includes/database-connection.php';
include  'includes/sessions.php';

$page_title = 'Book an Appointment';
include  'includes/header.php';

function get_all_services(PDO $pdo): array {
    $stmt = $pdo->query("
        SELECT ServiceID, `Type`
          FROM Service
         ORDER BY `Type`
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_all_technicians(PDO $pdo): array {
    $stmt = $pdo->query("
        SELECT TechnicianID, Name
          FROM Technician
         ORDER BY Name
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_available_timeslots(PDO $pdo, string $date, string $tech_id): array {
    $start   = new DateTime("$date 10:00");
    $end     = new DateTime("$date 17:00");
    $intvl   = new DateInterval('PT30M');
    $slots   = [];
    for ($dt = clone $start; $dt < $end; $dt->add($intvl)) {
        $slots[] = $dt->format('H:i:s');
    }

    $sql = "
      SELECT ts.Start_Time
        FROM Appointment a
        JOIN Timeslot ts ON a.TimeslotID = ts.TimeslotID
       WHERE ts.Date        = :date
         AND a.TechnicianID = :tech
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['date'=>$date,'tech'=>$tech_id]);
    $booked = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

    // subtract
    return array_values(array_diff($slots, $booked));
}

$selected_date    = $_POST['date']       ?? '';
$selected_service = $_POST['service']    ?? '';
$selected_tech    = $_POST['technician'] ?? '';

$available_times = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book'])) {
  // Get the selected timeslot ID from the form
  $timeslot_id = $_POST['timeslot_id'] ?? '';
  
  if (!$timeslot_id) {
    echo "<p class='error'>Please select a time slot.</p>";
  } else {
    // insert into Appointment using the existing timeslot
    $pdo->prepare("
        INSERT INTO Appointment (ServiceID, TimeslotID, TechnicianID)
        VALUES (:svc, :tsid, :tech)
      ")->execute([
        'svc'   => $selected_service,
        'tsid'  => $timeslot_id,
        'tech'  => $selected_tech
      ]);

  echo "<p class='success'>Appointment successfully booked!</p>";
  }
}


if ($_SERVER['REQUEST_METHOD']==='POST' 
    && (isset($_POST['check']) || isset($_POST['book']))
    && $selected_date
    && $selected_tech
) {
    $available_times = get_available_timeslots(
      $pdo, $selected_date, $selected_tech
    );
}

$services    = get_all_services($pdo);
$technicians = get_all_technicians($pdo);
?>

<main class="container">
  <h2>Book an Appointment</h2>

  <!-- Step A: pick date / service / tech -->
  <form method="POST" action="">
    <label for="date">Date:</label>
    <input
      type="date" id="date" name="date" required
      value="<?= htmlspecialchars($selected_date) ?>"
    >

    <label for="service">Service:</label>
    <select id="service" name="service" required>
      <option value="">— Select service —</option>
      <?php foreach ($services as $s): ?>
        <option
          value="<?= $s['ServiceID'] ?>"
          <?= $s['ServiceID']==$selected_service ? 'selected':'' ?>
        >
          <?= htmlspecialchars($s['Type']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label for="technician">Technician:</label>
    <select id="technician" name="technician" required>
      <option value="">— Select technician —</option>
      <?php foreach ($technicians as $t): ?>
        <option
          value="<?= $t['TechnicianID'] ?>"
          <?= $t['TechnicianID']==$selected_tech ? 'selected':'' ?>
        >
          <?= htmlspecialchars($t['Name']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <button type="submit" name="check" class="btn">Check Availability</button>
  </form>

  <?php if (isset($_POST['check']) || isset($_POST['book'])): ?>
    <section class="availability">
      <h3>Available Times on <?= htmlspecialchars($selected_date) ?></h3>

      <?php if (empty($available_times)): ?>
        <p>No slots available.</p>
      <?php else: ?>
        <form method="POST" action="">
          <!-- carry forward the selection -->
          <input type="hidden" name="date"       value="<?= htmlspecialchars($selected_date) ?>">
          <input type="hidden" name="service"    value="<?= htmlspecialchars($selected_service) ?>">
          <input type="hidden" name="technician" value="<?= htmlspecialchars($selected_tech) ?>">

          <label for="timeslot">Pick a time:</label>
          <select id="timeslot" name="timeslot_id" required>
            <option value="">— Select slot —</option>
            <?php foreach ($available_times as $time): 
              // lookup TimeslotID
              $stmt = $pdo->prepare("
                SELECT TimeslotID 
                  FROM Timeslot 
                 WHERE Date = :date 
                   AND Start_Time = :start
                 LIMIT 1
              ");
              $stmt->execute(['date'=>$selected_date,'start'=>$time]);
              $tsid = $stmt->fetchColumn();
            ?>
              <option value="<?= $tsid ?>">
                <?= date('g:i A', strtotime($time)) ?>
              </option>
            <?php endforeach; ?>
          </select>

          <button type="submit" name="book" class="btn">Book Appointment</button>
        </form>
      <?php endif; ?>
    </section>
  <?php endif; ?>

  
  <div class="form-actions">
    <a href="home.php" class="btn">Home</a>
  </div>
</main>

<footer class="footer">
  &copy; 2025 J&amp;T&amp;G Nails & SPA
</footer>
</body>
</html>
            