<?php

namespace Auth\FillableAttributeSets;

use Shared\Support\FillableAttributeSet;

class UserCreatorFillableAttributeSet extends FillableAttributeSet
{
    /**
     * @return array
     */
    protected function makeFillableAttributes(): array
    {
        return [
            'name',
            'email',
            'password',
            'password_confirmation'
        ];
    }
}
