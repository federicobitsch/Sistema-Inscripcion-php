<?php
declare(strict_types=1);
session_start();
if (!isset($_SESSION['user_id'])) {
    die("Debe iniciar sesión");
}

date_default_timezone_set('America/Argentina/Buenos_Aires');

require_once __DIR__ . '/../vendor/fpdf186/fpdf.php';
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../src/models/User.php';
require_once __DIR__ . '/../src/models/Inscripcion.php';

$db = new Database();
$conn = $db->connect();
$userModel = new User($conn);
$insModel  = new Inscripcion($conn);

$id = intval($_SESSION['user_id']);
$usuario = $userModel->findById($id);
$inscripcion = $insModel->obtenerPorUsuario($id);

if (!$usuario || !$inscripcion) {
    die("No hay inscripción confirmada.");
}

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Comprobante de Inscripcion',0,1,'C');
$pdf->Ln(10);

$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,'Nombre: '.$usuario['nombre'],0,1);
$pdf->Cell(0,10,'Materia: '.$inscripcion['materia'],0,1);
$pdf->Cell(0,10,'Estado: '.$inscripcion['estado'],0,1);
$pdf->Cell(0, 10, 'Fecha: ' . date('Y-m-d H:i:s'), 0, 1);
$pdf->Ln(10);
$pdf->Cell(0,10,'Numero de inscripcion: '.$inscripcion['id'],0,1);
$pdf->Output('I','comprobante_inscripcion.pdf');

?>
