<?php

namespace Shared\Http\Request\Contracts\Paging;

use Illuminate\Contracts\Support\Arrayable;

interface IPagination extends Arrayable
{
    /**
     * Retorna o tamanho da paginação.
     *
     * @return int
     */
    public function getSize(): int;

    /**
     * Retorna o número da página.
     *
     * @return int
     */
    public function getNumber(): int;
}
