<?php

namespace CurrencyConversion\Http\Controllers;

use CurrencyConversion\Business\CurrencyConversionCreator\Facades\CurrencyConversionCreator;
use CurrencyConversion\Business\CurrencyConversionFinder\Facades\CurrencyConversionFinder;
use Illuminate\Http\JsonResponse;
use Shared\Http\BaseController;

class CurrencyConversionController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->getResponse()->dispatch(function () {
            $query = $this->getQuery()->toArray();
            return CurrencyConversionFinder::paginate($query);
        });

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        return $this->getResponse()->dispatch(function () use ($id) {
            return CurrencyConversionFinder::find($id);
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResponse
     */
    public function store(): JsonResponse
    {
        return $this->getResponse()->dispatch(function () {
            $attributes = $this->getPostAttributes();
            return CurrencyConversionCreator::store($attributes);
        });
    }
}
