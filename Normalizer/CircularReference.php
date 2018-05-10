<?php

namespace SymfonyTools\Normalizer;

use \Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use \Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use \Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use \Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use \Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


class CircularReference extends ObjectNormalizer
{

    protected $propertyAccessor;
    protected $version = 2;

    public function __construct(
        ClassMetadataFactoryInterface $classMetadataFactory = null,
        NameConverterInterface $nameConverter = null,
        PropertyAccessorInterface $propertyAccessor = null,
        PropertyTypeExtractorInterface $propertyTypeExtractor = null
    )
    {

        parent::__construct(
            $classMetadataFactory,
            $nameConverter,
            $propertyAccessor,
            $propertyTypeExtractor
        );

        $this->setCircularReferenceLimit(2);

    }

}
