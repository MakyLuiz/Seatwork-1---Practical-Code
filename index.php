<?php
session_start();

$todoList = isset($_SESSION["todoList"]) ? $_SESSION["todoList"] : [];

function appendData($data, $todoList) {
    $todoList[] = ['task' => $data, 'completed' => false];
    return $todoList;
}

function deleteData($toDelete, $todoList) {
    foreach ($todoList as $index => $taskData) {
        if ($taskData['task'] === $toDelete) {
            unset($todoList[$index]);
        }
    }
    return array_values($todoList); // Reindex array
}

function updateTaskStatus($taskToUpdate, $todoList) {
    foreach ($todoList as &$taskData) {
        if ($taskData['task'] === $taskToUpdate) {
            $taskData['completed'] = !$taskData['completed'];
        }
    }
    return $todoList;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["task"])) {
        echo '<script>alert("Error: there is no data to add in array")</script>';
        exit;
    }

    $todoList = appendData($_POST["task"], $todoList);
    $_SESSION["todoList"] = $todoList;
}

if (isset($_GET['task'])) {
    if (isset($_GET['delete']) && $_GET['delete'] == 'true') {
        $todoList = deleteData($_GET['task'], $todoList);
    } elseif (isset($_GET['toggle']) && $_GET['toggle'] == 'true') {
        $todoList = updateTaskStatus($_GET['task'], $todoList);
    }
    $_SESSION["todoList"] = $todoList;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple To-Do List</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-image: linear-gradient(to right, #FC466B , #3F5EFB);">
    <div class="container mt-5">
        <h1 class="text-center" style="color: #000000; padding: 10px; background-color: #FFFFFF; border-radius: 50px;">To-Do List</h1>
        <div class="card" style="border-radius: 25px;">
            <div class="card-header">Add a new task</div>
            <div class="card-body">
                <form method="post" action="">
                    <div class="form-group">
                        <input type="text" class="form-control" name="task" placeholder="Enter your task here">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Task</button>
                </form>
            </div>
        </div>

        <div class="card mt-4" style="border-radius: 10px;">
            <div class="card-header" style="border-radius: 10px;">Tasks</div>
            <ul class="list-group list-group-flush">
                <?php
                foreach ($todoList as $taskData) {
                    $task = htmlspecialchars($taskData['task']);
                    $completed = $taskData['completed'] ? 'checked' : '';
                    $taskStyle = $taskData['completed'] ? 'text-decoration: line-through;' : '';
                    echo '<div class="d-flex p-2 bd-highlight w-100 justify-content-between align-items-center">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="check' . $task . '" ' . $completed . ' onchange="window.location.href=\'index.php?toggle=true&task=' . urlencode($task) . '\'">
                                <label class="custom-control-label" for="check' . $task . '" style="' . $taskStyle . '">' . $task . '</label>
                            </div>
                            <a href="index.php?delete=true&task=' . urlencode($task) . '" class="btn btn-danger">Delete</a>
                          </div>';
                }
                ?>
            </ul>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
