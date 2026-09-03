<?php
abstract class Controller
{
    protected function view(string $view, array $data = []): void
    {
        require __DIR__ . '/../Views/' . $view . '.php';
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }
}
