<?php
// Configuración SMTP — Brevo (brevo.com, 300 emails/día gratis)
// PASOS PARA OBTENER LAS CREDENCIALES:
// 1. Regístrate gratis en https://app.brevo.com
// 2. Ve a tu nombre (arriba derecha) → SMTP & API → SMTP
// 3. Copia el "Login" (tu email de Brevo) y genera una clave SMTP ("Generate a new SMTP key")
// 4. Ponlos abajo

define("MAIL_HOST",      "smtp-relay.brevo.com");
define("MAIL_PORT",      587);
define("MAIL_USER",      "");    // Login que aparece en la sección SMTP
define("MAIL_PASS",      "");   // clave SMTP generada en Brevo
define("MAIL_FROM_NAME", "SuperAdoracion");

// URL base de la aplicación (sin barra final)
define("APP_URL", "http://localhost/git_developer/mvc/vista");
