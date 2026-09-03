<?php

class Router
{
    private $routes = [];

    public function get($path, $handler)
    {
        $this->add('GET', $path, $handler);
    }

    public function post($path, $handler)
    {
        $this->add('POST', $path, $handler);
    }

    public function put($path, $handler)
    {
        $this->add('PUT', $path, $handler);
    }

    public function delete($path, $handler)
    {
        $this->add('DELETE', $path, $handler);
    }

    public function dispatch($method = null, $uri = null)
    {
        $method = strtoupper($method ?? $_SERVER['REQUEST_METHOD'] ?? 'GET');
        $path = parse_url($uri ?? $_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '([^/]+)', $route['path']);
            if (!is_string($pattern) || !preg_match('#^' . $pattern . '$#', $path, $matches)) {
                continue;
            }

            array_shift($matches);
            return call_user_func_array($route['handler'], $matches);
        }

        http_response_code(404);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['message' => 'Route tidak ditemukan.'], JSON_UNESCAPED_UNICODE);
        return null;
    }

    private function add($method, $path, $handler)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => rtrim($path, '/') ?: '/',
            'handler' => $handler,
        ];
    }
}
