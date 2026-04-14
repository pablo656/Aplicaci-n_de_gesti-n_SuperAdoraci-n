<?php
require_once __DIR__ . "/../config/mail_config.php";

class Mailer {
    private $host;
    private $port;
    private $user;
    private $pass;
    private $log = [];

    public function __construct() {
        $this->host = MAIL_HOST;
        $this->port = MAIL_PORT;
        $this->user = MAIL_USER;
        $this->pass = MAIL_PASS;
    }

    public function getLog() {
        return $this->log;
    }

    public function enviar($destinatario, $asunto, $cuerpoHtml) {
        $contexto = stream_context_create([
            'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true,
            ]
        ]);

        $socket = stream_socket_client(
            "tcp://{$this->host}:{$this->port}",
            $errno, $errstr, 15,
            STREAM_CLIENT_CONNECT,
            $contexto
        );
        if (!$socket) {
            $this->registrar("ERROR conexión TCP: [$errno] $errstr");
            $this->guardarLog();
            return false;
        }
        $this->registrar("Conexión TCP OK");
        stream_set_timeout($socket, 15);

        $this->registrar("S: " . $this->leer($socket));         // 220

        $this->escribir($socket, "EHLO localhost\r\n");
        $this->registrar("S: " . $this->leer($socket));

        $this->escribir($socket, "STARTTLS\r\n");
        $resp = $this->leer($socket);
        $this->registrar("S STARTTLS: " . $resp);
        if (strpos($resp, "220") === false) {
            $this->guardarLog();
            fclose($socket);
            return false;
        }

        $ok = stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT);
        if (!$ok) {
            $this->registrar("ERROR: TLS handshake fallido");
            $this->guardarLog();
            fclose($socket);
            return false;
        }
        $this->registrar("TLS OK");

        $this->escribir($socket, "EHLO localhost\r\n");
        $this->registrar("S: " . $this->leer($socket));

        $this->escribir($socket, "AUTH LOGIN\r\n");
        $this->registrar("S: " . $this->leer($socket));         // 334 user

        $this->escribir($socket, base64_encode($this->user) . "\r\n");
        $this->registrar("S: " . $this->leer($socket));         // 334 pass

        $this->escribir($socket, base64_encode($this->pass) . "\r\n");
        $respAuth = $this->leer($socket);
        $this->registrar("S AUTH: " . $respAuth);

        if (strpos($respAuth, "235") === false) {
            $this->registrar("ERROR: autenticación fallida");
            $this->guardarLog();
            fclose($socket);
            return false;
        }

        $this->escribir($socket, "MAIL FROM:<{$this->user}>\r\n");
        $this->registrar("S: " . $this->leer($socket));

        $this->escribir($socket, "RCPT TO:<{$destinatario}>\r\n");
        $this->registrar("S: " . $this->leer($socket));

        $this->escribir($socket, "DATA\r\n");
        $this->registrar("S: " . $this->leer($socket));

        $cabeceras  = "From: " . MAIL_FROM_NAME . " <{$this->user}>\r\n";
        $cabeceras .= "To: {$destinatario}\r\n";
        $cabeceras .= "Subject: =?UTF-8?B?" . base64_encode($asunto) . "?=\r\n";
        $cabeceras .= "MIME-Version: 1.0\r\n";
        $cabeceras .= "Content-Type: text/html; charset=UTF-8\r\n";

        $this->escribir($socket, $cabeceras . "\r\n" . $cuerpoHtml . "\r\n.\r\n");
        $this->registrar("S: " . $this->leer($socket));

        $this->escribir($socket, "QUIT\r\n");
        fclose($socket);

        $this->registrar("Email enviado OK a $destinatario");
        $this->guardarLog();
        return true;
    }

    private function escribir($socket, $data) {
        fwrite($socket, $data);
    }

    private function leer($socket) {
        $resp = "";
        while ($linea = fgets($socket, 512)) {
            $resp .= $linea;
            if (isset($linea[3]) && $linea[3] === " ") break;
        }
        return trim($resp);
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
