<?php declare(strict_types=1);

namespace Tests\Mediagone\SmallUid\Doctrine;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\PostgreSqlPlatform;
use Doctrine\DBAL\Types\Type;
use Mediagone\SmallUid\Doctrine\ClassToDatabaseConversionError;
use Mediagone\SmallUid\Doctrine\DatabaseToClassConversionError;
use Mediagone\SmallUid\Doctrine\SmallUidType;
use Mediagone\SmallUid\SmallUid;
use PHPUnit\Framework\TestCase;
use function hex2bin;


/**
 * @covers \Mediagone\SmallUid\Doctrine\SmallUidType
 */
final class SmallUidTypeTest extends TestCase
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private SmallUidType $type;
    
    
    
    //========================================================================================================
    // Initialization
    //========================================================================================================
    
    public static function setUpBeforeClass() : void
    {
        if (!Type::hasType(SmallUidType::NAME)) {
            Type::addType(SmallUidType::NAME, SmallUidType::class);
        }
    }
    
    
    public function setUp() : void
    {
        $this->type = Type::getType(SmallUidType::NAME);
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_return_its_name() : void
    {
        self::assertSame(SmallUidType::NAME, $this->type->getName());
    }
    
    
    public function test_requires_comment_hint() : void
    {
        self::assertTrue($this->type->requiresSQLCommentHint(new MySqlPlatform()));
        self::assertTrue($this->type->requiresSQLCommentHint(new PostgreSqlPlatform()));
    }
    
    
    public function test_can_convert_to_database_value() : void
    {
        $uid = SmallUid::random();
        $value = $this->type->convertToDatabaseValue($uid, new MySqlPlatform());
        
        self::assertSame(hex2bin((string)$uid->toHex()), $value);
    }
    
    
    public function test_can_convert_null_value_to_database() : void
    {
        $value = $this->type->convertToDatabaseValue(null, new MySqlPlatform());
        
        self::assertNull($value);
    }
    
    
    public function test_can_only_convert_SmallUid_value_to_database() : void
    {
        $this->expectException(ClassToDatabaseConversionError::class);
        $invalidValue = new class() { };
        $this->type->convertToDatabaseValue($invalidValue, new MySqlPlatform());
    }
    
    
    
    
    public function test_can_convert_value_from_database() : void
    {
        $value = (string)SmallUid::random()->toHex();
        $id = $this->type->convertToPHPValue(hex2bin($value), new MySqlPlatform());
        
        self::assertInstanceOf(SmallUid::class, $id);
        self::assertSame($value, (string)$id->toHex());
    }
    
    
    public function test_can_convert_null_value_from_database() : void
    {
        $id = $this->type->convertToPHPValue(null, new MySqlPlatform());
        
        self::assertNull($id);
    }
    
    
    public function test_can_only_convert_value_from_database_to_SmallUid() : void
    {
        $this->expectException(DatabaseToClassConversionError::class);
        
        if (!Type::hasType(InvalidUidType::NAME)) {
            Type::addType(InvalidUidType::NAME, InvalidUidType::class);
        }
        $invalidType = Type::getType(InvalidUidType::NAME);
        
        $invalidType->convertToPHPValue('', new MySqlPlatform());
    }
    
    
    //========================================================================================================
    // Custom type
    //========================================================================================================
    
    public function test_can_register_automatically() : void
    {
        self::assertFalse(Type::hasType('app_customuid'), 'Type should not be registered yet.');
        SmallUidType::registerDoctrineTypeForClass(CustomUid::class);
        self::assertTrue(Type::hasType('app_customuid'), 'Type should be registered!');
        
        $type = Type::getType('app_customuid');
        self::assertInstanceOf(SmallUidType::class, $type);
        self::assertSame('app_customuid', $type->getName());
        self::assertSame(CustomUid::class, $type->getClassName());
    }
    
    
    
    public function test_can_convert_custom_uid_from_database() : void
    {
        $type = Type::getType('app_customuid');
        
        $value = CustomUid::random()->toHex()->toBinary();
        $id = $type->convertToPHPValue($value, new MySqlPlatform());
        
        self::assertInstanceOf(CustomUid::class, $id);
        self::assertSame($value, $id->toHex()->toBinary());
    }
    
    
    public function test_can_convert_null_custom_uid_value_from_database() : void
    {
        $type = Type::getType('app_customuid');
        
        $id = $type->convertToPHPValue(null, new MySqlPlatform());
        self::assertNull($id);
    }
    
}
