<?php

namespace CurrencyConversion\Repositories\Contracts;

use Shared\Repositories\Contracts\Behaviors\Findable;
use Shared\Repositories\Contracts\Behaviors\Paginable;
use Shared\Repositories\Contracts\Behaviors\Storable;

interface ICurrencyConversionRepository extends Findable, Paginable, Storable
{

}
