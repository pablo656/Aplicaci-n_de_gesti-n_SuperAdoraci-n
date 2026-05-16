<?php
define('ACCESO_PERMITIDO', true);
require_once "mvc/helpers/Mailer.php";

$mailer = new Mailer();
$ok = $mailer->enviar("superadoracionpruebas@gmail.com", "Test email", "<p>Prueba de envío</p>");

echo "<pre>";
echo "Resultado: " . ($ok ? "OK" : "FALLIDO") . "\n\n";
echo "Log:\n";
print_r($mailer->getLog());
echo "</pre>";
