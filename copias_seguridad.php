<?php
$conexion = new mysqli("localhost", "root", "", "sistema_it");
if ($conexion->connect_error) die("Error de conexión a la base de datos");

// Registrar nuevo backup
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['equipo_id'])) {
    $fecha = $_POST['fecha'];
    $frecuencia = $_POST['frecuencia'];
    $estado = $_POST['estado'];
    $observaciones = $_POST['observaciones'];

    $stmt = $conexion->prepare("INSERT INTO backups (equipo_id, fecha, frecuencia, estado, observaciones) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $_POST['equipo_id'], $fecha, $frecuencia, $estado, $observaciones);
    $stmt->execute();
    $stmt->close();

    header("Location: copias_seguridad.php");
    exit;
}

// Obtener lista de equipos
$query = "
SELECT e.id, e.tipo, e.marca, e.modelo,
(SELECT fecha FROM backups b WHERE b.equipo_id = e.id ORDER BY fecha DESC LIMIT 1) AS ultima_fecha,
(SELECT estado FROM backups b WHERE b.equipo_id = e.id ORDER BY fecha DESC LIMIT 1) AS ultimo_estado,
(SELECT frecuencia FROM backups b WHERE b.equipo_id = e.id ORDER BY fecha DESC LIMIT 1) AS ultima_frecuencia,
(SELECT observaciones FROM backups b WHERE b.equipo_id = e.id ORDER BY fecha DESC LIMIT 1) AS ultima_observacion
FROM equipos e
WHERE e.tipo = 'servidor'
ORDER BY e.id
";
$resultado = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Plan de Copias de Seguridad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f7fa;
            font-family: 'Segoe UI', sans-serif;
        }
        h1 {
            color: #34495e;
        }
        .table-container {
            background: #ffffff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background-color: #4a69bd;
            border: none;
        }
        .btn-primary:hover {
            background-color: #3b5b9b;
        }
        .volver {
            margin-top: 40px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container py-5">
    <h1 class="mb-4">Plan de Copias de Seguridad</h1>

    <div class="table-container">
        <?php while($fila = $resultado->fetch_assoc()): ?>
            <form method="POST" action="copias_seguridad.php" class="mb-4 p-3 border rounded shadow-sm bg-light">
                <h5>Equipo ID <?= $fila['id'] ?> - <?= htmlspecialchars($fila['marca']) ?> <?= htmlspecialchars($fila['modelo']) ?></h5>

                <div class="row g-3">
                    <div class="col-md-3">
                        <label>Última copia:</label>
                        <input type="text" class="form-control" value="<?= $fila['ultima_fecha'] ?: 'N/A' ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label>Frecuencia anterior:</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($fila['ultima_frecuencia']) ?: 'N/A' ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label>Estado anterior:</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($fila['ultimo_estado']) ?: 'N/A' ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label>Observación anterior:</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($fila['ultima_observacion']) ?: 'N/A' ?>" readonly>
                    </div>

                    <div class="col-md-3">
                        <label>Fecha nueva copia:</label>
                        <input type="date" name="fecha" required class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Frecuencia:</label>
                        <select name="frecuencia" required class="form-select">
                            <option value="Diaria">Diaria</option>
                            <option value="Semanal">Semanal</option>
                            <option value="Mensual">Mensual</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Estado:</label>
                        <select name="estado" required class="form-select">
                            <option value="Exitoso">Exitoso</option>
                            <option value="Fallido">Fallido</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Observaciones:</label>
                        <input type="text" name="observaciones" class="form-control" placeholder="Observaciones">
                    </div>
                </div>

                <input type="hidden" name="equipo_id" value="<?= $fila['id'] ?>">

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary px-5">Guardar Copia de Seguridad</button>
                </div>
            </form>
        <?php endwhile; ?>
    </div>

    <div class="volver">
        <a href="index.php" class="btn btn-secondary btn-lg">← Volver al menú</a>
    </div>
</div>
</body>
</html>

<?php $conexion->close(); ?>
