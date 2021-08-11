<?php
 namespace MailPoetVendor\Doctrine\ORM\Query\AST\Functions; if (!defined('ABSPATH')) exit; use MailPoetVendor\Doctrine\DBAL\Platforms\AbstractPlatform; use MailPoetVendor\Doctrine\ORM\Query\AST\Node; use MailPoetVendor\Doctrine\ORM\Query\Lexer; use MailPoetVendor\Doctrine\ORM\Query\Parser; use MailPoetVendor\Doctrine\ORM\Query\SqlWalker; use function strcasecmp; class TrimFunction extends \MailPoetVendor\Doctrine\ORM\Query\AST\Functions\FunctionNode { public $leading; public $trailing; public $both; public $trimChar = \false; public $stringPrimary; public function getSql(\MailPoetVendor\Doctrine\ORM\Query\SqlWalker $sqlWalker) { $stringPrimary = $sqlWalker->walkStringPrimary($this->stringPrimary); $platform = $sqlWalker->getConnection()->getDatabasePlatform(); $trimMode = $this->getTrimMode(); $trimChar = $this->trimChar !== \false ? $sqlWalker->getConnection()->quote($this->trimChar) : \false; return $platform->getTrimExpression($stringPrimary, $trimMode, $trimChar); } public function parse(\MailPoetVendor\Doctrine\ORM\Query\Parser $parser) { $lexer = $parser->getLexer(); $parser->match(\MailPoetVendor\Doctrine\ORM\Query\Lexer::T_IDENTIFIER); $parser->match(\MailPoetVendor\Doctrine\ORM\Query\Lexer::T_OPEN_PARENTHESIS); $this->parseTrimMode($parser); if ($lexer->isNextToken(\MailPoetVendor\Doctrine\ORM\Query\Lexer::T_STRING)) { $parser->match(\MailPoetVendor\Doctrine\ORM\Query\Lexer::T_STRING); $this->trimChar = $lexer->token['value']; } if ($this->leading || $this->trailing || $this->both || $this->trimChar) { $parser->match(\MailPoetVendor\Doctrine\ORM\Query\Lexer::T_FROM); } $this->stringPrimary = $parser->StringPrimary(); $parser->match(\MailPoetVendor\Doctrine\ORM\Query\Lexer::T_CLOSE_PARENTHESIS); } private function getTrimMode() : int { if ($this->leading) { return \MailPoetVendor\Doctrine\DBAL\Platforms\AbstractPlatform::TRIM_LEADING; } if ($this->trailing) { return \MailPoetVendor\Doctrine\DBAL\Platforms\AbstractPlatform::TRIM_TRAILING; } if ($this->both) { return \MailPoetVendor\Doctrine\DBAL\Platforms\AbstractPlatform::TRIM_BOTH; } return \MailPoetVendor\Doctrine\DBAL\Platforms\AbstractPlatform::TRIM_UNSPECIFIED; } private function parseTrimMode(\MailPoetVendor\Doctrine\ORM\Query\Parser $parser) : void { $lexer = $parser->getLexer(); $value = $lexer->lookahead['value']; if (\strcasecmp('leading', $value) === 0) { $parser->match(\MailPoetVendor\Doctrine\ORM\Query\Lexer::T_LEADING); $this->leading = \true; return; } if (\strcasecmp('trailing', $value) === 0) { $parser->match(\MailPoetVendor\Doctrine\ORM\Query\Lexer::T_TRAILING); $this->trailing = \true; return; } if (\strcasecmp('both', $value) === 0) { $parser->match(\MailPoetVendor\Doctrine\ORM\Query\Lexer::T_BOTH); $this->both = \true; return; } } } 