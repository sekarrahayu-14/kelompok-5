<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../app/Models/Produk.php';
require_once __DIR__ . '/../app/Models/KategoriProduk.php';

function jsonResponse(array $data, int $code = 200): never
{
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$pos = strpos($path, 'api/');
$path = $pos === false ? $path : substr($path, $pos + 4);
$segments = $path === '' ? [] : explode('/', $path);
$resource = $segments[0] ?? '';
$id = isset($segments[1]) ? (int) $segments[1] : null;
$input = json_decode(file_get_contents('php://input'), true) ?? [];

$model = match ($resource) {
    'produk' => new Produk(),
    'kategori' => new KategoriProduk(),
    default => null
};
if (!$model) jsonResponse(['status' => 'error', 'message' => 'Resource tidak ditemukan'], 404);

try {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            if ($id) {
                $data = $model->find($id);
                if (!$data) jsonResponse(['status' => 'error', 'message' => 'Data tidak ditemukan'], 404);
                jsonResponse(['status' => 'success', 'data' => $data]);
            }
            jsonResponse(['status' => 'success', 'data' => $model->all()]);
        case 'POST':
            if (!$model->create($input)) jsonResponse(['status' => 'error', 'message' => 'Data gagal ditambahkan'], 400);
            jsonResponse(['status' => 'success', 'message' => 'Data berhasil ditambahkan'], 201);
        case 'PUT':
        case 'PATCH':
            if (!$id) jsonResponse(['status' => 'error', 'message' => 'ID wajib diisi'], 400);
            if (!$model->find($id)) jsonResponse(['status' => 'error', 'message' => 'Data tidak ditemukan'], 404);
            $model->update($id, $input);
            jsonResponse(['status' => 'success', 'message' => 'Data berhasil diperbarui']);
        case 'DELETE':
            if (!$id) jsonResponse(['status' => 'error', 'message' => 'ID wajib diisi'], 400);
            if (!$model->find($id)) jsonResponse(['status' => 'error', 'message' => 'Data tidak ditemukan'], 404);
            $model->delete($id);
            jsonResponse(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        default:
            jsonResponse(['status' => 'error', 'message' => 'Method tidak diizinkan'], 405);
    }
} catch (Throwable $e) {
    jsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
}
