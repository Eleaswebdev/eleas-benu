<?php
 namespace MailPoetVendor\Doctrine\ORM\Mapping; if (!defined('ABSPATH')) exit; use MailPoetVendor\Doctrine\DBAL\Platforms\AbstractPlatform; interface QuoteStrategy { public function getColumnName($fieldName, \MailPoetVendor\Doctrine\ORM\Mapping\ClassMetadata $class, \MailPoetVendor\Doctrine\DBAL\Platforms\AbstractPlatform $platform); public function getTableName(\MailPoetVendor\Doctrine\ORM\Mapping\ClassMetadata $class, \MailPoetVendor\Doctrine\DBAL\Platforms\AbstractPlatform $platform); public function getSequenceName(array $definition, \MailPoetVendor\Doctrine\ORM\Mapping\ClassMetadata $class, \MailPoetVendor\Doctrine\DBAL\Platforms\AbstractPlatform $platform); public function getJoinTableName(array $association, \MailPoetVendor\Doctrine\ORM\Mapping\ClassMetadata $class, \MailPoetVendor\Doctrine\DBAL\Platforms\AbstractPlatform $platform); public function getJoinColumnName(array $joinColumn, \MailPoetVendor\Doctrine\ORM\Mapping\ClassMetadata $class, \MailPoetVendor\Doctrine\DBAL\Platforms\AbstractPlatform $platform); public function getReferencedJoinColumnName(array $joinColumn, \MailPoetVendor\Doctrine\ORM\Mapping\ClassMetadata $class, \MailPoetVendor\Doctrine\DBAL\Platforms\AbstractPlatform $platform); public function getIdentifierColumnNames(\MailPoetVendor\Doctrine\ORM\Mapping\ClassMetadata $class, \MailPoetVendor\Doctrine\DBAL\Platforms\AbstractPlatform $platform); public function getColumnAlias($columnName, $counter, \MailPoetVendor\Doctrine\DBAL\Platforms\AbstractPlatform $platform, ?\MailPoetVendor\Doctrine\ORM\Mapping\ClassMetadata $class = null); } 