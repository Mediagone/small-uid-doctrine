<?php declare(strict_types=1);

namespace Mediagone\SmallUid\Doctrine;

use LogicException;
use function gettype;


final class ClassToDatabaseConversionError extends LogicException
{
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    public function __construct($value, string $expectedClassName)
    {
        parent::__construct('Value to convert to database format must be an instance of "'.  $expectedClassName .'", got "'. gettype($value) .'".');
    }
    
    
    
}
