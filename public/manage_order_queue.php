<?php
session_start();

require_once '../src/config.php';
require_once '../src/OrderQueue.php';
require_once '../src/Order.php';
require_once '../src/User.php';
require_once '../src/MenuItem.php';

// Redirect to login if not authenticated or not an admin/manager/attendant
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager' && $_SESSION['role'] !== 'attendant')) {
    header('Location: login.php');
    exit();
}

$orderQueue = new OrderQueue();
$order = new Order();
$user = new User();
$menuItem = new MenuItem();

$queue_items = $orderQueue->getOrderQueue();

// Handle Deleting from Queue (marking order as fulfilled)
if (isset($_GET['fulfill'])) {
    $queue_id = $_GET['fulfill'];
    $queue_entry = $orderQueue->getOrderQueueById($queue_id);

    if ($queue_entry) {
        $order_id = $queue_entry->order_id;
        $order_data = $order->getOrderById($order_id);

        if ($order_data) {
            $data = [
                'id' => $order_id,
                'user_id' => $order_data->user_id,
                'menu_item_id' => $order_data->menu_item_id,
                'status' => 'fulfilled'
            ];
            if ($order->updateOrder($data)) {
                if ($orderQueue->deleteOrderFromQueue($queue_id)) {
                    $_SESSION['message'] = 'Order fulfilled and removed from queue successfully.';
                    $_SESSION['message_type'] = 'success';
                } else {
                    $_SESSION['message'] = 'Error removing order from queue.';
                    $_SESSION['message_type'] = 'danger';
                }
            } else {
                $_SESSION['message'] = 'Error updating order status.';
                $_SESSION['message_type'] = 'danger';
            }
        } else {
            $_SESSION['message'] = 'Order not found.';
            $_SESSION['message_type'] = 'danger';
        }
    } else {
        $_SESSION['message'] = 'Queue entry not found.';
        $_SESSION['message_type'] = 'danger';
    }
    header('Location: manage_order_queue.php');
    exit();
}

include '../src/includes/header.php';
?>

<h1 class="mb-3">Manage Order Queue</h1>
<p class="text-start"><a href="<?php
    if ($_SESSION['role'] === 'admin') echo 'admin_dashboard.php';
    else if ($_SESSION['role'] === 'manager') echo 'manager_dashboard.php';
    else echo 'attendant_dashboard.php';
?>" class="back-link"><i class="fas fa-arrow-left me-1"></i> Back to Dashboard</a></p>

<h2 class="mb-3">Current Order Queue</h2>
<?php if (empty($queue_items)): ?>
    <p class="text-center">The order queue is empty.</p>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Queue ID</th>
                    <th>Order ID</th>
                    <th>User</th>
                    <th>Menu Item</th>
                    <th>Status</th>
                    <th>Time Added</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($queue_items as $queue_entry): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($queue_entry->queue_id); ?></td>
                        <td><?php echo htmlspecialchars($queue_entry->order_id); ?></td>
                        <td><?php echo htmlspecialchars($queue_entry->username); ?></td>
                        <td><?php echo htmlspecialchars($queue_entry->item_name); ?></td>
                        <td><?php echo htmlspecialchars($queue_entry->status); ?></td>
                        <td><?php echo htmlspecialchars($queue_entry->timestamp); ?></td>
                        <td>
                            <a href="manage_order_queue.php?fulfill=<?php echo $queue_entry->queue_id; ?>" class="btn btn-sm btn-success" onclick="return confirm('Mark this order as fulfilled and remove from queue?');">Fulfill Order</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php include '../src/includes/footer.php'; ?>