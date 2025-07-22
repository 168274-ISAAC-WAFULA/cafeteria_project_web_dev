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

if (isset($_GET['id'])) {
    $item_id = $_GET['id'];
    $item_data = $menuItem->getMenuItemById($item_id);

    if (!$item_data) {
        $_SESSION['message'] = 'Menu item not found.';
        $_SESSION['message_type'] = 'danger';
        header('Location: manage_menu_items.php');
        exit();
    }
} else {
    header('Location: manage_menu_items.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $image_name = $_POST['current_image']; // Keep current image if no new one uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = UPLOAD_DIR;
        // Ensure the uploads directory exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $image_name = basename($_FILES['image']['name']);
        $target_file = $target_dir . $image_name;
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $_SESSION['message'] = 'Error uploading new image.';
            $_SESSION['message_type'] = 'danger';
            $image_name = $_POST['current_image']; // Revert to current image on upload failure
        }
    }

    $data = [
        'id' => $_POST['menu_item_id'],
        'name' => $_POST['name'],
        'description' => $_POST['description'],
        'price' => $_POST['price'],
        'image' => $image_name,
        'time_of_day' => $_POST['time_of_day']
    ];

    if ($menuItem->updateMenuItem($data)) {
        $_SESSION['message'] = 'Menu item updated successfully.';
        $_SESSION['message_type'] = 'success';
        header('Location: manage_menu_items.php');
        exit();
    } else {
        $_SESSION['message'] = 'Error updating menu item.';
        $_SESSION['message_type'] = 'danger';
        header('Location: manage_menu_items.php');
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4 text-center">Edit Menu Item</h1>
        <p class="text-center"><a href="manage_menu_items.php" class="btn btn-secondary">Back to Manage Menu Items</a></p>

        <div class="card">
            <div class="card-body">
                <form action="edit_menu_item.php?id=<?php echo htmlspecialchars($item_data->id); ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="menu_item_id" value="<?php echo htmlspecialchars($item_data->id); ?>">
                    <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($item_data->image); ?>">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name:</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($item_data->name); ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description:</label>
                        <textarea id="description" name="description" class="form-control"><?php echo htmlspecialchars($item_data->description); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price:</label>
                        <input type="number" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($item_data->price); ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Current Image:</label>
                        <?php if ($item_data->image): ?>
                            <img src="<?php echo BASE_URL . '/uploads/' . htmlspecialchars($item_data->image); ?>" alt="<?php echo htmlspecialchars($item->name); ?>" width="100" class="img-thumbnail mb-2">
                            <br>
                        <?php else: ?>
                            No Image
                            <br>
                        <?php endif; ?>
                        <label for="image" class="form-label">Upload New Image (optional):</label>
                        <input type="file" id="image" name="image" accept="image/*" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="time_of_day" class="form-label">Time of Day:</label>
                        <select id="time_of_day" name="time_of_day" class="form-select">
                            <option value="morning" <?php echo ($item_data->time_of_day == 'morning') ? 'selected' : ''; ?>>Morning</option>
                            <option value="afternoon" <?php echo ($item_data->time_of_day == 'afternoon') ? 'selected' : ''; ?>>Afternoon</option>
                            <option value="evening" <?php echo ($item_data->time_of_day == 'evening') ? 'selected' : ''; ?>>Evening</option>
                        </select>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Update Menu Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
</body>
</html>
