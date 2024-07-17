<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if (isset($_POST['update_payment'])) {
   $order_id = $_POST['order_id'];
   $payment_status = $_POST['payment_status'];
   $update_status = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
   $update_status->execute([$payment_status, $order_id]);
   $message[] = 'Payment status updated!';
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_order->execute([$delete_id]);
   header('location:placed_orders.php');
}

if (isset($_POST['print_orders'])) {
   // Code for printing orders
   // You can add any printing logic here

   // Display notification message
   $message[] = 'Orders have been printed successfully!';
}

// Fetch orders
$select_orders = $conn->prepare("SELECT * FROM `orders`");
$select_orders->execute();
$orders = $select_orders->fetchAll(PDO::FETCH_ASSOC);

// Ensure $orders is always an array
$orders = is_array($orders) ? $orders : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Placed Orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- Your existing code for other sections... -->

<!-- Add the new button for printing orders -->
<a href="#" onclick="printOrders()" class="btn">Print Orders</a>

<!-- Your existing code for other sections... -->

<section class="placed-orders">
   <h1 class="heading">Placed Orders</h1>

   <div class="box-container">
      <?php if (!empty($orders)): ?>
         <?php foreach ($orders as $order): ?>
            <div class="box">
               <p>user id: <span><?= $order['user_id']; ?></span></p>
               <p>placed on: <span><?= $order['placed_on']; ?></span></p>
               <p>name: <span><?= $order['name']; ?></span></p>
               <p>email: <span><?= $order['email']; ?></span></p>
               <p>number: <span><?= $order['number']; ?></span></p>
               <p>address: <span><?= $order['address']; ?></span></p>
               <p>total products: <span><?= $order['total_products']; ?></span></p>
               <p>total price: <span>Ksh<?= $order['total_price']; ?>/-</span></p>
               <p>payment method: <span><?= $order['method']; ?></span></p>
               <p>payment status: <span><?= $order['payment_status']; ?></span></p>
               
               <form action="" method="POST">
                  <input type="hidden" name="order_id" value="<?= $order['id']; ?>">
                  <select name="payment_status" class="drop-down">
                     <option value="" selected disabled><?= $order['payment_status']; ?></option>
                     <option value="pending">pending</option>
                     <option value="completed">completed</option>
                  </select>
                  <div class="flex-btn">
                     <input type="submit" value="update" class="btn" name="update_payment">
                     <a href="placed_orders.php?delete=<?= $order['id']; ?>" class="delete-btn" onclick="return confirm('delete this order?');">delete</a>
                  </div>
               </form>
            </div>
         <?php endforeach; ?>
      <?php else: ?>
         <p class="empty">No orders placed yet!</p>
      <?php endif; ?>
   </div>
</section>

<!-- Your existing code for other sections... -->

<!-- Display notification message if exists -->
<?php if (!empty($message)): ?>
   <div class="notification">
      <?php foreach ($message as $msg): ?>
         <p><?= $msg; ?></p>
      <?php endforeach; ?>
   </div>
<?php endif; ?>

<!-- Your existing code for other sections... -->

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

<script>
function printOrders() {
   console.log('Orders are being printed!');

   // Open the print dialog for the user
   window.print(); 
}
</script>
<!-- Your existing code for other sections... -->

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

<script>
function printOrders() {
   console.log('Orders are being printed!');

   // Open the print dialog for the user
   window.print(); 
}

// Add event listener for after print
window.onafterprint = function() {
   // Display a success alert message after printing is done
   alert('Orders have been printed successfully!');
}
</script>

</body>
</html>
