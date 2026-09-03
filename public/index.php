<?php

define('SITOKO_ROOT', dirname(__DIR__));
require SITOKO_ROOT . '/app/Core/Database.php';
require SITOKO_ROOT . '/app/Core/BaseModel.php';
require SITOKO_ROOT . '/app/Core/Controller.php';
require SITOKO_ROOT . '/app/Core/Router.php';

foreach (glob(SITOKO_ROOT . '/app/Models/*.php') as $file) {
	require_once $file;
}
foreach (glob(SITOKO_ROOT . '/app/Controllers/*.php') as $file) {
	require_once $file;
}

$router = new Router();
$router->get('/', function () {
	$products = (new Produk())->getAll();
	$transactions = (new Transaksi())->getTransaksi();
	require SITOKO_ROOT . '/app/Views/dashboard.php';
});

$router->get('/login', function () { require SITOKO_ROOT . '/app/Views/login.php'; });

$router->get('/produk', function () { $products = (new ProdukController(new Produk()))->index(); require SITOKO_ROOT . '/app/Views/produk.php'; });
$router->get('/kategori', function () { $categories = (new KategoriController(new Kategori()))->index(); require SITOKO_ROOT . '/app/Views/kategori.php'; });
$router->get('/pelanggan', function () { $customers = (new PelangganController(new Pelanggan()))->index(); require SITOKO_ROOT . '/app/Views/pelanggan.php'; });
$router->get('/kasir', function () { $cashiers = (new KasirController(new Kasir()))->index(); require SITOKO_ROOT . '/app/Views/kasir.php'; });
$router->get('/transaksi', function () { $products = (new Produk())->getAll(); $customers = (new Pelanggan())->getAll(); require SITOKO_ROOT . '/app/Views/transaksi.php'; });
$router->get('/laporan', function () {
	$controller = new LaporanController(new Laporan());
	$report = $controller->index($_GET['mulai'] ?? null, $_GET['selesai'] ?? null);
	extract($report);
	require SITOKO_ROOT . '/app/Views/laporan.php';
});

$router->post('/produk', function () { (new ProdukController(new Produk()))->store($_POST); header('Location: /kelompok-5/produk?success=Produk%20disimpan'); });
$router->post('/kategori', function () { (new KategoriController(new Kategori()))->store($_POST); header('Location: /kelompok-5/kategori?success=Kategori%20disimpan'); });
$router->post('/pelanggan', function () { (new PelangganController(new Pelanggan()))->store($_POST); header('Location: /kelompok-5/pelanggan?success=Pelanggan%20disimpan'); });
$router->post('/kasir', function () { (new KasirController(new Kasir()))->store($_POST); header('Location: /kelompok-5/kasir?success=Kasir%20disimpan'); });
$router->post('/transaksi', function () { $id = (new TransaksiController(new Transaksi()))->store($_POST); header('Location: /kelompok-5/struk?id=' . urlencode($id)); });
$router->post('/produk/update', function () { (new ProdukController(new Produk()))->update($_POST['id_produk'], $_POST); header('Location: /kelompok-5/produk?success=Produk%20diperbarui'); });
$router->post('/kategori/update', function () { (new KategoriController(new Kategori()))->update($_POST['id_kategori'], $_POST); header('Location: /kelompok-5/kategori?success=Kategori%20diperbarui'); });
$router->post('/pelanggan/update', function () { (new PelangganController(new Pelanggan()))->update($_POST['id_pelanggan'], $_POST); header('Location: /kelompok-5/pelanggan?success=Pelanggan%20diperbarui'); });
$router->post('/kasir/update', function () { (new KasirController(new Kasir()))->update($_POST['id_kasir'], $_POST); header('Location: /kelompok-5/kasir?success=Kasir%20diperbarui'); });
$router->post('/produk/delete', function () { (new ProdukController(new Produk()))->delete($_POST['id']); header('Location: /kelompok-5/produk?success=Produk%20dihapus'); });
$router->post('/kategori/delete', function () { (new KategoriController(new Kategori()))->delete($_POST['id']); header('Location: /kelompok-5/kategori?success=Kategori%20dihapus'); });
$router->post('/pelanggan/delete', function () { (new PelangganController(new Pelanggan()))->delete($_POST['id']); header('Location: /kelompok-5/pelanggan?success=Pelanggan%20dihapus'); });
$router->post('/kasir/delete', function () { (new KasirController(new Kasir()))->delete($_POST['id']); header('Location: /kelompok-5/kasir?success=Kasir%20dihapus'); });
$router->get('/struk', function () {
	$rows = (new Transaksi())->getDetail($_GET['id'] ?? 0);
	require SITOKO_ROOT . '/app/Views/struk.php';
});

try {
	$requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
	$basePath = rtrim(str_replace('\\', '/', dirname(dirname($_SERVER['SCRIPT_NAME'] ?? '/public/index.php'))), '/');
	if ($basePath !== '' && $basePath !== '/' && strpos($requestUri, $basePath) === 0) {
		$requestUri = substr($requestUri, strlen($basePath)) ?: '/';
	}
	$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $requestUri);
} catch (Throwable $exception) {
	http_response_code(400);
	$error = $exception->getMessage();
	require SITOKO_ROOT . '/app/Views/dashboard.php';
}
