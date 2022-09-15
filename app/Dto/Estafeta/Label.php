<?php
namespace App\Dto\Estafeta;

use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\FieldValidator as Validator;

use App\Dto\Estafeta\LabelDescriptionList;

class Label extends DataTransferObject 
{

    public string $apiToken ="md5"
    ;    
    /** @var string */
    public $suscriberId = "1";
    
    /** @var string */
    public $customerNumber = "0000000";

     /** @var string */
    public $password = ",1,B(vVi";

     /** @var string */
    public $login = "AdminUser";

     /** @var boolean */
    public $valid = true;
    
    /** @var int */
    public $quadrant = 0;

    /** @var int */
    public $paperType = 1;

    /** @var int */
    public $labelDescriptionListCount = 1;    

    public LabelDescriptionList $labelDescriptionList ; 

    
}