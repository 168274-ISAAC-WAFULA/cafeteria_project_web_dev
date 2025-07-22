<!-- Sidebar-->
<div id="sidebar-wrapper">
    <div class="sidebar-heading">Cafeteria App</div>
    <div class="list-group list-group-flush">
        <a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?php echo BASE_URL; ?>/public/index.php"><i class="fas fa-home me-2"></i>Home</a>
        <a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?php echo BASE_URL; ?>/public/place_order.php"><i class="fas fa-utensils me-2"></i>Place Order</a>
        <a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?php echo BASE_URL; ?>/public/view_orders.php"><i class="fas fa-receipt me-2"></i>My Orders</a>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?php echo BASE_URL; ?>/public/admin_dashboard.php"><i class="fas fa-user-shield me-2"></i>Admin Dashboard</a>
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?php echo BASE_URL; ?>/public/manage_users.php"><i class="fas fa-users me-2"></i>Manage Users</a>
        <?php endif; ?>

        <?php if (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'manager')): ?>
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?php echo BASE_URL; ?>/public/manage_menu_items.php"><i class="fas fa-clipboard-list me-2"></i>Manage Menu Items</a>
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?php echo BASE_URL; ?>/public/manage_orders.php"><i class="fas fa-cash-register me-2"></i>Manage Orders</a>
        <?php endif; ?>

        <?php if (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'manager' || $_SESSION['role'] === 'attendant')): ?>
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?php echo BASE_URL; ?>/public/manage_order_queue.php"><i class="fas fa-hourglass-half me-2"></i>Manage Order Queue</a>
        <?php endif; ?>

        <a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?php echo BASE_URL; ?>/public/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
    </div>
</div>