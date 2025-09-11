<?php
namespace App\Logging;

use Monolog\Formatter\LineFormatter;

class CustomLogFormatter extends LineFormatter
{
    // Define el formato que quieres para tu log
    // [%datetime%] es el timestamp automático de Monolog
    // [%level_name%] es el nivel del log (INFO, ERROR, etc.)
    // [%message%] es el mensaje del log
    // [%context%] es el array de datos adicionales (ej. 'pedido_id')
    // [%extra%] es información extra del handler (ej. user_id del procesador)

    const FORMAT = "[%datetime%] | [%level_name%] | [%message%] \n %context% ";

    public function __construct()
    {
        // El segundo argumento es el formato del timestamp.
        // La 'u' al final incluye microsegundos para mayor precisión.
        parent::__construct(static::FORMAT, 'Y-m-d H:i:s');
        
        // Opcional: Esto incluye los detalles del stack trace para logs de errores
        $this->includeStacktraces(true);
    }
}