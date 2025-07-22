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
$users = $user->getUsers();

// Handle Add/Edit User
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_user'])) {
        $data = [
            'username' => $_POST['username'],
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'role' => $_POST['role']
        ];
        if ($user->findUserByUsername($data['username'])) {
            $_SESSION['message'] = 'Username already exists.';
            $_SESSION['message_type'] = 'danger';
        } else if ($user->register($data)) {
            $_SESSION['message'] = 'User added successfully.';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Error adding user.';
            $_SESSION['message_type'] = 'danger';
        }
    }
    header('Location: manage_users.php');
    exit();
} elseif (isset($_GET['delete'])) {
    if ($user->deleteUser($_GET['delete'])) {
        $_SESSION['message'] = 'User deleted successfully.';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Error deleting user.';
        $_SESSION['message_type'] = 'danger';
    }
    header('Location: manage_users.php');
    exit();
}

include '../src/includes/header.php';
?>

<h1 class="mb-3">Manage Users</h1>
<p class="text-start"><a href="admin_dashboard.php" class="back-link"><i class="fas fa-arrow-left me-1"></i> Back to Dashboard</a></p>

<h2 class="mb-3">Add New User</h2>
<form action="manage_users.php" method="POST">
    <div class="mb-3">
        <label for="username" class="form-label">Username:</label>
        <input type="text" id="username" name="username" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password:</label>
        <input type="password" id="password" name="password" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="role" class="form-label">Role:</label>
        <select id="role" name="role" class="form-select">
            <option value="user">User</option>
            <option value="attendant">Attendant</option>
            <option value="manager">Manager</option>
            <option value="admin">Admin</option>
        </select>
    </div>
    <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
</form>

<h2 class="mb-3 mt-4">Existing Users</h2>
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user_data): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user_data->id); ?></td>
                    <td><?php echo htmlspecialchars($user_data->username); ?></td>
                    <td><?php echo htmlspecialchars($user_data->role); ?></td>
                    <td>
                        <a href="edit_user.php?id=<?php echo $user_data->id; ?>" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                        <a href="manage_users.php?delete=<?php echo $user_data->id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?');" title="Delete"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../src/includes/footer.php'; ?>