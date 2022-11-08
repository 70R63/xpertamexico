<?php
namespace App\Dto\Estafeta\API\V3;

use Spatie\DataTransferObject\DataTransferObject;


class WayBillDocument extends DataTransferObject {
	public string $content = "Contenido del envio";
}