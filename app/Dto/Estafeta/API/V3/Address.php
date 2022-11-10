<?php
namespace App\Dto\Estafeta\API\V3;

use Spatie\DataTransferObject\DataTransferObject;

class Address extends DataTransferObject {
	
	public bool $bUsedCode = false;

	#[Max(5)]
	public string $roadTypeAbbName = "Av.";

	#[Max(50)]
	public string $roadName = "roadName";

	#[Max(5)]
	public string $settlementTypeAbbName = "settlementTypeAbbName";

	#[Max(57)]
	public string $settlementName = "settlementName";

	#[Max(5)]
	public string $zipCode = "zipCode";
	
	public string $countryName = "MEX";

	#[Max(100)]
	public string $addressReference = "addressReference";
	
	#[Max(20)]
	public string $externalNum = "externalNum";
	

}