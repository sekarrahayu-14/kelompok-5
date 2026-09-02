<?php
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/Produk.php';
require_once __DIR__ . '/../Models/KategoriProduk.php';

class ProdukController extends Controller
{
    private Produk $produk;
    private KategoriProduk $kategori;

    public function __construct()
    {
        $this->produk = new Produk();
        $this->kategori = new KategoriProduk();
    }

    public function index(): void
    {
        $this->view('produk/index', ['produk' => $this->produk->all()]);
    }

    public function create(): void
    {
        $this->view('produk/form', ['title' => 'Tambah Produk', 'produk' => null, 'kategori' => $this->kategori->all(), 'action' => '/sitoko/produk/simpan']);
    }

    public function edit(int $id): void
    {
        $item = $this->produk->find($id);
        if (!$item) { http_response_code(404); exit('Produk tidak ditemukan.'); }
        $this->view('produk/form', ['title' => 'Ubah Produk', 'produk' => $item, 'kategori' => $this->kategori->all(), 'action' => '/sitoko/produk/update/' . $id]);
    }

    public function store(): void
    {
        $this->produk->create($_POST);
        $this->redirect('/sitoko/produk');
    }

    public function update(int $id): void
    {
        $this->produk->update($id, $_POST);
        $this->redirect('/sitoko/produk');
    }

    public function destroy(int $id): void
    {
        $this->produk->delete($id);
        $this->redirect('/sitoko/produk');
    }
}
