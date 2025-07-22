<?php
session_start();

require_once '../src/config.php';
require_once '../src/MenuItem.php';

// Redirect to login if not authenticated or not an admin/manager
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager')) {
    header('Location: login.php');
    exit();
}

$menuItem = new MenuItem();
$menu_items = $menuItem->getMenuItems();

// Handle Add/Edit Menu Item
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_menu_item'])) {
        $image_name = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = UPLOAD_DIR;
            // Ensure the uploads directory exists
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $image_name = basename($_FILES['image']['name']);
            $target_file = $target_dir . $image_name;
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $_SESSION['message'] = 'Error uploading image.';
                $_SESSION['message_type'] = 'danger';
                $image_name = null;
            }
        }

        $data = [
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'price' => $_POST['price'],
            'image' => $image_name,
            'time_of_day' => $_POST['time_of_day']
        ];

        if ($menuItem->addMenuItem($data)) {
            $_SESSION['message'] = 'Menu item added successfully.';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Error adding menu item.';
            $_SESSION['message_type'] = 'danger';
        }
    }
    header('Location: manage_menu_items.php');
    exit();
} elseif (isset($_GET['delete'])) {
    if ($menuItem->deleteMenuItem($_GET['delete'])) {
        $_SESSION['message'] = 'Menu item deleted successfully.';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Error deleting menu item.';
        $_SESSION['message_type'] = 'danger';
    }
    header('Location: manage_menu_items.php');
    exit();
}

include '../src/includes/header.php';
?>

<h1 class="mb-3">Manage Menu Items</h1>
<p class="text-start"><a href="<?php echo ($_SESSION['role'] === 'admin') ? 'admin_dashboard.php' : 'manager_dashboard.php'; ?>" class="back-link"><i class="fas fa-arrow-left me-1"></i> Back to Dashboard</a></p>

<h2 class="mb-3">Add New Menu Item</h2>
<form action="manage_menu_items.php" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="name" class="form-label">Name:</label>
        <input type="text" id="name" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description:</label>
        <textarea id="description" name="description" class="form-control"></textarea>
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">Price:</label>
        <input type="number" id="price" name="price" step="0.01" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="image" class="form-label">Image:</label>
        <input type="file" id="image" name="image" accept="image/*" class="form-control">
    </div>
    <div class="mb-3">
        <label for="time_of_day" class="form-label">Time of Day:</label>
        <select id="time_of_day" name="time_of_day" class="form-select">
            <option value="morning">Morning</option>
            <option value="afternoon">Afternoon</option>
            <option value="evening">Evening</option>
        </select>
    </div>
    <button type="submit" name="add_menu_item" class="btn btn-primary">Add Menu Item</button>
</form>

<h2 class="mb-3 mt-4">Existing Menu Items</h2>
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Image</th>
                <th>Time of Day</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($menu_items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item->id); ?></td>
                    <td><?php echo htmlspecialchars($item->name); ?></td>
                    <td><?php echo htmlspecialchars($item->description); ?></td>
                    <td><?php echo htmlspecialchars(number_format($item->price, 2)); ?></td>
                    <td>
                        <?php if ($item->image): ?>
                            <img src="<?php echo BASE_URL . '/uploads/' . htmlspecialchars($item->image); ?>" alt="<?php echo htmlspecialchars($item->name); ?>" width="50" class="img-thumbnail">
                        <?php else: ?>
                            No Image
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($item->time_of_day); ?></td>
                    <td>
                        <a href="edit_menu_item.php?id=<?php echo $item->id; ?>" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                        <a href="manage_menu_items.php?delete=<?php echo $item->id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this menu item?');" title="Delete"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../src/includes/footer.php'; ?>