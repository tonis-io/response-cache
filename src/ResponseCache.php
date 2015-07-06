<?php
namespace Tonis\ResponseCache;

use Doctrine\Common\Cache\Cache;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ResponseCache
{
    /** @var Cache */
    private $cache;
    /** @var array */
    private $config;

    /**
     * @param Cache $cache
     * @param array $config
     */
    public function __construct(Cache $cache, array $config = [])
    {
        $defaults = ['ttl' => 60];

        $this->cache  = $cache;
        $this->config = array_merge($defaults, $config);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $key  = $this->generateKey($request);
        $data = $this->cache->fetch($key);

        if (false !== $data) {
            list($body, $code, $headers) = unserialize($this->cache->fetch($key));

            $response->getBody()->write($body);
            $response = $response->withStatus($code);

            foreach (unserialize($headers) as $name => $value) {
                $response = $response->withHeader($name, $value);
            }

            return $response;
        }

        $response = $next ? $next($request, $response) : $response;

        // save cache - status code, headers, body
        $body    = $response->getBody()->__toString();
        $code    = $response->getStatusCode();
        $headers = serialize($response->getHeaders());
        $data    = serialize([$body, $code, $headers]);

        $this->cache->save($key, $data, $this->config['ttl']);

        return $response;
    }

    /**
     * Generates a cache key based on a unique request.
     *
     * @todo determine parts required for unique request
     * @param ServerRequestInterface $request
     * @return string
     */
    private function generateKey(ServerRequestInterface $request)
    {
        $params = $request->getQueryParams();
        ksort($params);

        $parts = [
            $request->getMethod(),
            $request->getUri()->getPath(),
            serialize($params)
        ];

        return sha1(implode(':', $parts));
    }
}
