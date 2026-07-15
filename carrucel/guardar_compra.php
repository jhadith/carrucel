<?php
require_once 'servicios.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$cedula = isset($_POST['cedula']) ? trim($_POST['cedula']) : '';
$total = isset($_POST['total']) ? trim($_POST['total']) : '';

if (!preg_match('/^[0-9]{10}$/', $cedula) || !is_numeric($total) || floatval($total) <= 0) {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
    exit;
}

$id = servicios::insertar($cedula, floatval($total));
if ($id === false) {
    echo json_encode(['success' => false, 'message' => 'Error al guardar la compra']);
    exit;
}

echo json_encode(['success' => true, 'id' => $id]);
