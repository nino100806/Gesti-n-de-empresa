<?php
$conexion = new mysqli("localhost", "root", "", "sistema_it");
if ($conexion->connect_error) die("Error de conexión a la base de datos");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conexion->prepare("INSERT INTO empleados (nombre, apellido, cargo, email, telefono) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss",
        $_POST['nombre'], $_POST['apellido'], $_POST['cargo'], $_POST['email'], $_POST['telefono']);
    $stmt->execute();
}

$resultado = $conexion->query("SELECT * FROM empleados");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Empleados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }
        .container {
            max-width: 1100px;
            padding: 40px 20px;
        }
        h1, h2 {
            color: #2d3748;
            font-weight: 600;
        }
        .card {
            background-color: #ffffff;
            border: none;
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
            padding: 30px;
            margin-bottom: 40px;
        }
        .form-control, .form-select {
            border-radius: 10px;
            padding: 10px 15px;
        }
        .btn {
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 500;
        }
        .table {
            border-radius: 10px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        thead {
            background-color: #2d3748;
            color: #fff;
        }
    </style>
</head>
<body>
<div class="container">

    <div class="card">
        <h1>Gestión de Empleados</h1>
        <form method="POST" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Apellido</label>
                <input type="text" name="apellido" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Cargo</label>
                <input type="text" name="cargo" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control" required>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Guardar Empleado</button>
            </div>
        </form>
    </div>

    <div class="card">
        <h2>Listado de Empleados</h2>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Cargo</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                </tr>
            </thead>
            <tbody>
                <?php while($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= $fila['id'] ?></td>
                    <td><?= htmlspecialchars($fila['nombre']) ?></td>
                    <td><?= htmlspecialchars($fila['apellido']) ?></td>
                    <td><?= htmlspecialchars($fila['cargo']) ?></td>
                    <td><?= htmlspecialchars($fila['email']) ?></td>
                    <td><?= htmlspecialchars($fila['telefono']) ?></td>
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
