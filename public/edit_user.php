<?php
session_start();

require_once '../src/config.php';
require_once '../src/User.php';

// Redirect to login if not authenticated or not an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$user = new User();

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $user_data = $user->getUserById($user_id);

    if (!$user_data) {
        $_SESSION['message'] = 'User not found.';
        $_SESSION['message_type'] = 'danger';
        header('Location: manage_users.php');
        exit();
    }
} else {
    header('Location: manage_users.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'id' => $_POST['user_id'],
        'username' => $_POST['username'],
        'role' => $_POST['role']
    ];

    if ($user->updateUser($data)) {
        $_SESSION['message'] = 'User updated successfully.';
        $_SESSION['message_type'] = 'success';
        header('Location: manage_users.php');
        exit();
    } else {
        $_SESSION['message'] = 'Error updating user.';
        $_SESSION['message_type'] = 'danger';
        header('Location: manage_users.php');
        exit();
    }
}

include '../src/includes/header.php';
?>

<h1 class="mt-4">Edit User</h1>
<p class="text-center"><a href="manage_users.php" class="btn btn-secondary">Back to Manage Users</a></p>

<div class="card">
    <div class="card-body">
        <form action="edit_user.php?id=<?php echo htmlspecialchars($user_data->id); ?>" method="POST">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_data->id); ?>">
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user_data->username); ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role:</label>
                <select id="role" name="role" class="form-select">
                    <option value="user" <?php echo ($user_data->role == 'user') ? 'selected' : ''; ?>>User</option>
                    <option value="attendant" <?php echo ($user_data->role == 'attendant') ? 'selected' : ''; ?>>Attendant</option>
                    <option value="manager" <?php echo ($user_data->role == 'manager') ? 'selected' : ''; ?>>Manager</option>
                    <option value="admin" <?php echo ($user_data->role == 'admin') ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Update User</button>
            </div>
        </form>
    </div>
</div>

<?php include '../src/includes/footer.php'; ?>