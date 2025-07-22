<?php
session_start();

require_once '../src/config.php';
require_once '../src/Order.php';
require_once '../src/MenuItem.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$order = new Order();
$menuItem = new MenuItem();

$user_id = $_SESSION['user_id'];
$user_orders = [];

$user_orders = $order->getOrdersByUserId($user_id);

include '../src/includes/header.php';
?>

<h1 class="mb-3">My Orders</h1>
<p class="text-start"><a href="index.php" class="back-link"><i class="fas fa-arrow-left me-1"></i> Back to Home</a></p>

<?php if (empty($user_orders)): ?>
    <p class="text-center">You have not placed any orders yet.</p>
<?php else: ?>
    <h2 class="mb-3">Your Order History</h2>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Menu Item</th>
                    <th>Status</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($user_orders as $ord): ?>
                    <?php $ordered_item = $menuItem->getMenuItemById($ord->menu_item_id); ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ord->id); ?></td>
                        <td><?php echo htmlspecialchars($ordered_item->name); ?></td>
                        <td><?php echo htmlspecialchars($ord->status); ?></td>
                        <td><?php echo htmlspecialchars($ord->timestamp); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php include '../src/includes/footer.php'; ?>