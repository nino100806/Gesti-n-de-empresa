<?php
$conexion = new mysqli("localhost", "root", "", "sistema_it");
if ($conexion->connect_error) die("Error de conexión a la base de datos");

// Actualizar acceso si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['empleado_id'])) {
    $acceso_internet = isset($_POST['acceso_internet']) ? 1 : 0;
    $acceso_videoconf = isset($_POST['acceso_videoconf']) ? 1 : 0;

    $stmt = $conexion->prepare("UPDATE empleados SET acceso_internet = ?, acceso_videoconf = ? WHERE id = ?");
    $stmt->bind_param("iii", $acceso_internet, $acceso_videoconf, $_POST['empleado_id']);
    $stmt->execute();

    header("Location: control_acceso.php");
    exit;
}

// Obtener lista de empleados
$resultado = $conexion->query("SELECT id, nombre, apellido, acceso_internet, acceso_videoconf FROM empleados");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Control de Acceso a Internet y Videoconferencias</title>
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
            margin-top: 30px;
        }
        table thead {
            background-color: #4a69bd;
            color: white;
        }
        .btn-primary {
            background-color: #4a69bd;
            border: none;
        }
        .btn-primary:hover {
            background-color: #3b5b9b;
        }
        .volver {
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>
<div class="container py-5">
    <h1 class="mb-4">Control de Acceso a Internet y Videoconferencias</h1>

    <div class="table-container">
        <table class="table table-bordered table-hover align-middle">
            <thead>
                <tr>
                    <th>Empleado</th>
                    <th>Acceso a Internet</th>
                    <th>Acceso a Videoconferencias</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <form method="POST" action="control_acceso.php">
                        <td><?= htmlspecialchars($fila['nombre'] . ' ' . $fila['apellido']) ?></td>
                        <td class="text-center">
                            <input type="checkbox" name="acceso_internet" <?= $fila['acceso_internet'] ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" name="acceso_videoconf" <?= $fila['acceso_videoconf'] ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input type="hidden" name="empleado_id" value="<?= $fila['id'] ?>">
                            <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
                        </td>
                    </form>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="volver">
        <a href="index.php" class="btn btn-secondary">← Volver al menú</a>
    </div>
</div>
</body>
</html>
<?php $conexion->close(); ?>

