<?php

namespace App\Service;

class HTTPHelper
{
    public const HTTP_URL_REPLACE = 1;
    public const HTTP_URL_JOIN_PATH = 2;
    public const HTTP_URL_JOIN_QUERY = 4;
    public const HTTP_URL_STRIP_USER = 8;
    public const HTTP_URL_STRIP_PASS = 16;
    public const HTTP_URL_STRIP_AUTH = 32;
    public const HTTP_URL_STRIP_PORT = 64;
    public const HTTP_URL_STRIP_PATH = 128;
    public const HTTP_URL_STRIP_QUERY = 256;
    public const HTTP_URL_STRIP_FRAGMENT = 512;
    public const HTTP_URL_STRIP_ALL = 1024;

    /**
     * Build a URL.
     *
     * The parts of the second URL will be merged into the first according to
     * the flags' argument.
     *
     * @param mixed $url (part(s) of) an URL in form of a string or
     *                   associative array like parse_url() returns
     * @param array{
     *     scheme?: string,
     *     host?: string,
     *     port?: int|null,
     *     user?: string,
     *     pass?: string,
     *     path?: string,
     *     query?: string,
     *     fragment?: string,
     * } $parts same as the first argument
     * @param int $flags a bitmask of binary or 'd self::HTTP_URL constants;
     *                   self::HTTP_URL_REPLACE is the default
     */
    public static function buildUrl(mixed $url, array $parts = [], int $flags = self::HTTP_URL_REPLACE): string
    {
        is_array($url) || $url = parse_url($url);
        isset($url['query']) && is_string($url['query']) || $url['query'] = null;

        $keys = ['user', 'pass', 'port', 'path', 'query', 'fragment'];

        // HTTP_URL_STRIP_ALL and HTTP_URL_STRIP_AUTH cover several other flags.
        if ($flags & self::HTTP_URL_STRIP_ALL) {
            $flags |= self::HTTP_URL_STRIP_USER | self::HTTP_URL_STRIP_PASS
                | self::HTTP_URL_STRIP_PORT | self::HTTP_URL_STRIP_PATH
                | self::HTTP_URL_STRIP_QUERY | self::HTTP_URL_STRIP_FRAGMENT;
        } elseif ($flags & self::HTTP_URL_STRIP_AUTH) {
            $flags |= self::HTTP_URL_STRIP_USER | self::HTTP_URL_STRIP_PASS;
        }

        // Schema and host are always replaced
        foreach (['scheme', 'host'] as $part) {
            if (isset($parts[$part])) {
                $url[$part] = $parts[$part];
            }
        }

        if ($flags & self::HTTP_URL_REPLACE) {
            foreach ($keys as $key) {
                if (isset($parts[$key])) {
                    $url[$key] = $parts[$key];
                }
            }
        } else {
            if (isset($parts['path']) && ($flags & self::HTTP_URL_JOIN_PATH)) {
                if (isset($url['path']) && !str_starts_with($parts['path'], '/')) {
                    // Workaround for trailing slashes
                    $url['path'] .= 'a';
                    $url['path'] = rtrim(
                        str_replace(basename($url['path']), '', $url['path']),
                        '/'
                    ) . '/' . ltrim($parts['path'], '/');
                } else {
                    $url['path'] = $parts['path'];
                }
            }

            if (isset($parts['query']) && ($flags & self::HTTP_URL_JOIN_QUERY)) {
                if (isset($url['query'])) {
                    parse_str($url['query'], $url_query);
                    parse_str($parts['query'], $parts_query);

                    $url['query'] = http_build_query(
                        array_replace_recursive(
                            $url_query,
                            $parts_query
                        )
                    );
                } else {
                    $url['query'] = $parts['query'];
                }
            }
        }

        if (isset($url['path']) && '' !== $url['path'] && !str_starts_with($url['path'], '/')) {
            $url['path'] = '/' . $url['path'];
        }

        foreach ($keys as $key) {
            $strip = 'self::HTTP_URL_STRIP_' . strtoupper($key);
            if ($flags & constant($strip)) {
                unset($url[$key]);
            }
        }

        $parsed_string = '';

        if (!empty($url['scheme'])) {
            $parsed_string .= $url['scheme'] . '://';
        }

        if (!empty($url['user'])) {
            $parsed_string .= $url['user'];

            if (isset($url['pass'])) {
                $parsed_string .= ':' . $url['pass'];
            }

            $parsed_string .= '@';
        }

        if (!empty($url['host'])) {
            $parsed_string .= $url['host'];
        }

        if (!empty($url['port'])) {
            $parsed_string .= ':' . $url['port'];
        }

        if (!empty($url['path'])) {
            $parsed_string .= $url['path'];
        }

        if (!empty($url['query'])) {
            $parsed_string .= '?' . $url['query'];
        }

        if (!empty($url['fragment'])) {
            $parsed_string .= '#' . $url['fragment'];
        }

        return $parsed_string;
    }
}
