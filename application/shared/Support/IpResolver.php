<?php

namespace Shared\Support;

class IpResolver
{
    /**
     * @SuppressWarnings("Superglobals")
     *
     * @return string|null
     */
    public static function getIp(): ?string
    {
        $entries = [
            'HTTP_X_REAL_IP',
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_FORWARDED',
        ];

        $ip = with($entries, function ($entries) {
            foreach ($entries as $entry) {
                if (isset($_SERVER[$entry])) {
                    return $_SERVER[$entry];
                }
            }
            return $_SERVER['REMOTE_ADDR'] ?? null;
        });

        if (str_contains($ip, ",")) {
            $pieces = explode(",", $ip);
            $ip     = $pieces[0];
        }

        if (empty($ip)) {
            $hostname = gethostname();
            if (!$hostname) {
                return null;
            }
            $ip = gethostbyname($hostname);
        }

        return trim($ip);
    }
}
