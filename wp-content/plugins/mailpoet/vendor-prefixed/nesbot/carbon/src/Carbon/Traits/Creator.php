<?php
 namespace MailPoetVendor\Carbon\Traits; if (!defined('ABSPATH')) exit; use MailPoetVendor\Carbon\Carbon; use MailPoetVendor\Carbon\CarbonImmutable; use MailPoetVendor\Carbon\CarbonInterface; use MailPoetVendor\Carbon\Exceptions\InvalidDateException; use MailPoetVendor\Carbon\Exceptions\InvalidFormatException; use MailPoetVendor\Carbon\Exceptions\OutOfRangeException; use MailPoetVendor\Carbon\Translator; use Closure; use DateTimeInterface; use DateTimeZone; use Exception; use MailPoetVendor\ReturnTypeWillChange; trait Creator { use ObjectInitialisation; protected static $lastErrors; public function __construct($time = null, $tz = null) { if ($time instanceof \DateTimeInterface) { $time = $this->constructTimezoneFromDateTime($time, $tz)->format('Y-m-d H:i:s.u'); } if (\is_numeric($time) && (!\is_string($time) || !\preg_match('/^\\d{1,14}$/', $time))) { $time = static::createFromTimestampUTC($time)->format('Y-m-d\\TH:i:s.uP'); } $isNow = empty($time) || $time === 'now'; if (\method_exists(static::class, 'hasTestNow') && \method_exists(static::class, 'getTestNow') && static::hasTestNow() && ($isNow || static::hasRelativeKeywords($time))) { static::mockConstructorParameters($time, $tz); } if (\strpos((string) 0.1, '.') === \false) { $locale = \setlocale(\LC_NUMERIC, '0'); \setlocale(\LC_NUMERIC, 'C'); } try { parent::__construct($time ?: 'now', static::safeCreateDateTimeZone($tz) ?: null); } catch (\Exception $exception) { throw new \MailPoetVendor\Carbon\Exceptions\InvalidFormatException($exception->getMessage(), 0, $exception); } $this->constructedObjectId = \spl_object_hash($this); if (isset($locale)) { \setlocale(\LC_NUMERIC, $locale); } static::setLastErrors(parent::getLastErrors()); } private function constructTimezoneFromDateTime(\DateTimeInterface $date, &$tz) { if ($tz !== null) { $safeTz = static::safeCreateDateTimeZone($tz); if ($safeTz) { return $date->setTimezone($safeTz); } return $date; } $tz = $date->getTimezone(); return $date; } public function __clone() { $this->constructedObjectId = \spl_object_hash($this); } public static function instance($date) { if ($date instanceof static) { return clone $date; } static::expectDateTime($date); $instance = new static($date->format('Y-m-d H:i:s.u'), $date->getTimezone()); if ($date instanceof \MailPoetVendor\Carbon\CarbonInterface || $date instanceof \MailPoetVendor\Carbon\Traits\Options) { $settings = $date->getSettings(); if (!$date->hasLocalTranslator()) { unset($settings['locale']); } $instance->settings($settings); } return $instance; } public static function rawParse($time = null, $tz = null) { if ($time instanceof \DateTimeInterface) { return static::instance($time); } try { return new static($time, $tz); } catch (\Exception $exception) { $date = @static::now($tz)->change($time); if (!$date) { throw new \MailPoetVendor\Carbon\Exceptions\InvalidFormatException("Could not parse '{$time}': " . $exception->getMessage(), 0, $exception); } return $date; } } public static function parse($time = null, $tz = null) { $function = static::$parseFunction; if (!$function) { return static::rawParse($time, $tz); } if (\is_string($function) && \method_exists(static::class, $function)) { $function = [static::class, $function]; } return $function(...\func_get_args()); } public static function parseFromLocale($time, $locale = null, $tz = null) { return static::rawParse(static::translateTimeString($time, $locale, 'en'), $tz); } public static function now($tz = null) { return new static(null, $tz); } public static function today($tz = null) { return static::rawParse('today', $tz); } public static function tomorrow($tz = null) { return static::rawParse('tomorrow', $tz); } public static function yesterday($tz = null) { return static::rawParse('yesterday', $tz); } public static function maxValue() { if (self::$PHPIntSize === 4) { return static::createFromTimestamp(\PHP_INT_MAX); } return static::create(9999, 12, 31, 23, 59, 59); } public static function minValue() { if (self::$PHPIntSize === 4) { return static::createFromTimestamp(~\PHP_INT_MAX); } return static::create(1, 1, 1, 0, 0, 0); } private static function assertBetween($unit, $value, $min, $max) { if (static::isStrictModeEnabled() && ($value < $min || $value > $max)) { throw new \MailPoetVendor\Carbon\Exceptions\OutOfRangeException($unit, $min, $max, $value); } } private static function createNowInstance($tz) { if (!static::hasTestNow()) { return static::now($tz); } $now = static::getTestNow(); if ($now instanceof \Closure) { return $now(static::now($tz)); } return $now; } public static function create($year = 0, $month = 1, $day = 1, $hour = 0, $minute = 0, $second = 0, $tz = null) { if (\is_string($year) && !\is_numeric($year)) { return static::parse($year, $tz ?: (\is_string($month) || $month instanceof \DateTimeZone ? $month : null)); } $defaults = null; $getDefault = function ($unit) use($tz, &$defaults) { if ($defaults === null) { $now = self::createNowInstance($tz); $defaults = \array_combine(['year', 'month', 'day', 'hour', 'minute', 'second'], \explode('-', $now->rawFormat('Y-n-j-G-i-s.u'))); } return $defaults[$unit]; }; $year = $year === null ? $getDefault('year') : $year; $month = $month === null ? $getDefault('month') : $month; $day = $day === null ? $getDefault('day') : $day; $hour = $hour === null ? $getDefault('hour') : $hour; $minute = $minute === null ? $getDefault('minute') : $minute; $second = (float) ($second === null ? $getDefault('second') : $second); self::assertBetween('month', $month, 0, 99); self::assertBetween('day', $day, 0, 99); self::assertBetween('hour', $hour, 0, 99); self::assertBetween('minute', $minute, 0, 99); self::assertBetween('second', $second, 0, 99); $fixYear = null; if ($year < 0) { $fixYear = $year; $year = 0; } elseif ($year > 9999) { $fixYear = $year - 9999; $year = 9999; } $second = ($second < 10 ? '0' : '') . \number_format($second, 6); $instance = static::rawCreateFromFormat('!Y-n-j G:i:s.u', \sprintf('%s-%s-%s %s:%02s:%02s', $year, $month, $day, $hour, $minute, $second), $tz); if ($fixYear !== null) { $instance = $instance->addYears($fixYear); } return $instance; } public static function createSafe($year = null, $month = null, $day = null, $hour = null, $minute = null, $second = null, $tz = null) { $fields = static::getRangesByUnit(); foreach ($fields as $field => $range) { if (${$field} !== null && (!\is_int(${$field}) || ${$field} < $range[0] || ${$field} > $range[1])) { if (static::isStrictModeEnabled()) { throw new \MailPoetVendor\Carbon\Exceptions\InvalidDateException($field, ${$field}); } return \false; } } $instance = static::create($year, $month, $day, $hour, $minute, $second, $tz); foreach (\array_reverse($fields) as $field => $range) { if (${$field} !== null && (!\is_int(${$field}) || ${$field} !== $instance->{$field})) { if (static::isStrictModeEnabled()) { throw new \MailPoetVendor\Carbon\Exceptions\InvalidDateException($field, ${$field}); } return \false; } } return $instance; } public static function createStrict(?int $year = 0, ?int $month = 1, ?int $day = 1, ?int $hour = 0, ?int $minute = 0, ?int $second = 0, $tz = null) : self { $initialStrictMode = static::isStrictModeEnabled(); static::useStrictMode(\true); try { $date = static::create($year, $month, $day, $hour, $minute, $second, $tz); } finally { static::useStrictMode($initialStrictMode); } return $date; } public static function createFromDate($year = null, $month = null, $day = null, $tz = null) { return static::create($year, $month, $day, null, null, null, $tz); } public static function createMidnightDate($year = null, $month = null, $day = null, $tz = null) { return static::create($year, $month, $day, 0, 0, 0, $tz); } public static function createFromTime($hour = 0, $minute = 0, $second = 0, $tz = null) { return static::create(null, null, null, $hour, $minute, $second, $tz); } public static function createFromTimeString($time, $tz = null) { return static::today($tz)->setTimeFromTimeString($time); } private static function createFromFormatAndTimezone($format, $time, $originalTz) { if (\version_compare(\PHP_VERSION, '7.3.0-dev', '<')) { $format = \str_replace('.v', '.u', $format); } if ($originalTz === null) { return parent::createFromFormat($format, "{$time}"); } $tz = \is_int($originalTz) ? @\timezone_name_from_abbr('', (int) ($originalTz * static::MINUTES_PER_HOUR * static::SECONDS_PER_MINUTE), 1) : $originalTz; $tz = static::safeCreateDateTimeZone($tz, $originalTz); if ($tz === \false) { return \false; } return parent::createFromFormat($format, "{$time}", $tz); } public static function rawCreateFromFormat($format, $time, $tz = null) { $format = \preg_replace('/(?<!\\\\)((?:\\\\{2})*)c/', '$1Y-m-d\\TH:i:sP', $format); if (\preg_match('/(?<!\\\\)(?:\\\\{2})*(a|A)/', $format, $aMatches, \PREG_OFFSET_CAPTURE) && \preg_match('/(?<!\\\\)(?:\\\\{2})*(h|g|H|G)/', $format, $hMatches, \PREG_OFFSET_CAPTURE) && $aMatches[1][1] < $hMatches[1][1] && \preg_match('/(am|pm|AM|PM)/', $time)) { $format = \preg_replace('/^(.*)(?<!\\\\)((?:\\\\{2})*)(a|A)(.*)$/U', '$1$2$4 $3', $format); $time = \preg_replace('/^(.*)(am|pm|AM|PM)(.*)$/U', '$1$3 $2', $time); } $date = self::createFromFormatAndTimezone($format, $time, $tz); $lastErrors = parent::getLastErrors(); $mock = static::getMockedTestNow($tz); if ($mock && $date instanceof \DateTimeInterface) { $nonEscaped = '(?<!\\\\)(\\\\{2})*'; $nonIgnored = \preg_replace("/^.*{$nonEscaped}!/s", '', $format); if ($tz === null && !\preg_match("/{$nonEscaped}[eOPT]/", $nonIgnored)) { $tz = clone $mock->getTimezone(); } $mock = $mock->copy()->microsecond(0); if (!\preg_match("/{$nonEscaped}[!|]/", $format)) { $format = static::MOCK_DATETIME_FORMAT . ' ' . $format; $time = ($mock instanceof self ? $mock->rawFormat(static::MOCK_DATETIME_FORMAT) : $mock->format(static::MOCK_DATETIME_FORMAT)) . ' ' . $time; } $date = self::createFromFormatAndTimezone($format, $time, $tz); } if ($date instanceof \DateTimeInterface) { $instance = static::instance($date); $instance::setLastErrors($lastErrors); return $instance; } if (static::isStrictModeEnabled()) { throw new \MailPoetVendor\Carbon\Exceptions\InvalidFormatException(\implode(\PHP_EOL, $lastErrors['errors'])); } return \false; } public static function createFromFormat($format, $time, $tz = null) { $function = static::$createFromFormatFunction; if (!$function) { return static::rawCreateFromFormat($format, $time, $tz); } if (\is_string($function) && \method_exists(static::class, $function)) { $function = [static::class, $function]; } return $function(...\func_get_args()); } public static function createFromIsoFormat($format, $time, $tz = null, $locale = 'en', $translator = null) { $format = \preg_replace_callback('/(?<!\\\\)(\\\\{2})*(LTS|LT|[Ll]{1,4})/', function ($match) use($locale, $translator) { [$code] = $match; static $formats = null; if ($formats === null) { $translator = $translator ?: \MailPoetVendor\Carbon\Translator::get($locale); $formats = ['LT' => static::getTranslationMessageWith($translator, 'formats.LT', $locale, 'h:mm A'), 'LTS' => static::getTranslationMessageWith($translator, 'formats.LTS', $locale, 'h:mm:ss A'), 'L' => static::getTranslationMessageWith($translator, 'formats.L', $locale, 'MM/DD/YYYY'), 'LL' => static::getTranslationMessageWith($translator, 'formats.LL', $locale, 'MMMM D, YYYY'), 'LLL' => static::getTranslationMessageWith($translator, 'formats.LLL', $locale, 'MMMM D, YYYY h:mm A'), 'LLLL' => static::getTranslationMessageWith($translator, 'formats.LLLL', $locale, 'dddd, MMMM D, YYYY h:mm A')]; } return $formats[$code] ?? \preg_replace_callback('/MMMM|MM|DD|dddd/', function ($code) { return \mb_substr($code[0], 1); }, $formats[\strtoupper($code)] ?? ''); }, $format); $format = \preg_replace_callback('/(?<!\\\\)(\\\\{2})*(' . \MailPoetVendor\Carbon\CarbonInterface::ISO_FORMAT_REGEXP . '|[A-Za-z])/', function ($match) { [$code] = $match; static $replacements = null; if ($replacements === null) { $replacements = ['OD' => 'd', 'OM' => 'M', 'OY' => 'Y', 'OH' => 'G', 'Oh' => 'g', 'Om' => 'i', 'Os' => 's', 'D' => 'd', 'DD' => 'd', 'Do' => 'd', 'd' => '!', 'dd' => '!', 'ddd' => 'D', 'dddd' => 'D', 'DDD' => 'z', 'DDDD' => 'z', 'DDDo' => 'z', 'e' => '!', 'E' => '!', 'H' => 'G', 'HH' => 'H', 'h' => 'g', 'hh' => 'h', 'k' => 'G', 'kk' => 'G', 'hmm' => 'gi', 'hmmss' => 'gis', 'Hmm' => 'Gi', 'Hmmss' => 'Gis', 'm' => 'i', 'mm' => 'i', 'a' => 'a', 'A' => 'a', 's' => 's', 'ss' => 's', 'S' => '*', 'SS' => '*', 'SSS' => '*', 'SSSS' => '*', 'SSSSS' => '*', 'SSSSSS' => 'u', 'SSSSSSS' => 'u*', 'SSSSSSSS' => 'u*', 'SSSSSSSSS' => 'u*', 'M' => 'm', 'MM' => 'm', 'MMM' => 'M', 'MMMM' => 'M', 'Mo' => 'm', 'Q' => '!', 'Qo' => '!', 'G' => '!', 'GG' => '!', 'GGG' => '!', 'GGGG' => '!', 'GGGGG' => '!', 'g' => '!', 'gg' => '!', 'ggg' => '!', 'gggg' => '!', 'ggggg' => '!', 'W' => '!', 'WW' => '!', 'Wo' => '!', 'w' => '!', 'ww' => '!', 'wo' => '!', 'x' => 'U???', 'X' => 'U', 'Y' => 'Y', 'YY' => 'y', 'YYYY' => 'Y', 'YYYYY' => 'Y', 'YYYYYY' => 'Y', 'z' => 'e', 'zz' => 'e', 'Z' => 'e', 'ZZ' => 'e']; } $format = $replacements[$code] ?? '?'; if ($format === '!') { throw new \MailPoetVendor\Carbon\Exceptions\InvalidFormatException("Format {$code} not supported for creation."); } return $format; }, $format); return static::rawCreateFromFormat($format, $time, $tz); } public static function createFromLocaleFormat($format, $locale, $time, $tz = null) { return static::rawCreateFromFormat($format, static::translateTimeString($time, $locale, 'en'), $tz); } public static function createFromLocaleIsoFormat($format, $locale, $time, $tz = null) { $time = static::translateTimeString($time, $locale, 'en', \MailPoetVendor\Carbon\CarbonInterface::TRANSLATE_MONTHS | \MailPoetVendor\Carbon\CarbonInterface::TRANSLATE_DAYS | \MailPoetVendor\Carbon\CarbonInterface::TRANSLATE_MERIDIEM); return static::createFromIsoFormat($format, $time, $tz, $locale); } public static function make($var) { if ($var instanceof \DateTimeInterface) { return static::instance($var); } $date = null; if (\is_string($var)) { $var = \trim($var); if (!\preg_match('/^P[0-9T]/', $var) && !\preg_match('/^R[0-9]/', $var) && \preg_match('/[a-z0-9]/i', $var)) { $date = static::parse($var); } } return $date; } private static function setLastErrors(array $lastErrors) { static::$lastErrors = $lastErrors; } public static function getLastErrors() { return static::$lastErrors; } } 