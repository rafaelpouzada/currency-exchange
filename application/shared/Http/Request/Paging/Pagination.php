<?php

namespace Shared\Http\Request\Paging;

use Illuminate\Http\Request;
use Shared\Http\Request\Contracts\Paging\IPagination;

class Pagination implements IPagination
{
    /**
     * Número de itens por página
     *
     * @var int
     */
    protected int $size = 25;

    /**
     * Número da página
     *
     * @var int
     */
    protected int $number = 1;

    /**
     * Paginator constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->setNumber($request->input('page.number', 1));
        $this->setSize($request->input('page.size', 25));
    }

    /**
     * {@inheritDoc}
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * Define o tamanho da paginação.
     *
     * @param int $size
     * @return void
     */
    private function setSize(int $size): void
    {
        if (!is_numeric($size) || $size < 1) {
            $size = 25;
        }

        $this->size = $size;
    }

    /**
     * {@inheritDoc}
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * Define o número da paginação.
     *
     * @param int $number
     * @return void
     */
    private function setNumber(int $number): void
    {
        if (!is_numeric($number) || $number < 1) {
            $number = 1;
        }

        $this->number = $number;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'size'   => $this->size,
            'number' => $this->number,
        ];
    }
}
