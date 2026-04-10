<?php

header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'student_registration_db';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

$conn->select_db($dbname);

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(['success' => false, 'error' => 'Student ID required']);
    exit;
}

$id = intval($_GET['id']);


$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

if (!$student) {
    echo json_encode(['success' => false, 'error' => 'Student not found']);
    exit;
}


$qual_stmt = $conn->prepare("SELECT examination, board, percentage, year_of_passing FROM qualifications WHERE student_id = ?");
$qual_stmt->bind_param("i", $id);
$qual_stmt->execute();
$qualifications = $qual_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode([
    'success' => true,
    'student' => $student,
    'qualifications' => $qualifications
]);

$conn->close();
?>