<?php
$conexion = new mysqli("localhost", "root", "", "sistema_it");
if ($conexion->connect_error) die("Error de conexión a la base de datos");

// Cargar listado de equipos para el formulario
$equipos = $conexion->query("SELECT id, marca, modelo FROM equipos");

// Procesar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conexion->prepare("INSERT INTO backups (equipo_id, fecha, frecuencia, estado, observaciones) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss",
        $_POST['equipo_id'], $_POST['fecha'], $_POST['frecuencia'], $_POST['estado'], $_POST['observaciones']);
    $stmt->execute();

    header("Location: backups.php");
    exit;
}

// Obtener backups existentes
$resultado = $conexion->query("SELECT b.id, e.marca, e.modelo, b.fecha, b.frecuencia, b.estado, b.observaciones 
FROM backups b 
JOIN equipos e ON b.equipo_id = e.id");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Backups</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h1>Gestión de Backups</h1>

    <form method="POST" class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Equipo</label>
            <select name="equipo_id" class="form-select" required>
                <option value="">Seleccione un equipo</option>
                <?php while($eq = $equipos->fetch_assoc()): ?>
                    <option value="<?= $eq['id'] ?>">
                        <?= $eq['marca'] . " " . $eq['modelo'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Fecha de Backup</label>
            <input type="date" name="fecha" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Frecuencia</label>
            <select name="frecuencia" class="form-select" required>
                <option value="diario">Diario</option>
                <option value="semanal">Semanal</option>
                <option value="mensual">Mensual</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Estado</label>
            <select name="estado" class="form-select" required>
                <option value="exitoso">Exitoso</option>
                <option value="fallido">Fallido</option>
            </select>
        </div>
        <div class="col-md-12">
            <label class="form-label">Observaciones</label>
            <input type="text" name="observaciones" class="form-control">
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Registrar Backup</button>
        </div>
    </form>

    <hr class="my-5">
    <h2>Historial de Backups</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th><th>Equipo</th><th>Fecha</th><th>Frecuencia</th><th>Estado</th><th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while($fila = $resultado->fetch_assoc()): ?>
            <tr>
                <td><?= $fila['id'] ?></td>
                <td><?= $fila['marca'] . " " . $fila['modelo'] ?></td>
                <td><?= $fila['fecha'] ?></td>
                <td><?= ucfirst($fila['frecuencia']) ?></td>
                <td><?= ucfirst($fila['estado']) ?></td>
                <td><?= $fila['observaciones'] ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
<?php $conexion->close(); ?>
