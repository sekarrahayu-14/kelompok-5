<?php

session_start();

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
$requireAuth = function () {
	if (empty($_SESSION['kasir_id'])) {
		header('Location: /kelompok-5/app/Views/login.php');
		exit;
	}
};

$router->get('/', function () {
	if (!empty($_SESSION['kasir_id'])) {
		$products = (new Produk())->getAll();
		$transactions = (new Transaksi())->getTransaksi();
		require SITOKO_ROOT . '/app/Views/dashboard.php';
		return;
	}

	header('Location: /kelompok-5/app/Views/login.php');
	exit;
});

$router->get('/login', function () {
	if (!empty($_SESSION['kasir_id'])) {
		header('Location: /kelompok-5/app/Views/dashboard.php');
		exit;
	}

	require SITOKO_ROOT . '/app/Views/login.php';
});

$router->get('/logout', function () {
	// Hapus seluruh data autentikasi sebelum mengakhiri session.
	$_SESSION = [];
	if (ini_get('session.use_cookies')) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
	}
	session_unset();
	session_destroy();
	header('Location: /kelompok-5/app/Views/login.php?logout=1');
	exit;
});

$router->post('/login', function () {
	$username = trim((string) ($_POST['username'] ?? ''));
	$password = (string) ($_POST['password'] ?? '');
	$user = (new Kasir())->verifyLogin($username, $password);

	if ($user) {
		$_SESSION['kasir_id'] = (int) $user['id_kasir'];
		$_SESSION['kasir_username'] = $user['username'];
		$_SESSION['kasir_nama'] = $user['nama_kasir'];
		header('Location: /kelompok-5/app/Views/dashboard.php');
		exit;
	}

	header('Location: /kelompok-5/app/Views/login.php?error=1');
	exit;
});

$router->get('/produk', function () use ($requireAuth) { $requireAuth(); $products = (new ProdukController(new Produk()))->index(); require SITOKO_ROOT . '/app/Views/produk.php'; });
$router->get('/kategori', function () use ($requireAuth) { $requireAuth(); $categories = (new KategoriController(new Kategori()))->index(); require SITOKO_ROOT . '/app/Views/kategori.php'; });
$router->get('/pelanggan', function () use ($requireAuth) { $requireAuth(); $customers = (new PelangganController(new Pelanggan()))->index(); require SITOKO_ROOT . '/app/Views/pelanggan.php'; });
$router->get('/kasir', function () use ($requireAuth) { $requireAuth(); $cashiers = (new KasirController(new Kasir()))->index(); require SITOKO_ROOT . '/app/Views/kasir.php'; });
$router->get('/transaksi', function () use ($requireAuth) { $requireAuth(); $products = (new Produk())->getAll(); $customers = (new Pelanggan())->getAll(); require SITOKO_ROOT . '/app/Views/transaksi.php'; });
$router->get('/laporan', function () use ($requireAuth) {
	$requireAuth();
	$controller = new LaporanController(new Laporan());
	$report = $controller->index($_GET['mulai'] ?? null, $_GET['selesai'] ?? null);
	extract($report);
	require SITOKO_ROOT . '/app/Views/laporan.php';
});

$router->post('/produk', function () use ($requireAuth) { $requireAuth(); (new ProdukController(new Produk()))->store($_POST); header('Location: /kelompok-5/produk?success=Produk%20disimpan'); });
$router->post('/kategori', function () use ($requireAuth) { $requireAuth(); (new KategoriController(new Kategori()))->store($_POST); header('Location: /kelompok-5/kategori?success=Kategori%20disimpan'); });
$router->post('/pelanggan', function () use ($requireAuth) { $requireAuth(); (new PelangganController(new Pelanggan()))->store($_POST); header('Location: /kelompok-5/pelanggan?success=Pelanggan%20disimpan'); });
$router->post('/kasir', function () use ($requireAuth) { $requireAuth(); (new KasirController(new Kasir()))->store($_POST); header('Location: /kelompok-5/kasir?success=Kasir%20disimpan'); });
$router->post('/transaksi', function () use ($requireAuth) { $requireAuth(); $input = $_POST; $input['id_kasir'] = $_SESSION['kasir_id']; $id = (new TransaksiController(new Transaksi()))->store($input); header('Location: /kelompok-5/struk?id=' . urlencode($id)); });
$router->post('/produk/update', function () use ($requireAuth) { $requireAuth(); (new ProdukController(new Produk()))->update($_POST['id_produk'], $_POST); header('Location: /kelompok-5/produk?success=Produk%20diperbarui'); });
$router->post('/kategori/update', function () use ($requireAuth) { $requireAuth(); (new KategoriController(new Kategori()))->update($_POST['id_kategori'], $_POST); header('Location: /kelompok-5/kategori?success=Kategori%20diperbarui'); });
$router->post('/pelanggan/update', function () use ($requireAuth) { $requireAuth(); (new PelangganController(new Pelanggan()))->update($_POST['id_pelanggan'], $_POST); header('Location: /kelompok-5/pelanggan?success=Pelanggan%20diperbarui'); });
$router->post('/kasir/update', function () use ($requireAuth) { $requireAuth(); (new KasirController(new Kasir()))->update($_POST['id_kasir'], $_POST); header('Location: /kelompok-5/kasir?success=Kasir%20diperbarui'); });
$router->post('/produk/delete', function () use ($requireAuth) { $requireAuth(); (new ProdukController(new Produk()))->delete($_POST['id']); header('Location: /kelompok-5/produk?success=Produk%20dihapus'); });
$router->post('/kategori/delete', function () use ($requireAuth) { $requireAuth(); (new KategoriController(new Kategori()))->delete($_POST['id']); header('Location: /kelompok-5/kategori?success=Kategori%20dihapus'); });
$router->post('/pelanggan/delete', function () use ($requireAuth) { $requireAuth(); (new PelangganController(new Pelanggan()))->delete($_POST['id']); header('Location: /kelompok-5/pelanggan?success=Pelanggan%20dihapus'); });
$router->post('/kasir/delete', function () use ($requireAuth) { $requireAuth(); (new KasirController(new Kasir()))->delete($_POST['id']); header('Location: /kelompok-5/kasir?success=Kasir%20dihapus'); });
$router->get('/struk', function () use ($requireAuth) {
	$requireAuth();
	$rows = (new Transaksi())->getDetail($_GET['id'] ?? 0);
	require SITOKO_ROOT . '/app/Views/struk.php';
});

try {
	$requestUri = isset($_GET['route'])
		? '/' . ltrim((string) $_GET['route'], '/')
		: (parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
	$projectPath = '/kelompok-5';
	if ($requestUri === $projectPath || strpos($requestUri, $projectPath . '/') === 0) {
		$requestUri = substr($requestUri, strlen($projectPath)) ?: '/';
	}

	$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $requestUri);
} catch (Throwable $exception) {
	http_response_code(500);
	$errorMessage = rawurlencode($exception->getMessage());
	$target = '/kelompok-5/';
	if ($requestUri !== '/') {
		$target = '/kelompok-5' . $requestUri;
	}
	header('Location: ' . $target . '?error=' . $errorMessage);
	exit;
}
