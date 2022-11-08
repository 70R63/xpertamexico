<?php
namespace App\Dto\Estafeta\API\V3;

use Spatie\DataTransferObject\DataTransferObject;

use App\Dto\Estafeta\API\V3\WayBillDocument;
use App\Dto\Estafeta\API\V3\ItemDescription;
use App\Dto\Estafeta\API\V3\ServiceConfiguration;
use App\Dto\Estafeta\API\V3\Location;



class LabelDefinition extends DataTransferObject {
	public $wayBillDocument;
    public $itemDescription;
    public $serviceConfiguration;
    public $location;
}