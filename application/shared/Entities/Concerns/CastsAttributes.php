<?php

namespace Shared\Entities\Concerns;

use Carbon\{Carbon, CarbonInterface};
use DateTimeInterface;
use JsonException;
use RuntimeException;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
trait CastsAttributes
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected array $casts = [];

    /**
     * The format of the entity's date columns.
     *
     * @var string
     */
    protected string $dateFormat;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected array $dates = [];

    /**
     * Determine whether an attribute should be cast to a native type.
     *
     * @param string            $key
     * @param array|string|null $types
     * @return bool
     */
    public function hasCast(string $key, array|string $types = null): bool
    {
        if (array_key_exists($key, $this->getCasts())) {
            return !$types || in_array($this->getCastType($key), (array)$types, true);
        }

        return false;
    }

    /**
     * Get the casts array.
     *
     * @return array
     */
    public function getCasts(): array
    {
        return $this->casts;
    }

    /**
     * Get the format for database stored dates.
     *
     * @return string
     */
    public function getDateFormat(): string
    {
        return $this->dateFormat ?: 'Y-m-d';
    }

    /**
     * Set the date format used by the model.
     *
     * @param string $format
     * @return $this
     */
    public function setDateFormat(string $format): static
    {
        $this->dateFormat = $format;

        return $this;
    }

    /**
     * Get the attributes that should be converted to dates.
     *
     * @return array
     */
    public function getDates(): array
    {
        return $this->dates;
    }

    /**
     * Cast an attribute to a native PHP type.
     *
     * @SuppressWarnings(PHPMD)
     * @param string $key
     * @param mixed  $value
     * @return                  mixed
     * @throws JsonException
     */
    protected function castAttribute(string $key, mixed $value): mixed
    {
        if (is_null($value)) {
            return null;
        }

        return match ($this->getCastType($key)) {
            'int', 'integer' => (int)$value,
            'real', 'float', 'double' => $this->fromFloat($value),
            'decimal' => $this->asDecimal($value, (int)explode(':', $this->getCasts()[$key], 2)[1]),
            'string' => (string)$value,
            'bool', 'boolean' => (bool)$value,
            'object' => $this->fromJson($value, true),
            'array', 'json' => $this->fromJson($value),
            'date' => $this->asDate($value),
            'datetime', 'custom_datetime' => $this->asDateTime($value),
            'timestamp' => $this->asTimestamp($value),
            default => $value,
        };
    }

    /**
     * Cast the given attribute to JSON.
     *
     * @param string $key
     * @param mixed  $value
     * @return string|RuntimeException
     * @throw  RuntimeException
     * @throws JsonException
     */
    protected function castAttributeAsJson(string $key, mixed $value): string|RuntimeException
    {
        $value = $this->asJson($value);

        if ($value === false) {
            $message = json_last_error_msg();
            $class   = get_class($this);

            return new RuntimeException(
                "Unable to encode attribute [{$key}] for entity [{$class}] to JSON: {$message}."
            );
        }

        return $value;
    }

    /**
     * Get the type of cast for a model attribute.
     *
     * @param string $key
     * @return string
     */
    protected function getCastType(string $key): string
    {
        if ($this->isCustomDateTimeCast($this->getCasts()[$key])) {
            return 'custom_datetime';
        }

        if ($this->isDecimalCast($this->getCasts()[$key])) {
            return 'decimal';
        }

        return strtolower(trim($this->getCasts()[$key]));
    }

    /**
     * Determine if the cast type is a custom date time cast.
     *
     * @param string $cast
     * @return bool
     */
    protected function isCustomDateTimeCast(string $cast): bool
    {
        return strncmp($cast, 'date:', 5) === 0 ||
            strncmp($cast, 'datetime:', 9) === 0;
    }

    /**
     * Determine if the cast type is a decimal cast.
     *
     * @param string $cast
     * @return bool
     */
    protected function isDecimalCast(string $cast): bool
    {
        return strncmp($cast, 'decimal:', 8) === 0;
    }

    /**
     * Determine whether a value is Date / DateTime castable for inbound manipulation.
     *
     * @param string $key
     * @return bool
     */
    protected function isDateCastable(string $key): bool
    {
        return $this->hasCast($key, ['date', 'datetime']);
    }

    /**
     * Determine if the given attribute is a date or date castable.
     *
     * @param string $key
     * @return bool
     */
    protected function isDateAttribute(string $key): bool
    {
        return in_array($key, $this->getDates(), true) ||
            $this->isDateCastable($key);
    }

    /**
     * Determine whether a value is JSON castable for inbound manipulation.
     *
     * @param string $key
     * @return bool
     */
    protected function isJsonCastable(string $key): bool
    {
        return $this->hasCast($key, ['array', 'json', 'object']);
    }

    /**
     * Determine if the given value is a standard date format.
     *
     * @param string $value
     * @return bool|int
     */
    protected function isStandardDateFormat(string $value): bool|int
    {
        return preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $value);
    }

    /**
     * Decode the given JSON back into an array or object.
     *
     * @SuppressWarnings(PHPMD)
     * @param string $value
     * @param bool   $asObject
     * @return                  mixed
     * @throws JsonException
     */
    protected function fromJson(string $value, bool $asObject = false): mixed
    {
        return json_decode($value, !$asObject, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Decode the given float.
     *
     * @param  mixed $value
     * @return float
     */
    protected function fromFloat(mixed $value): float
    {
        return match ((string)$value) {
            'Infinity' => INF,
            '-Infinity' => -INF,
            'NaN' => NAN,
            default => (float)$value,
        };
    }

    /**
     * Convert a DateTime to a storable string.
     *
     * @param  mixed $value
     * @return string|null
     */
    protected function fromDateTime(mixed $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        return $this->asDateTime($value)->format($this->getDateFormat());
    }

    /**
     * Encode the given value as JSON.
     *
     * @param mixed $value
     * @return false|string
     * @throws JsonException
     */
    protected function asJson(mixed $value): false|string
    {
        return json_encode($value, JSON_THROW_ON_ERROR);
    }

    /**
     * Return a decimal as string.
     *
     * @param float $value
     * @param int   $decimals
     * @return string
     */
    protected function asDecimal(float $value, int $decimals): string
    {
        return number_format($value, $decimals, '.', '');
    }

    /**
     * Return a timestamp as DateTime object with time set to 00:00:00.
     *
     * @param  mixed $value
     * @return Carbon
     */
    protected function asDate(mixed $value): Carbon
    {
        return $this->asDateTime($value)->startOfDay();
    }

    /**
     * Return a timestamp as DateTime object.
     *
     * @param  mixed $value
     * @return Carbon|null
     */
    protected function asDateTime(mixed $value): ?Carbon
    {
        // If this value is already a Carbon instance, we shall just return it as is.
        // This prevents us having to re-instantiate a Carbon instance when we know
        // it already is one, which wouldn't be fulfilled by the DateTime check.
        if ($value instanceof CarbonInterface) {
            return Carbon::instance($value);
        }

        // If the value is already a DateTime instance, we will just skip the rest of
        // these checks since they will be a waste of time, and hinder performance
        // when checking the field. We will just return the DateTime right away.
        if ($value instanceof DateTimeInterface) {
            return Carbon::parse(
                $value->format('Y-m-d H:i:s.u'),
                $value->getTimezone()
            );
        }

        // If this value is an integer, we will assume it is a UNIX timestamp's value
        // and format a Carbon object from this timestamp. This allows flexibility
        // when defining your date fields as they might be UNIX timestamps here.
        if (is_numeric($value)) {
            return Carbon::createFromTimestamp($value);
        }

        // If the value is in simply year, month, day format, we will instantiate the
        // Carbon instances from that format. Again, this provides for simple date
        // fields on the database, while still supporting Carbonized conversion.
        if ($this->isStandardDateFormat($value)) {
            $date = Carbon::createFromFormat('Y-m-d', $value);
            if (!$date) {
                return null;
            }

            return Carbon::instance($date)->startOfDay();
        }

        $format = $this->getDateFormat();

        // https://bugs.php.net/bug.php?id=75577
        if (version_compare(PHP_VERSION, '7.3.0-dev', '<')) {
            $format = str_replace('.v', '.u', $format);
        }

        // Finally, we will just assume this date is in the format used by default on
        // the database connection and use that format to create the Carbon object
        // that is returned back out to the developers after we convert it here.
        $date = Carbon::createFromFormat($format, $value);
        return $date ?: null;
    }


    /**
     * Return a timestamp as unix timestamp.
     *
     * @param  mixed $value
     * @return int
     */
    protected function asTimestamp(mixed $value): int
    {
        return $this->asDateTime($value)->getTimestamp();
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format($this->getDateFormat());
    }
}
