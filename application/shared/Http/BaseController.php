<?php

namespace Shared\Http;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request;
use Shared\Http\Request\Query;
use Shared\Http\Response\Response;

abstract class BaseController extends Controller
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * @var Query|null
     */
    protected ?Query $query;

    /**
     * @var Response|null
     */
    protected ?Response $response;

    /**
     * @return Query
     */
    protected function getQuery(): Query
    {
        if (!isset($this->query)) {
            /** @var \Illuminate\Http\Request $request */
            $request     = request();
            $this->query = new Query($request);
        }

        return $this->query;
    }

    /**
     * Retorna os filtros da consulta.
     *
     * @return array
     */
    protected function getFilters(): array
    {
        return $this->getQuery()->getFilter()->toArray();
    }

    /**
     * Retorna os atributos enviados em post.
     *
     * @param  string ...$keys
     * @return array
     */
    protected function getPostAttributes(...$keys): array
    {
        $post = Request::post();
        if (!is_array($post)) {
            return [];
        }

        if ($keys) {
            return Arr::only($post, $keys);
        }

        return $post;
    }

    /**
     * Retorna os atributos enviados em requisições.
     *
     * @param  string ...$keys
     * @return array
     */
    protected function getQueryParams(...$keys): array
    {
        if ($keys) {
            return Request::only($keys);
        }

        return Request::all();
    }

    /**
     * Retorna a instância responsável pela resposta ao cliente.
     *
     * @return Response
     */
    protected function getResponse(): Response
    {
        if (!isset($this->response)) {
            $this->response = new Response(app(ResponseFactory::class), $this->getQuery());
        }

        return $this->response;
    }
}
