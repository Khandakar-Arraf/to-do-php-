<?php
$sql = "DELETE FROM tasks WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $taskId, PDO::PARAM_INT);
$stmt->execute();

header('Location: ../tasks.php');


?>