<?php declare(strict_types=1);

namespace Mediagone\SmallUid\Doctrine;

use LogicException;


final class DatabaseToClassConversionError extends LogicException
{
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    public function __construct(string $type, string $expectedClassName)
    {
        parent::__construct('Value to restore from the database can only be converted to a subclass of "'.  $expectedClassName .'", got "'. $type .'".');
    }
    
    
    
}
