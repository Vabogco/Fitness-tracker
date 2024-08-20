<?php
declare(strict_types=1);

namespace App\Serializer\Handler;

use App\Enum\ActivityType;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\Context;

class ActivityTypeHandler implements SubscribingHandlerInterface
{
    public static function getSubscribingMethods(): array
    {
        return [
            [
                'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => ActivityType::class,
                'method' => 'serializeEnumToJson',
            ],
            [
                'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
                'format' => 'json',
                'type' => ActivityType::class,
                'method' => 'deserializeEnumFromJson',
            ],
        ];
    }

    public function serializeEnumToJson(JsonSerializationVisitor $visitor, ActivityType $enum, array $type, Context $context)
    {
        return $enum->name;
    }

    public function deserializeEnumFromJson(JsonDeserializationVisitor $visitor, string $data, array $type, Context $context): ActivityType
    {
        return ActivityType::from($data);
    }
}
