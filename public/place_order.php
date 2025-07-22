<?php
require_once '../src/config.php';
require_once '../src/MenuItem.php';
require_once '../src/Order.php';
require_once '../src/OrderQueue.php';

include '../src/includes/header.php';

$menuItem = new MenuItem();
$order = new Order();
$orderQueue = new OrderQueue();

$current_time = date('H');
$time_of_day = '';

if ($current_time >= 5 && $current_time < 12) {
    $time_of_day = 'morning';
} elseif ($current_time >= 12 && $current_time < 17) {
    $time_of_day = 'afternoon';
} else {
    $time_of_day = 'evening';
}

$available_menu_items = [];
foreach ($menuItem->getMenuItems() as $item) {
    if ($item->time_of_day === $time_of_day) {
        $available_menu_items[] = $item;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_order'])) {
    $menu_item_id = $_POST['menu_item_id'];
    $user_id = $_SESSION['user_id'];

    $data = [
        'user_id' => $user_id,
        'menu_item_id' => $menu_item_id,
        'status' => 'pending'
    ];

    if ($order->addOrder($data)) {
        $new_order_id = $order->getLastInsertedId(); // Get the ID of the newly inserted order
        if ($orderQueue->addOrderToQueue($new_order_id)) {
            $_SESSION['message'] = 'Order placed successfully and added to queue!';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Order placed, but failed to add to queue.';
            $_SESSION['message_type'] = 'warning';
        }
    } else {
        $_SESSION['message'] = 'Error placing order.';
        $_SESSION['message_type'] = 'danger';
    }
    header('Location: place_order.php');
    exit();
}

?>

<h1 class="mb-3">Place Your Order (<?php echo ucfirst($time_of_day); ?> Menu)</h1>
<p class="text-start"><a href="index.php" class="back-link"><i class="fas fa-arrow-left me-1"></i> Back to Home</a></p>

<?php if (empty($available_menu_items)): ?>
    <p class="text-center">No menu items available for <?php echo $time_of_day; ?>.</p>
<?php else: ?>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php foreach ($available_menu_items as $item): ?>
            <div class="col">
                <div class="card h-100 shadow-sm menu-item-card">
                    <?php if ($item->image): ?>
                        <img src="<?php echo BASE_URL . '/uploads/' . htmlspecialchars($item->image); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($item->name); ?>">
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo htmlspecialchars($item->name); ?></h5>
                        <p class="card-text flex-grow-1"><?php echo htmlspecialchars($item->description); ?></p>
                        <p class="card-text"><strong>Price: $<?php echo htmlspecialchars(number_format($item->price, 2)); ?></strong></p>
                        <form action="place_order.php" method="POST">
                            <input type="hidden" name="menu_item_id" value="<?php echo htmlspecialchars($item->id); ?>">
                            <button type="submit" name="place_order" class="btn btn-primary w-100">Create Order</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include '../src/includes/footer.php'; ?>