<?php
require_once '../src/config.php';
include '../src/includes/header.php';
?>

<h1 class="mt-4">Dashboard</h1>
<p class="lead">Welcome to your dashboard, <?php echo htmlspecialchars($username); ?>!</p>

<div class="row">
    <div class="col-lg-12">
        <div class="card mb-4">
            <div class="card-header">Quick Actions</div>
            <div class="card-body">
                <div class="d-grid gap-2 d-md-flex justify-content-center">
                    <?php if ($role === 'admin'): ?>
                        <a href="admin_dashboard.php" class="btn btn-primary btn-lg">Admin Dashboard</a>
                    <?php elseif ($role === 'manager'): ?>
                        <a href="manager_dashboard.php" class="btn btn-primary btn-lg">Manager Dashboard</a>
                    <?php elseif ($role === 'attendant'): ?>
                        <a href="attendant_dashboard.php" class="btn btn-primary btn-lg">Attendant Dashboard</a>
                    <?php endif; ?>
                    <a href="place_order.php" class="btn btn-success btn-lg">Place New Order</a>
                    <a href="view_orders.php" class="btn btn-info btn-lg">View My Orders</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../src/includes/footer.php'; ?>