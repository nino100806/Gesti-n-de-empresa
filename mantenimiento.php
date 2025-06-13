<?php
$conexion = new mysqli("localhost", "root", "", "sistema_it");
if ($conexion->connect_error) die("Error de conexión: " . $conexion->connect_error);

// Insertar mantenimiento
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_equipos'])) {
    $id_equipos = $_POST['id_equipos'];
    $stmt = $conexion->prepare("INSERT INTO mantenimientos (id_equipos, anio_fabricacion, fecha_ultimo_mantenimiento, observaciones, fecha_proximo_mantenimiento) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss",
        $id_equipos,
        $_POST['anio_fabricacion'],
        $_POST['fecha_ultimo_mantenimiento'],
        $_POST['observaciones'],
        $_POST['fecha_proximo_mantenimiento']);
    $stmt->execute();
}

// Equipos para formularios
$equipos = $conexion->query("SELECT id, marca, modelo FROM equipos");
$ids_resultado = $conexion->query("SELECT id FROM equipos ORDER BY id ASC");
$lista_ids = $conexion->query("SELECT id, marca, modelo FROM equipos ORDER BY id ASC") or die("Error en la consulta de lista_ids: " . $conexion->error);

// Buscar equipo por ID
$datos_equipo = null;
if (isset($_GET['buscar_id'])) {
    $id_buscado = (int) $_GET['buscar_id'];
    $stmt = $conexion->prepare("SELECT * FROM equipos WHERE id = ?");
    $stmt->bind_param("i", $id_buscado);
    $stmt->execute();
    $resultado_busqueda = $stmt->get_result();
    $datos_equipo = $resultado_busqueda->fetch_assoc();
}

// Mantenimientos + info de equipos
$mantenimientos = $conexion->query("
    SELECT m.*, e.marca, e.modelo
    FROM mantenimientos m
    JOIN equipos e ON m.id_equipos = e.id
    ORDER BY m.id_mantenimiento DESC
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Mantenimientos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f1f5f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }
        .container {
            max-width: 1100px;
            padding: 40px 20px;
        }
        h1, h2, h3 {
            color: #2d3748;
            font-weight: 600;
        }
        .card {
            background-color: #ffffff;
            border: none;
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 40px;
        }
        .list-group-item {
            background-color: #f7fafc;
            border: none;
            border-radius: 8px;
            margin-bottom: 8px;
        }
        .form-control, .form-select {
            border-radius: 10px;
            padding: 10px 15px;
        }
        .btn-primary, .btn-success {
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
        <h1>Gestión de Mantenimientos</h1>

        <h3 class="mb-3">Lista de Equipos Disponibles</h3>
        <ul class="list-group">
            <?php while ($e = $lista_ids->fetch_assoc()): ?>
                <li class="list-group-item">
                    ID <?= $e['id'] ?> — <?= $e['marca'] ?> <?= $e['modelo'] ?>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>

    <div class="card">
        <h2>Buscar equipo por ID</h2>
        <form method="GET" class="row g-3 align-items-end mb-4">
            <div class="col-md-4">
                <label for="buscar_id" class="form-label">ID del equipo</label>
                <select name="buscar_id" id="buscar_id" class="form-select" required>
                    <option value="">-- Seleccionar ID --</option>
                    <?php while ($id_row = $ids_resultado->fetch_assoc()): ?>
                        <option value="<?= $id_row['id'] ?>" <?= (isset($_GET['buscar_id']) && $_GET['buscar_id'] == $id_row['id']) ? 'selected' : '' ?>>
                            <?= $id_row['id'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Buscar equipo</button>
            </div>
        </form>

        <?php if ($datos_equipo): ?>
            <div class="alert alert-secondary">
                <h5>Datos del equipo #<?= $datos_equipo['id'] ?></h5>
                <ul class="list-group">
                    <li class="list-group-item"><strong>Marca:</strong> <?= $datos_equipo['marca'] ?></li>
                    <li class="list-group-item"><strong>Modelo:</strong> <?= $datos_equipo['modelo'] ?></li>
                    <li class="list-group-item"><strong>Procesador:</strong> <?= $datos_equipo['procesador'] ?></li>
                    <li class="list-group-item"><strong>RAM:</strong> <?= $datos_equipo['ram_gb'] ?> GB</li>
                    <li class="list-group-item"><strong>Almacenamiento:</strong> <?= $datos_equipo['almacenamiento_gb'] ?> GB</li>
                    <li class="list-group-item"><strong>Sistema Operativo:</strong> <?= $datos_equipo['sistema_operativo'] ?></li>
                </ul>
            </div>
        <?php elseif (isset($_GET['buscar_id'])): ?>
            <div class="alert alert-danger">No se encontró ningún equipo con ese ID.</div>
        <?php endif; ?>
    </div>

    <div class="card">
        <h2>Registrar nuevo mantenimiento</h2>
        <form method="POST" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Equipo</label>
                <select name="id_equipos" class="form-select" required>
                    <?php mysqli_data_seek($equipos, 0); while ($eq = $equipos->fetch_assoc()): ?>
                        <option value="<?= $eq['id'] ?>">
                            <?= $eq['id'] ?> - <?= $eq['marca'] . ' ' . $eq['modelo'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Año fabricación</label>
                <input type="number" name="anio_fabricacion" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Último mant.</label>
                <input type="date" name="fecha_ultimo_mantenimiento" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Próximo mant.</label>
                <input type="date" name="fecha_proximo_mantenimiento" class="form-control" required>
            </div>
            <div class="col-md-12">
                <label class="form-label">Observaciones</label>
                <textarea name="observaciones" class="form-control" rows="2"></textarea>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-success">Guardar</button>
            </div>
        </form>
    </div>

    <div class="card">
        <h2>Historial de Mantenimientos</h2>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Equipo</th>
                    <th>Año Fab.</th>
                    <th>Último</th>
                    <th>Próximo</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($m = $mantenimientos->fetch_assoc()): ?>
                <tr>
                    <td><?= $m['id_mantenimiento'] ?></td>
                    <td><?= $m['marca'] . ' ' . $m['modelo'] ?></td>
                    <td><?= $m['anio_fabricacion'] ?></td>
                    <td><?= $m['fecha_ultimo_mantenimiento'] ?></td>
                    <td><?= $m['fecha_proximo_mantenimiento'] ?></td>
                    <td><?= $m['observaciones'] ?></td>
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
