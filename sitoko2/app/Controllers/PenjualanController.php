<?php
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/Penjualan.php';
require_once __DIR__ . '/../Models/Produk.php';

class PenjualanController extends Controller
{
    private Penjualan $penjualan;
    private Produk $produk;

    public function __construct()
    {
        $this->penjualan = new Penjualan();
        $this->produk = new Produk();
    }

    public function index(): void
    {
        $this->view('penjualan/index', ['penjualan' => $this->penjualan->all()]);
    }

    public function create(): void
    {
        $this->view('penjualan/form', ['produk' => $this->produk->all()]);
    }

    public function store(): void
    {
        try {
            $this->penjualan->create($_POST);
            $this->redirect('/sitoko/penjualan');
        } catch (Throwable $e) {
            http_response_code(400);
            exit($e->getMessage());
        }
    }
}
