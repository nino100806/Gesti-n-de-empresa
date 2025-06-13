<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Sistema de Gestión Técnica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #eef2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }
        .container {
            max-width: 650px;
            padding-top: 60px;
        }
        h1 {
            font-weight: 600;
            font-size: 2.2rem;
            margin-bottom: 25px;
            text-align: center;
            color: #2c3e50;
        }
        .card {
            background-color: #ffffff;
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            padding: 30px;
        }
        .list-group a {
            border: none;
            border-radius: 8px;
            margin-bottom: 12px;
            transition: all 0.3s;
            padding: 15px 20px;
            font-size: 1.1rem;
            background-color: #f0f4f8;
            color: #2c3e50;
        }
        .list-group a:hover {
            background-color: #4a90e2;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <h1>Sistema de Gestión Técnica</h1>
        <p class="text-center mb-4">Seleccione la funcionalidad:</p>
        <div class="list-group">
            <a href="equipos.php" class="list-group-item list-group-item-action">Gestión de Equipos</a>
            <a href="empleados.php" class="list-group-item list-group-item-action">Gestión de Empleados</a>
            <a href="mantenimiento.php" class="list-group-item list-group-item-action">Plan de Mantenimiento Preventivo</a>
            <a href="copias_seguridad.php" class="list-group-item list-group-item-action">Plan de Copias de Seguridad</a>
            <a href="renovacion.php" class="list-group-item list-group-item-action">Plan de Renovación de Equipos</a>
            <a href="control_acceso.php" class="list-group-item list-group-item-action">Restricciones y Control de Software</a>
        </div>
    </div>
</div>
</body>
</html>
