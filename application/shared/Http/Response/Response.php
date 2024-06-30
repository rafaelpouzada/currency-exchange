<?php

namespace Shared\Http\Response;

use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\JsonResponse;
use Shared\Http\Request\Query;

class Response
{
    /**
     * @var Query $query
     */
    protected Query $query;

    /**
     * @var ResponseFactory $request
     */
    protected ResponseFactory $request;

    /**
     * Response's constructor
     *
     * @param ResponseFactory $request
     * @param Query $query
     */
    public function __construct(ResponseFactory $request, Query $query)
    {
        $this->request = $request;
        $this->query   = $query;
    }

    /**
     * Formata a conteúdo fornecido para o padrão de retorno da API.
     * @example
     * [
     *      'filtering' => [],
     *      'sorting'   => [],
     *      'paging'    => [],
     *      'data'      => [],
     *      'meta'      => [],
     * ]
     *
     * @param mixed $content
     * @return array
     */
    protected function parse(mixed $content): array
    {
        $response = [
            'filtering' => $this->query->getFilter()->toArray(),
            'sorting'   => $this->query->getSorter()->toArray(),
            'paging'    => $this->getPagination($content),
            'data'      => $this->getData($content),
            'meta'      => $this->getMeta(),
        ];

        return array_filter($response, static function ($entry, $key) {
            return $key === 'data' || !empty($entry);
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * Cria uma resposta com o retorno do método fornecido.
     *
     * @param Closure $closure
     * @param int     $status
     * @param array   $headers
     *
     * @return JsonResponse
     */
    public function dispatch(Closure $closure, int $status = 200, array $headers = []): JsonResponse
    {
        $content = call_user_func($closure);
        $body    = $this->parse($content);

        return $this->request->json($body, $status, $headers);
    }

    /**
     * Retorna a paginação formatada para o padrão da api.
     *
     * @param mixed $content
     * @return mixed
     */
    private function getData(mixed $content): mixed
    {
        if ($content instanceof LengthAwarePaginator) {
            return $content->items();
        }

        if ($content instanceof Arrayable) {
            return $content->toArray();
        }

        return $content;
    }

    /**
     * Retorna a paginação formatada para o padrão da api.
     *
     * @param mixed $content
     * @return array
     */
    private function getPagination(mixed $content): array
    {
        if (!($content instanceof LengthAwarePaginator)) {
            return [];
        }

        return [
            'number' => $content->currentPage(),
            'size'   => $content->perPage(),
            'total'  => $content->total(),
            'from'   => $content->firstItem(),
            'to'     => $content->lastItem(),
            'links' => [
                'first_page' => $content->url(1),
                'prev_page'  => $content->previousPageUrl(),
                'next_page'  => $content->nextPageUrl(),
                'last_page'  => $content->url($content->lastPage()),
            ]
        ];
    }

    /**
     * Retorna o objeto meta
     *
     * @return array $meta
     */
    private function getMeta(): array
    {
        return ['version' => '1.0.0'];
    }
}
