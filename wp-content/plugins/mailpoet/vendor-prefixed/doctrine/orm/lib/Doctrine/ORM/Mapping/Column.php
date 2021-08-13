<?php
 namespace MailPoetVendor\Doctrine\ORM\Mapping; if (!defined('ABSPATH')) exit; use Attribute; use MailPoetVendor\Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor; final class Column implements \MailPoetVendor\Doctrine\ORM\Mapping\Annotation { public $name; public $type; public $length; public $precision = 0; public $scale = 0; public $unique = \false; public $nullable = \false; public $options = []; public $columnDefinition; public function __construct(?string $name = null, ?string $type = null, ?int $length = null, ?int $precision = null, ?int $scale = null, bool $unique = \false, bool $nullable = \false, array $options = [], ?string $columnDefinition = null) { $this->name = $name; $this->type = $type; $this->length = $length; $this->precision = $precision; $this->scale = $scale; $this->unique = $unique; $this->nullable = $nullable; $this->options = $options; $this->columnDefinition = $columnDefinition; } } 