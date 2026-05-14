<?php
require_once __DIR__ . "/../config/mail_config.php";

class Mailer {
    private const API_URL = "https://api.brevo.com/v3/smtp/email";
    private $log = [];

    public function getLog() {
        return $this->log;
    }

    public function enviar($destinatario, $asunto, $cuerpoHtml) {
        $payload = json_encode([
            "sender"      => ["name" => MAIL_FROM_NAME, "email" => MAIL_FROM],
            "to"          => [["email" => $destinatario]],
            "subject"     => $asunto,
            "htmlContent" => $cuerpoHtml,
        ]);

        $ch = curl_init(self::API_URL);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_HTTPHEADER     => [
                "api-key: " . MAIL_API_KEY,
                "Content-Type: application/json",
                "Accept: application/json",
            ],
        ]);

        $respuesta  = curl_exec($ch);
        $httpCode   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError  = curl_error($ch);

        if ($curlError) {
            $this->registrar("ERROR curl: $curlError");
            $this->guardarLog();
            return false;
        }

        $this->registrar("HTTP $httpCode — $respuesta");

        if ($httpCode < 200 || $httpCode >= 300) {
            $this->registrar("ERROR: envío fallido a $destinatario");
            $this->guardarLog();
            return false;
        }

        $this->registrar("Email enviado OK a $destinatario");
        $this->guardarLog();
        return true;
    }

    private function registrar($mensaje) {
        $this->log[] = date("H:i:s") . " " . $mensaje;
    }

    private function guardarLog() {
        foreach ($this->log as $linea) {
            error_log("[MAILER] " . $linea);
        }
    }
}
