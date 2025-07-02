<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class LayananController extends BaseController
{
    protected $layanan;

    function __construct()
    {
        // Pastikan hanya admin yang bisa akses controller ini
        if(session()->get('role') != 'admin'){
            echo 'Akses Ditolak!';
            exit; 
        }
        
        helper('form');
        $this->layanan = new LayananModel();
    }

    public function index()
    {
        $data = [
            'layanan' => $this->layanan->findAll(),
            'title' => 'Manajemen Layanan'
        ];
        return view('v_layanan', $data);
    }

    public function create()
    {
        $dataForm = [
            'nama' => $this->request->getPost('nama'),
            'harga' => $this->request->getPost('harga'),
            'estimasi_menit' => $this->request->getPost('estimasi_menit'),
        ];

        $foto = $this->request->getFile('foto');
        if ($foto->isValid() && !$foto->hasMoved()) {
            $fileName = $foto->getRandomName();
            $foto->move('img/', $fileName);
            $dataForm['foto'] = $fileName;
        }

        $this->layanan->insert($dataForm);
        return redirect('layanan')->with('success', 'Data Layanan Berhasil Ditambah');
    }

    public function edit($id)
    {
        $dataForm = [
            'nama' => $this->request->getPost('nama'),
            'harga' => $this->request->getPost('harga'),
            'estimasi_menit' => $this->request->getPost('estimasi_menit'),
        ];

        if ($this->request->getPost('check')) {
            $foto = $this->request->getFile('foto');
            if ($foto->isValid() && !$foto->hasMoved()) {
                $dataLayanan = $this->layanan->find($id);
                if ($dataLayanan['foto'] != '' && file_exists("img/" . $dataLayanan['foto'])) {
                    unlink("img/" . $dataLayanan['foto']);
                }
                $fileName = $foto->getRandomName();
                $foto->move('img/', $fileName);
                $dataForm['foto'] = $fileName;
            }
        }

        $this->layanan->update($id, $dataForm);
        return redirect('layanan')->with('success', 'Data Layanan Berhasil Diubah');
    }

    public function delete($id)
    {
        $dataLayanan = $this->layanan->find($id);
        if ($dataLayanan['foto'] != '' && file_exists("img/" . $dataLayanan['foto'])) {
            unlink("img/" . $dataLayanan['foto']);
        }
        $this->layanan->delete($id);
        return redirect('layanan')->with('success', 'Data Layanan Berhasil Dihapus');
    }
}
