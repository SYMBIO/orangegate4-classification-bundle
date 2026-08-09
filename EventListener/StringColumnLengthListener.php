<?php

declare(strict_types=1);

namespace Symbio\OrangeGate\ClassificationBundle\EventListener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Sonata Classification Base* XML mappings omit VARCHAR lengths; DBAL 4 MySQL requires them.
 */
final class StringColumnLengthListener
{
    private const DEFAULT_LENGTH = 255;

    public function loadClassMetadata(LoadClassMetadataEventArgs $args): void
    {
        $metadata = $args->getClassMetadata();
        if (!$metadata instanceof ClassMetadata) {
            return;
        }

        $class = $metadata->getName();
        if (!str_starts_with($class, 'Symbio\\OrangeGate\\ClassificationBundle\\Entity\\')
            && !str_starts_with($class, 'Sonata\\ClassificationBundle\\Entity\\')) {
            return;
        }

        foreach ($metadata->fieldMappings as $fieldName => $mapping) {
            $type = $this->mappingValue($mapping, 'type');
            $length = $this->mappingValue($mapping, 'length');
            if ($type !== 'string' || $length !== null) {
                continue;
            }

            $this->setMappingLength($metadata, $fieldName, self::DEFAULT_LENGTH);
        }
    }

    private function mappingValue(array|object $mapping, string $key): mixed
    {
        if (is_array($mapping)) {
            return $mapping[$key] ?? null;
        }

        return $mapping->{$key} ?? null;
    }

    private function setMappingLength(ClassMetadata $metadata, string $fieldName, int $length): void
    {
        $mapping = $metadata->fieldMappings[$fieldName];
        if (is_array($mapping)) {
            $mapping['length'] = $length;
            $metadata->fieldMappings[$fieldName] = $mapping;

            return;
        }

        if (is_object($mapping) && property_exists($mapping, 'length')) {
            $mapping->length = $length;
        }
    }
}
