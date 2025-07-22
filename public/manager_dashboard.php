<?php
require_once '../src/config.php';
include '../src/includes/header.php';

// Redirect to login if not authenticated or not a manager
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
?>

<h1 class="mb-3">Manager Dashboard</h1>
<p class="lead mb-4">Welcome, <?php echo htmlspecialchars($username); ?>! Here you can manage menu items, orders, and the order queue.</p>

<div class="row">
    <div class="col-md-6 mb-4">
        <h2 class="mb-2">Menu Item Management</h2>
        <p class="mb-3">Manage food and drink items, including prices, descriptions, and images.</p>
        <a href="manage_menu_items.php" class="btn btn-primary">Go to Menu Item Management</a>
    </div>
    <div class="col-md-6 mb-4">
        <h2 class="mb-2">Order Management</h2>
        <p class="mb-3">View and update the status of all customer orders.</p>
        <a href="manage_orders.php" class="btn btn-primary">Go to Order Management</a>
    </div>
    <div class="col-md-6 mb-4">
        <h2 class="mb-2">Order Queue Management</h2>
        <p class="mb-3">Monitor and manage the real-time order queue for attendants.</p>
        <a href="manage_order_queue.php" class="btn btn-primary">Go to Order Queue Management</a>
    </div>
</div>

<?php include '../src/includes/footer.php'; ?>