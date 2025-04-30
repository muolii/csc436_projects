<?php
    require 'includes/database-connection.php';
    include 'includes/sessions.php';    // or wherever you have your login/logout/require_login()
    
    // make sure they’re logged in:
    require_login($_SESSION['logged_in'] ?? false);
    
    // pull the tech’s ID out of the session:
    $tech_id = $_SESSION['tech_id'];    

    function get_appointment_info(PDO $pdo, string $tech_id) {
$sql = "
SELECT 
            ts.Start_Time AS StartTime, 
            ts.Date AS Day, 
            s.Type AS ServiceName, 
            c.Name AS CustomerName 
            FROM 
            Appointment a
            JOIN Timeslot ts ON a.TimeslotID = ts.TimeslotID 
            JOIN Service s ON a.ServiceID = s.ServiceID 
            JOIN Customer c ON a.AppointmentID = c.AppointmentID
            WHERE
            a.TechnicianID = :tech_id
            ORDER BY
            ts.Date;
";

$stmt = $pdo->prepare($sql);
$stmt->execute(['tech_id' => $tech_id]);

return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
$appointments = get_appointment_info($pdo, $tech_id);

    ?> 

<?php include 'includes/header.php'; ?>
  <main class="container">
    <h2>My Appointments</h2>
    <table>
      <thead>
        <tr>
          <th>Appointment Time</th>
          <th>Appointment Day</th>
          <th>Service</th>
          <th>Customer</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($appointments as $cust_info): ?>
        <tr>
            <td><?= htmlspecialchars($cust_info['StartTime']) ?></td>
            <td><?= htmlspecialchars($cust_info['Day']) ?></td>
            <td><?= htmlspecialchars($cust_info['ServiceName']) ?></td>
            <td><?= htmlspecialchars($cust_info['CustomerName']) ?></td>
        </tr>
    <?php endforeach; ?>
      </tbody>
    </table>
    <div>
      <a href="logout.php" class="btn">Logout</a>
    </div>
  </main>
  <footer class="footer">
    &copy; 2025 J&amp;T&amp;G Nails & SPA
  </footer>
</body>
</html>