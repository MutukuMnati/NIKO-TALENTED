<?php
$data = json_decode(file_get_contents('php://input'), true);

// Log the data or process it as needed
file_put_contents('validation_log.txt', print_r($data, true), FILE_APPEND);

header('Content-Type: application/json');
echo json_encode(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
?>
