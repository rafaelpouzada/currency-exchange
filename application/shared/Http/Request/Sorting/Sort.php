<?php

namespace Shared\Http\Request\Sorting;

use Shared\Http\Request\Contracts\Sorting\ISort;

class Sort implements ISort
{
    /**
     * Campo usado na ordenação.
     *
     * @var string $field
     */
    protected string $field;

    /**
     * Direção da ordenação.
     *
     * @var string $direction
     */
    protected string $direction;

    /**
     * Sort constructor.
     *
     * @param string $field
     * @param string $direction
     */
    public function __construct(string $field, string $direction = 'asc')
    {
        $this->field     = $field;
        $this->direction = $direction;
    }

    /**
     * {@inheritDoc}
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * {@inheritDoc}
     */
    public function getDirection(): string
    {
        return $this->direction;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'field'     => $this->field,
            'direction' => $this->direction
        ];
    }
}
