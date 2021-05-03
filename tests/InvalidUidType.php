<?php declare(strict_types=1);

namespace Tests\Mediagone\SmallUid\Doctrine;

use Mediagone\SmallUid\Doctrine\SmallUidType;
use function get_class;


final class InvalidUidType extends SmallUidType
{
    public const NAME = 'invalid_uid';
    
    public function getName() : string
    {
        return self::NAME;
    }
    
    public function getClassName() : string
    {
        return get_class(new class() { });
    }
}
