<?php
 namespace MailPoetVendor\Carbon\Traits; if (!defined('ABSPATH')) exit; use MailPoetVendor\Carbon\Exceptions\InvalidFormatException; use MailPoetVendor\ReturnTypeWillChange; trait Serialization { use ObjectInitialisation; protected static $serializer; protected $dumpProperties = ['date', 'timezone_type', 'timezone']; protected $dumpLocale = null; public function serialize() { return \serialize($this); } public static function fromSerialized($value) { $instance = @\unserialize("{$value}"); if (!$instance instanceof static) { throw new \MailPoetVendor\Carbon\Exceptions\InvalidFormatException("Invalid serialized value: {$value}"); } return $instance; } public static function __set_state($dump) { if (\is_string($dump)) { return static::parse($dump); } $date = \get_parent_class(static::class) && \method_exists(parent::class, '__set_state') ? parent::__set_state((array) $dump) : (object) $dump; return static::instance($date); } public function __sleep() { $properties = $this->dumpProperties; if ($this->localTranslator ?? null) { $properties[] = 'dumpLocale'; $this->dumpLocale = $this->locale ?? null; } return $properties; } public function __wakeup() { if (\get_parent_class() && \method_exists(parent::class, '__wakeup')) { parent::__wakeup(); } $this->constructedObjectId = \spl_object_hash($this); if (isset($this->dumpLocale)) { $this->locale($this->dumpLocale); $this->dumpLocale = null; } $this->cleanupDumpProperties(); } public function jsonSerialize() { $serializer = $this->localSerializer ?? static::$serializer; if ($serializer) { return \is_string($serializer) ? $this->rawFormat($serializer) : $serializer($this); } return $this->toJSON(); } public static function serializeUsing($callback) { static::$serializer = $callback; } public function cleanupDumpProperties() { foreach ($this->dumpProperties as $property) { if (isset($this->{$property})) { unset($this->{$property}); } } return $this; } } 