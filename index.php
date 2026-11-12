<?php
// La aplicación consulta MySQL para obtener el saludo y verificar la conexión.
$datos = null;
$error = null;

try {
    $host = getenv('DB_HOST');
    $base = getenv('DB_NAME');
    $conexion = new PDO(
        "mysql:host={$host};port=3306;dbname={$base};charset=utf8mb4",
        getenv('DB_USER'),
        getenv('DB_PASSWORD'),
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_TIMEOUT => 5,
        ]
    );

    $consulta = $conexion->query(
        "SELECT 'Hola mundo' AS mensaje, DATABASE() AS base_datos, CURRENT_TIMESTAMP AS fecha"
    );
    $datos = $consulta->fetch();
} catch (PDOException $excepcion) {
    http_response_code(503);
    error_log('Error de conexión MySQL: ' . $excepcion->getMessage());
    $error = 'No se pudo consultar MySQL. Revisa el estado de los servicios con docker compose ps.';
}

function escapar(string $valor): string
{
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hola mundo - PHP y MySQL</title>
    <style>
        body { font-family: Arial, sans-serif; background: #eef3f9; color: #172b45; margin: 0; padding: 40px 20px; }
        main { max-width: 620px; margin: 40px auto; padding: 32px; background: white; border-radius: 12px; }
        h1 { margin-top: 0; }
        .estado { color: #126337; font-weight: bold; }
        .error { color: #a01818; }
        dt { font-weight: bold; margin-top: 18px; }
        dd { margin: 5px 0 0; }
    </style>
</head>
<body>
<main>
    <?php if ($datos): ?>
        <h1><?= escapar($datos['mensaje']) ?></h1>
        <p class="estado">Conexión a MySQL exitosa</p>
        <p>El saludo y los siguientes datos se obtuvieron mediante una consulta a MySQL.</p>
        <dl>
            <dt>Base de datos</dt>
            <dd><?= escapar($datos['base_datos']) ?></dd>
            <dt>Fecha y hora del servidor MySQL</dt>
            <dd><?= escapar($datos['fecha']) ?></dd>
        </dl>
    <?php else: ?>
        <h1>Conexión pendiente</h1>
        <p class="error"><?= escapar($error) ?></p>
    <?php endif; ?>
</main>
</body>
</html>
