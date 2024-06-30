<?php

namespace Shared\Http\Request\Contracts\Sorting;

use Illuminate\Contracts\Support\Arrayable;

interface ISort extends Arrayable
{
    /**
     * Retorna o campo usado para ordenar.
     *
     * @return string
     */
    public function getField(): string;

    /**
     * Retorna a direção de ordenação.
     *
     * @return string
     */
    public function getDirection(): string;
}
