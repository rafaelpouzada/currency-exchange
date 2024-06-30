<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Shared\Http\BaseController;

class HealthcheckController extends BaseController
{
    /**
     * @return JsonResponse
     */
    public function healthcheck(): JsonResponse
    {
        return $this->getResponse()->dispatch(function () {
            return [
                'status'              => 'ok',
                'server_date'         => now()->format('Y-m-d H:i:s'),
                'version'             => '{APP_VERSION}',
                'version_created_at'  => '{APP_VERSION_DATE}',
                'ip'                  => $this->getPublicIp()
            ];
        });
    }

    /**
     * @return string|null
     */
    protected function getPublicIp(): ?string
    {
        $file = base_path('public-ip.txt');
        if (!file_exists($file)) {
            return null;
        }
        return file_get_contents($file) ?: null;
    }
}
