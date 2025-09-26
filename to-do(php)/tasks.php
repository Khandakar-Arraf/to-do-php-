<?php
// tasks.php - Task management dashboard

session_start();
require 'backend/config.php';
require 'backend/functions.php';





// Handle add task
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $title = validateInput($_POST['title']);
    if (!empty($title)) {
        $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title) VALUES (:user_id, :title)");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->bindParam(':title', $title);
        $stmt->execute();
    }
}

// Handle edit task
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') {
    $task_id = (int)$_POST['task_id'];
    $title = validateInput($_POST['title']);
    if (!empty($title)) {
        $stmt = $pdo->prepare("UPDATE tasks SET title = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$title, $task_id, $_SESSION["user_id"]]);
    }
}

// Handle delete task
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $task_id = (int)$_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->execute([$task_id, $_SESSION["user_id"]]);
    redirect('tasks.php');
}

// Handle toggle complete
if (isset($_GET['action']) && $_GET['action'] == 'toggle' && isset($_GET['id'])) {
    $task_id = (int)$_GET['id'];
    $stmt = $pdo->prepare("UPDATE tasks SET completed = NOT completed WHERE id = ? AND user_id = ?");
    $stmt->execute([$task_id, $user_id]);
    redirect('tasks.php');
}

// Fetch tasks
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bindParam(1, $_SESSION["user_id"]);
$stmt->execute();
$tasks = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Task Manager</h2>
    <a href="backend/logout.php" class="btn btn-secondary mb-3">Logout</a>

    <!-- Add Task Form -->
    <form method="POST" class="mb-4">
        <input type="hidden" name="action" value="add">
        <div class="input-group">
            <input type="text" name="title" class="form-control" placeholder="New task..." required>
            <button type="submit" class="btn btn-primary">Add</button>
        </div>
    </form>

    <!-- Task List -->
    <ul class="list-group">
        <?php foreach ($tasks as $task): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span <?php if ($task['completed']): ?>style="text-decoration: line-through;"<?php endif; ?>>
                    <?php echo htmlspecialchars($task['title']); ?>
                </span>
                <div>
                    <a href="tasks.php?action=toggle&id=<?php echo $task['id']; ?>" class="btn btn-sm btn-success">
                        <?php echo $task['completed'] ? 'Undo' : 'Complete'; ?>
                    </a>
                    <!-- Edit Modal Trigger -->
                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $task['id']; ?>">Edit</button>
                    <a href="tasks.php?action=delete&id=<?php echo $task['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
                </div>
            </li>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal<?php echo $task['id']; ?>">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Task</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($task['title']); ?>" required>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </ul>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>