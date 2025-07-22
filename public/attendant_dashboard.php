<?php
require_once '../src/config.php';
include '../src/includes/header.php';

// Redirect to login if not authenticated or not an attendant
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'attendant') {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
?>

<h1 class="mb-3">Attendant Dashboard</h1>
<p class="mb-4">Welcome, <?php echo htmlspecialchars($username); ?>! Here you can manage the order queue.</p>

<h2 class="mb-2">Order Queue Management</h2>
<p class="mb-3">Monitor and manage the real-time order queue.</p>
<a href="manage_order_queue.php" class="btn btn-primary">Go to Order Queue Management</a>

<?php include '../src/includes/footer.php'; ?>