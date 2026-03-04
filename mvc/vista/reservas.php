<?php
// Vista de reservas
// Esta vista asume que puede recibir la variable $reservas desde el controlador
require __DIR__ . '/layerHeader.php';
?>
<link rel="stylesheet" href="/git_develover/mvc/vista/css/header_style.css">
<main>
    <h1>Reservas</h1>
    <?php if (empty($reservas)): ?>
        <p>No hay reservas para mostrar.</p>
    <?php else: ?>
        <table border="1" cellpadding="6" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID Usuario</th>
                    <th>ID Producto</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservas as $r): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($r['id']); ?></td>
                        <td><?php echo htmlspecialchars($r['id_usuario']); ?></td>
                        <td><?php echo htmlspecialchars($r['id_producto']); ?></td>
                        <td><?php echo htmlspecialchars($r['cantidad']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>
