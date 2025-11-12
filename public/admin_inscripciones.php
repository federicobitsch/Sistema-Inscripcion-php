<?php
declare(strict_types=1);
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../lib/Crud.php';

$db = new Database();
$conn = $db->connect();
$crud = new Crud($conn);

// Parámetros de paginación
$limit = 5;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Total de inscripciones
$total = $crud->readOne("SELECT COUNT(*) as total FROM inscripcion")['total'] ?? 0;
$totalPages = max(1, ceil($total / $limit));

// Consulta de inscripciones
// ⚠️ En este caso hacemos el LIMIT/OFFSET directo para evitar problemas con parámetros numéricos
$sql = "
    SELECT i.*, u.nombre 
    FROM inscripcion i 
    JOIN usuario u ON i.usuario_id = u.id
    ORDER BY i.updated_at DESC
    LIMIT $limit OFFSET $offset
";
$inscripciones = $crud->readAll($sql);
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Panel del Administrador – Inscripciones</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f4f6f9;
    padding: 20px;
}
h2 {
    color: #333;
}
table {
    border-collapse: collapse;
    width: 100%;
    background: #fff;
    box-shadow: 0 0 5px rgba(0,0,0,0.1);
}
th {
    background: #3498db;
    color: white;
    padding: 10px;
}
td {
    border: 1px solid #ddd;
    padding: 8px;
}
tr:nth-child(even) { background: #f9f9f9; }
a {
    text-decoration: none;
    color: #3498db;
}
.pagination {
    margin-top: 15px;
    text-align: center;
}
.pagination a {
    padding: 6px 10px;
    border: 1px solid #ccc;
    margin: 2px;
    color: #333;
}
.pagination .active {
    background: #3498db;
    color: white;
    border-color: #2980b9;
}
</style>
</head>
<body>

<h2>Listado de Inscripciones</h2>
<p><a href="inscripcion.php">Volver</a></p>

<table>
<tr>
    <th>ID</th>
    <th>Usuario</th>
    <th>Materia</th>
    <th>Estado</th>
    <th>Fecha actualización</th>
    <th>Acciones</th>
</tr>
<?php if (!empty($inscripciones)): ?>
    <?php foreach ($inscripciones as $ins): ?>
    <tr>
        <td><?= $ins['id'] ?></td>
        <td><?= htmlspecialchars($ins['nombre']) ?></td>
        <td><?= htmlspecialchars($ins['materia']) ?></td>
        <td><?= htmlspecialchars($ins['estado']) ?></td>
        <td><?= htmlspecialchars($ins['updated_at']) ?></td>

            <?php if ($ins['estado'] === 'confirmada'): ?>
                <a href="descargar_comprobante.php?id=<?= $ins['usuario_id'] ?>">📄 PDF</a>
            <?php else: ?>
                <em>Sin confirmar</em>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="6">No hay inscripciones registradas.</td></tr>
<?php endif; ?>
</table>

<div class="pagination">
<?php for($p=1; $p <= $totalPages; $p++): ?>
    <a href="?page=<?= $p ?>" class="<?= $p == $page ? 'active' : '' ?>"><?= $p ?></a>
<?php endfor; ?>
</div>

</body>
</html>
