<?php
// BORRAR ESTE ARCHIVO DESPUÉS DE DIAGNOSTICAR
$directorioMVC = dirname(__DIR__) . DIRECTORY_SEPARATOR . basename(__DIR__) . DIRECTORY_SEPARATOR;
$carpeta = __DIR__ . DIRECTORY_SEPARATOR . "mvc" . DIRECTORY_SEPARATOR . "imagenes" . DIRECTORY_SEPARATOR;

echo "<pre>";
echo "Ruta calculada para imagenes: " . $carpeta . "\n";
echo "¿Existe el directorio?: " . (is_dir($carpeta) ? "SÍ" : "NO") . "\n";
echo "¿Es escribible?: " . (is_writable($carpeta) ? "SÍ" : "NO") . "\n";
echo "Usuario PHP: " . get_current_user() . "\n";
echo "Usuario proceso: " . (function_exists('posix_getpwuid') ? posix_getpwuid(posix_geteuid())['name'] : 'N/A') . "\n";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "\n";
echo "post_max_size: " . ini_get('post_max_size') . "\n";
echo "file_uploads: " . ini_get('file_uploads') . "\n";
echo "upload_tmp_dir: " . ini_get('upload_tmp_dir') . "\n";
echo "open_basedir: " . (ini_get('open_basedir') ?: '(sin restricción)') . "\n";
echo "</pre>";
