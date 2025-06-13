<?php
$conexion = new mysqli("localhost", "root", "", "sistema_it");
if ($conexion->connect_error) die("Error de conexión a la base de datos");

// Obtener lista de equipos
$equipos = $conexion->query("SELECT id, marca, modelo FROM equipos");

// Si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conexion->prepare("INSERT INTO renovaciones (equipo_id, fecha_renovacion, estado, observaciones) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss",
        $_POST['equipo_id'],
        $_POST['fecha_renovacion'],
        $_POST['estado'],
        $_POST['observaciones']
    );
    $stmt->execute();

    header("Location: renovacion.php");
    exit;
}

// Consultar renovaciones
$resultado = $conexion->query("
    SELECT r.id, e.marca, e.modelo, r.fecha_renovacion, r.estado, r.observaciones 
    FROM renovaciones r
    JOIN equipos e ON r.equipo_id = e.id
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Plan de Renovación de Equipos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f7fa;
            font-family: 'Segoe UI', sans-serif;
        }
        h1, h2 {
            color: #34495e;
        }
        .form-container, .table-container {
            background: #ffffff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-bottom: 40px;
        }
        .btn-primary {
            background-color: #4a69bd;
            border: none;
        }
        .btn-primary:hover {
            background-color: #3b5b9b;
        }
        table thead {
            background-color: #4a69bd;
            color: white;
        }
        .volver {
            text-align: center;
            margin-top: 40px;
        }
    </style>
</head>
<body>
<div class="container py-5">
    <h1 class="mb-4">Plan de Renovación de Equipos</h1>

    <div class="form-container">
        <form method="POST" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Equipo</label>
                <select name="equipo_id" class="form-select" required>
                    <option value="">Seleccione un equipo</option>
                    <?php while($eq = $equipos->fetch_assoc()): ?>
                        <option value="<?= $eq['id'] ?>">
                            <?= $eq['marca'] . ' ' . $eq['modelo'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Fecha de renovación</label>
                <input type="date" name="fecha_renovacion" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-select" required>
                    <option value="Pendiente">Pendiente</option>
                    <option value="En Proceso">En Proceso</option>
                    <option value="Completado">Completado</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Observaciones</label>
                <textarea name="observaciones" class="form-control"></textarea>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Registrar</button>
            </div>
        </form>
    </div>

    <div class="table-container">
        <h2 class="mb-3">Listado de Renovaciones</h2>
        <table class="table table-bordered table-hover align-middle">
            <thead>
                <tr>
                    <th>ID</th><th>Equipo</th><th>Fecha</th><th>Estado</th><th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= $fila['id'] ?></td>
                    <td><?= $fila['marca'] ?> <?= $fila['modelo'] ?></td>
                    <td><?= $fila['fecha_renovacion'] ?></td>
                    <td><?= $fila['estado'] ?></td>
                    <td><?= $fila['observaciones'] ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="volver">
        <a href="index.php" class="btn btn-secondary btn-lg">← Volver al menú</a>
    </div>
</div>
</body>
</html>
<?php $conexion->close(); ?>
