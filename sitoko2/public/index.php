<?php
require_once __DIR__ . '/../app/Controllers/ProdukController.php';
require_once __DIR__ . '/../app/Controllers/KategoriController.php';
require_once __DIR__ . '/../app/Controllers/PenjualanController.php';

$base = '/sitoko2';
$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
if (str_starts_with('/' . $path, $base)) $path = trim(substr('/' . $path, strlen($base)), '/');
$segments = $path === '' ? [] : explode('/', $path);
$module = $segments[0] ?? 'produk';
$action = $segments[1] ?? 'index';
$id = isset($segments[2]) ? (int) $segments[2] : null;

if ($module === 'produk') {
    $controller = new ProdukController();
    if ($action === 'tambah') $controller->create();
    elseif ($action === 'simpan' && $_SERVER['REQUEST_METHOD'] === 'POST') $controller->store();
    elseif ($action === 'edit' && $id) $controller->edit($id);
    elseif ($action === 'update' && $id && $_SERVER['REQUEST_METHOD'] === 'POST') $controller->update($id);
    elseif ($action === 'hapus' && $id) $controller->destroy($id);
    else $controller->index();
} elseif ($module === 'kategori') {
    $controller = new KategoriController();
    if ($action === 'simpan' && $_SERVER['REQUEST_METHOD'] === 'POST') $controller->store();
    elseif ($action === 'hapus' && $id) $controller->destroy($id);
    else $controller->index();
} elseif ($module === 'penjualan') {
    $controller = new PenjualanController();
    if ($action === 'tambah') $controller->create();
    elseif ($action === 'simpan' && $_SERVER['REQUEST_METHOD'] === 'POST') $controller->store();
    else $controller->index();
} else {
    http_response_code(404);
    echo 'Halaman tidak ditemukan.';
}
