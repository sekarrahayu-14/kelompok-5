<?php

abstract class Controller
{
    protected function render($view, array $data = [])
    {
        extract($data);
        $viewFile = dirname(__DIR__) . '/Views/' . $view . '.php';
        if (!is_file($viewFile)) {
            throw new RuntimeException('View tidak ditemukan.');
        }
        require $viewFile;
    }

    protected function redirect($path)
    {
        header('Location: ' . $path);
        exit;
    }

    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    protected function requestData(): array
    {
        $content = file_get_contents('php://input');
        $data = json_decode($content ?: '', true);

        return is_array($data) ? $data : $_POST;
    }
}
