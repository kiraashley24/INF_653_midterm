<?php
function isValid($id, $model, $conn) {
    $query = 'SELECT id FROM ' . $model . ' WHERE id = ?';
    $stmt = $conn->prepare($query);
    $stmt->bindParam(1, $id);
    $stmt->execute();
    return $stmt->rowCount() > 0;
}
?>
