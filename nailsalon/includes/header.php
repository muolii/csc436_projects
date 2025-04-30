
<!DOCTYPE html>
<html>
    <head>
        <title>J&amp;T&amp;G Nails &amp; SPA</title>
        <link href="css/style.css" rel="stylesheet">
    </head>
    <body>
        <div class="page">
            <header>
                <h1 class="logo">J&amp;T&amp;G Nails &amp; SPA</h1>
            <nav>
                <a href="home.php">Home</a>
                <?= $logged_in ? '<a href="appointments.php">Appointments</a>' : ''; ?>
                <a href="booking.php">Book</a>
                <a href="services.php">Services</a>
                <a href="technician.php">Technicians</a>
                <?= $logged_in ? '<a href="logout.php">Log Out</a>' : '<a href="login.php">Login</a>'; ?>
            </nav>
            </header>
            <section>