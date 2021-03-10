<?php declare(strict_types=1);

namespace Mediagone\SmallUid\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Mediagone\Types\Common\System\Hex;
use Mediagone\SmallUid\SmallUid;
use function is_a;


class SmallUidType extends Type
{
    //========================================================================================================
    // Constants
    //========================================================================================================
    
    public const NAME = 'common_smalluid';
    
    
    
    //========================================================================================================
    // Type implementation
    //========================================================================================================
    
    /**
     * Gets the SQL declaration snippet for a field of this type.
     */
    final public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform) : string
    {
        return $platform->getBinaryTypeDeclarationSQL([
            'length' => '8',
            'fixed' => true,
        ]);
    }
    
    
    /**
     * Adds an SQL comment to typehint the actual Doctrine Type for reverse schema engineering.
     */
    final public function requiresSQLCommentHint(AbstractPlatform $platform) : bool
    {
        return true;
    }
    
    
    /**
     * Converts a value from its PHP representation to its database representation of this type.
     *
     * @param SmallUid|null $value The value to convert.
     */
    final public function convertToDatabaseValue($value, AbstractPlatform $platform) : ?string
    {
        if ($value === null) {
            return null;
        }
        
        if (! ($value instanceof SmallUid)) {
            throw new ClassToDatabaseConversionError($value, SmallUid::class);
        }
        
        return $value->toHex()->toBinary();
    }
    
    
    /**
     * Converts a value from its database representation to its PHP representation of this type.
     *
     * @param string|null $value The value to convert.
     */
    final public function convertToPHPValue($value, AbstractPlatform $platform) : ?SmallUid
    {
        if ($value === null) {
            return null;
        }
        
        $className = static::getClassName();
        if (! is_a($className, SmallUid::class, true)) {
            throw new DatabaseToClassConversionError($className, SmallUid::class);
        }
        
        /** @var SmallUid $className */
        return $className::fromHex(Hex::fromBinary($value));
    }
    
    
    
    //========================================================================================================
    //
    //========================================================================================================
    
    public function getName() : string
    {
        return self::NAME;
    }
    
    
    protected static function getClassName() : string
    {
        return SmallUid::class;
    }
    
    
    
}
