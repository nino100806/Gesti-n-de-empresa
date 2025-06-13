<?php
$conexion = new mysqli("localhost", "root", "", "sistema_it");
if ($conexion->connect_error) die("Error de conexión a la base de datos");

$anio_actual = date("Y");
$resultado = $conexion->query("SELECT *, ($anio_actual - anio_fabricacion) AS antiguedad FROM equipos");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Evaluación de Renovación de Equipos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h1>Evaluación de Renovación de Equipos</h1>
    <p>Se recomienda renovar los equipos con más de 5 años de antigüedad.</p>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th><th>Tipo</th><th>Marca</th><th>Modelo</th><th>Año Fab.</th><th>Antigüedad</th><th>Recomendación</th>
            </tr>
        </thead>
        <tbody>
            <?php while($fila = $resultado->fetch_assoc()): ?>
            <tr class="<?= ($fila['antiguedad'] >= 5) ? 'table-danger' : '' ?>">
                <td><?= $fila['id'] ?></td>
                <td><?= $fila['tipo'] ?></td>
                <td><?= $fila['marca'] ?></td>
                <td><?= $fila['modelo'] ?></td>
                <td><?= $fila['anio_fabricacion'] ?></td>
                <td><?= $fila['antiguedad'] ?> años</td>
                <td><?= ($fila['antiguedad'] >= 5) ? 'Recomendado renovar' : 'En buen estado' ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
<?php $conexion->close(); ?>
