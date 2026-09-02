<?php
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/KategoriProduk.php';

class KategoriController extends Controller
{
    private KategoriProduk $kategori;

    public function __construct()
    {
        $this->kategori = new KategoriProduk();
    }

    public function index(): void
    {
        $this->view('kategori/index', ['kategori' => $this->kategori->all()]);
    }

    public function store(): void
    {
        $this->kategori->create($_POST);
        $this->redirect('/sitoko/kategori');
    }

    public function destroy(int $id): void
    {
        $this->kategori->delete($id);
        $this->redirect('/sitoko/kategori');
    }
}
