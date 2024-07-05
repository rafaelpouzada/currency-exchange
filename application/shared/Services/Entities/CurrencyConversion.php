<?php

namespace Shared\Services\Entities;

use CurrencyConversion\Enums\Currency;
use Shared\Entities\Entity;

/**
 * @property value-of<Currency> base_code
 * @property value-of<Currency> target_code
 * @property float conversion_rate
 */
class CurrencyConversion extends Entity
{

}
