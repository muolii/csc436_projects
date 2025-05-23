<?php   										// Opening PHP tag
	
	// Include the database connection script
	require 'includes/database-connection.php';


	/*
	 * TO-DO: Define a function that retrives ALL customer and order info from the database based on values entered into form.
	 		  - Write SQL query to retrieve ALL customer and order info based on form values
	 		  - Execute the SQL query using the pdo function and fetch the result
	 		  - Return the order info
	 */
	 function get_order_info(PDO $pdo, string $email, string $orderNum) {
		$sql = "
			SELECT 
				c.cname AS customer_name, 
				c.username, 
				o.ordernum, 
				o.quantity, 
				o.date_ordered, 
				o.date_deliv
			FROM orders o
			INNER JOIN customer c ON o.custnum = c.custnum
			WHERE c.email = :email AND o.ordernum = :orderNum
			LIMIT 1;
		";
	
		$stmt = $pdo->prepare($sql);
		$stmt->execute([
			'email' => $email,
			'orderNum' => $orderNum
		]);
	
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
	
	
	// Check if the request method is POST (i.e, form submitted)
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
		// Retrieve the value of the 'email' field from the POST data
		$email = $_POST['email'];

		// Retrieve the value of the 'orderNum' field from the POST data
		$orderNum = $_POST['orderNum'];


		/*
		 * TO-DO: Retrieve info about order from the db using provided PDO connection
		 */
		$order_info = get_order_info($pdo, $email, $orderNum);
		
	}
// Closing PHP tag  ?> 

<!DOCTYPE>
<html>

	<head>
		<meta charset="UTF-8">
  		<meta name="viewport" content="width=device-width, initial-scale=1.0">
  		<title>Toys R URI</title>
  		<link rel="stylesheet" href="css/style.css">
  		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
	</head>

	<body>

		<header>
			<div class="header-left">
				<div class="logo">
					<img src="imgs/logo.png" alt="Toy R URI Logo">
      			</div>

	      		<nav>
	      			<ul>
	      				<li><a href="index.php">Toy Catalog</a></li>
	      				<li><a href="about.php">About</a></li>
			        </ul>
			    </nav>
		   	</div>

		    <div class="header-right">
		    	<ul>
		    		<li><a href="order.php">Check Order</a></li>
		    	</ul>
		    </div>
		</header>

		<main>

			<div class="order-lookup-container">
				<div class="order-lookup-container">
					<h1>Order Lookup</h1>
					<form action="order.php" method="POST">
						<div class="form-group">
							<label for="email">Email:</label>
							<input type="email" id="email" name="email" required>
						</div>

						<div class="form-group">
							<label for="orderNum">Order Number:</label>
							<input type="text" id="orderNum" name="orderNum" required>
						</div>

						<button type="submit">Lookup Order</button>
					</form>
				</div>
				
				<!-- 
				  -- TO-DO: Check if variable holding order is not empty. Make sure to replace null with your variable!
				  -->
				
				<?php if (!empty($order_info)): ?>
					<div class="order-details">

						<!-- 
				  		  -- TO DO: Fill in ALL the placeholders for this order from the db
  						  -->
						<h1>Order Details</h1>
						<p><strong>Name: </strong> <?= $order_info['customer_name'] ?></p>
				        	<p><strong>Username: </strong> <?= $order_info['username'] ?></p>
				        	<p><strong>Order Number: </strong> <?= $order_info['ordernum'] ?></p>
				        	<p><strong>Quantity: </strong> <?= $order_info['quantity'] ?></p>
				        	<p><strong>Date Ordered: </strong> <?= $order_info['date_ordered'] ?></p>
				        	<p><strong>Delivery Date: </strong> <?= $order_info['date_deliv'] ?></p>
				      
					</div>
				<?php endif; ?>

			</div>

		</main>

	</body>

</html>
