<?php
$conexion = new mysqli("localhost", "root", "", "sistema_it");
if ($conexion->connect_error) die("Error de conexión a la base de datos");

// Guardar fecha de mantenimiento
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['equipo_id'])) {
    $fecha_mantenimiento = $_POST['fecha_mantenimiento'];

    // Verificar si ya existe registro para ese equipo
    $stmt_check = $conexion->prepare("SELECT COUNT(*) FROM mantenimiento_preventivo WHERE equipo_id = ?");
    $stmt_check->bind_param("i", $_POST['equipo_id']);
    $stmt_check->execute();
    $stmt_check->bind_result($count);
    $stmt_check->fetch();
    $stmt_check->close();

    if ($count > 0) {
        // Actualizar
        $stmt = $conexion->prepare("UPDATE mantenimiento_preventivo SET fecha_proximo = ? WHERE equipo_id = ?");
        $stmt->bind_param("si", $fecha_mantenimiento, $_POST['equipo_id']);
    } else {
        // Insertar nuevo
        $stmt = $conexion->prepare("INSERT INTO mantenimiento_preventivo (equipo_id, fecha_proximo) VALUES (?, ?)");
        $stmt->bind_param("is", $_POST['equipo_id'], $fecha_mantenimiento);
    }
    $stmt->execute();
    $stmt->close();

    header("Location: mantenimiento_preventivo.php");
    exit;
}

// Obtener equipos con su próxima fecha de mantenimiento
$query = "
SELECT e.id, e.tipo, e.marca, e.modelo, mp.fecha_proximo
FROM equipos e
LEFT JOIN mantenimiento_preventivo mp ON e.id = mp.equipo_id
ORDER BY e.id
";
$resultado = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mantenimiento Preventivo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h1>Plan de Mantenimiento Preventivo</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Equipo</th>
                <th>Tipo</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Próximo mantenimiento</th>
                <th>Actualizar</th>
            </tr>
        </thead>
        <tbody>
            <?php while($fila = $resultado->fetch_assoc()): ?>
            <tr>
                <form method="POST" action="mantenimiento_preventivo.php">
                    <td><?= $fila['id'] ?></td>
                    <td><?= htmlspecialchars($fila['tipo']) ?></td>
                    <td><?= htmlspecialchars($fila['marca']) ?></td>
                    <td><?= htmlspecialchars($fila['modelo']) ?></td>
                    <td>
                        <input type="date" name="fecha_mantenimiento" value="<?= $fila['fecha_proximo'] ?: '' ?>" required>
                    </td>
                    <td>
                        <input type="hidden" name="equipo_id" value="<?= $fila['id'] ?>">
                        <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
                    </td>
                </form>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
<?php $conexion->close(); ?>
