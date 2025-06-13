<?php
$conexion = new mysqli("localhost", "root", "", "sistema_it");
if ($conexion->connect_error) die("Error de conexión a la base de datos");

// Traer empleados para el select
$empleados_resultado = $conexion->query("SELECT id, nombre FROM empleados");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conexion->prepare("INSERT INTO equipos (tipo, marca, modelo, procesador, ram_gb, almacenamiento_gb, sistema_operativo, empleado_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssiisi",
        $_POST['tipo'], $_POST['marca'], $_POST['modelo'], $_POST['procesador'],
        $_POST['ram_gb'], $_POST['almacenamiento_gb'], $_POST['sistema_operativo'], $_POST['empleado_id']);
    $stmt->execute();

    header("Location: equipos.php");
    exit;
}

// Traer equipos junto con el nombre del empleado asignado
$resultado = $conexion->query("
    SELECT equipos.*, empleados.nombre AS empleado_nombre 
    FROM equipos 
    LEFT JOIN empleados ON equipos.empleado_id = empleados.id
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Equipos</title>
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
    </style>
</head>
<body>
<div class="container py-5">
    <h1 class="mb-4">Gestión de Equipos</h1>

    <div class="form-container">
        <form method="POST" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Tipo de equipo</label>
                <select name="tipo" class="form-select" required>
                    <option value="PC">PC</option>
                    <option value="notebook">Notebook</option>
                    <option value="servidor">Servidor</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Marca</label>
                <input type="text" name="marca" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Modelo</label>
                <input type="text" name="modelo" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Procesador</label>
                <input type="text" name="procesador" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">RAM (GB)</label>
                <input type="number" name="ram_gb" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Almacenamiento (GB)</label>
                <input type="number" name="almacenamiento_gb" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Sistema Operativo</label>
                <input type="text" name="sistema_operativo" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Empleado asignado</label>
                <select name="empleado_id" class="form-select" required>
                    <option value="">Seleccione un empleado</option>
                    <?php while($empleado = $empleados_resultado->fetch_assoc()): ?>
                        <option value="<?= $empleado['id'] ?>"><?= htmlspecialchars($empleado['nombre']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>

    <div class="table-container">
        <h2>Listado de Equipos</h2><br>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th><th>Tipo</th><th>Marca</th><th>Modelo</th><th>Procesador</th><th>RAM</th><th>Almacenamiento</th><th>SO</th><th>Empleado asignado</th>
                </tr>
            </thead>
            <tbody>
                <?php while($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= $fila['id'] ?></td>
                    <td><?= $fila['tipo'] ?></td>
                    <td><?= $fila['marca'] ?></td>
                    <td><?= $fila['modelo'] ?></td>
                    <td><?= $fila['procesador'] ?></td>
                    <td><?= $fila['ram_gb'] ?> GB</td>
                    <td><?= $fila['almacenamiento_gb'] ?> GB</td>
                    <td><?= $fila['sistema_operativo'] ?></td>
                    <td><?= htmlspecialchars($fila['empleado_nombre'] ?? 'Sin asignar') ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Botón volver abajo -->
    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-secondary btn-lg">← Volver al menú</a>
    </div>
</div>
</body>
</html>
<?php $conexion->close(); ?>
