<?php
session_start();

require_once '../src/config.php';
require_once '../src/Order.php';
require_once '../src/User.php';
require_once '../src/MenuItem.php';

// Redirect to login if not authenticated or not an admin/manager
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager')) {
    header('Location: login.php');
    exit();
}

$order = new Order();
$user = new User();
$menuItem = new MenuItem();

$orders = $order->getOrders();

// Handle Delete Order
if (isset($_GET['delete'])) {
    if ($order->deleteOrder($_GET['delete'])) {
        $_SESSION['message'] = 'Order deleted successfully.';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Error deleting order.';
        $_SESSION['message_type'] = 'danger';
    }
    header('Location: manage_orders.php');
    exit();
}

// Handle Update Order Status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    $order_data = $order->getOrderById($order_id);
    if ($order_data) {
        $data = [
            'id' => $order_id,
            'user_id' => $order_data->user_id,
            'menu_item_id' => $order_data->menu_item_id,
            'status' => $new_status
        ];
        if ($order->updateOrder($data)) {
            $_SESSION['message'] = 'Order status updated successfully.';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Error updating order status.';
            $_SESSION['message_type'] = 'danger';
        }
    } else {
        $_SESSION['message'] = 'Order not found.';
        $_SESSION['message_type'] = 'danger';
    }
    header('Location: manage_orders.php');
    exit();
}

include '../src/includes/header.php';
?>

<h1 class="mb-3">Manage Orders</h1>
<p class="text-start"><a href="<?php echo ($_SESSION['role'] === 'admin') ? 'admin_dashboard.php' : 'manager_dashboard.php'; ?>" class="back-link"><i class="fas fa-arrow-left me-1"></i> Back to Dashboard</a></p>

<h2 class="mb-3">All Orders</h2>
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User</th>
                <th>Menu Item</th>
                <th>Status</th>
                <th>Timestamp</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $ord): ?>
                <?php
                    $ordered_user = $user->getUserById($ord->user_id);
                    $ordered_item = $menuItem->getMenuItemById($ord->menu_item_id);
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($ord->id); ?></td>
                    <td><?php echo htmlspecialchars($ordered_user->username); ?></td>
                    <td><?php echo htmlspecialchars($ordered_item->name); ?></td>
                    <td>
                        <form action="manage_orders.php" method="POST" class="d-inline">
                            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($ord->id); ?>">
                            <select name="status" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                                <option value="pending" <?php echo ($ord->status == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="fulfilled" <?php echo ($ord->status == 'fulfilled') ? 'selected' : ''; ?>>Fulfilled</option>
                                <option value="cancelled" <?php echo ($ord->status == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                            <input type="hidden" name="update_status" value="1">
                        </form>
                    </td>
                    <td><?php echo htmlspecialchars($ord->timestamp); ?></td>
                    <td>
                        <a href="manage_orders.php?delete=<?php echo $ord->id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this order?');" title="Delete"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../src/includes/footer.php'; ?>