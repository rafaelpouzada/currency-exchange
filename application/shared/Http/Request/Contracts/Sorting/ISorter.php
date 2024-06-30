<?php

namespace Shared\Http\Request\Contracts\Sorting;

use Illuminate\Contracts\Support\Arrayable;

interface ISorter extends Arrayable
{
    /**
     * @return ISort[]
     */
    public function getSorting(): array;
}
