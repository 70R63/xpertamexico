<?php
namespace App\Dto\Estafeta\API\V3;

use Spatie\DataTransferObject\DataTransferObject;

use App\Dto\Estafeta\API\V3\Insurance;

class ServiceConfiguration extends DataTransferObject {
	
	public int $quantityOfLabels=1;
	
	#[Max(2)]	
	public string $serviceTypeId="70";

	public string $salesOrganization = "112";

	#[Max(5)]
	public string $originZipCodeForRouting = "00000";

	public bool $isInsurance;

	public Insurance $insurance;

	public bool $isReturnDocument = FALSE;
	
	
}