<?php

namespace App\Controllers;
use App\Models\LayananModel;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;

class Home extends BaseController
{

    protected $layanan;
    protected $transaction;
    protected $transaction_detail;

    public function __construct()
    {
        helper('number');
        helper('form');
        $this->layanan = new LayananModel();
        $this->transaction = new TransactionModel();
        $this->transaction_detail = new TransactionDetailModel();
    }

    public function index(): string
    {
        $data = [
            'layanan' => $this->layanan->findAll(),
            'title' => 'Home'
        ];
        return view('v_home', $data);
    }
    public function profile()
    {
        $username = session()->get('username');
        $buy = $this->transaction->where('username', $username)->findAll();
        $product = [];

        if (!empty($buy)) {
            foreach ($buy as $item) {
                // Bergabung dengan tabel product untuk mendapatkan nama produk
                $detail = $this->transaction_detail
                                ->select('transaction_detail.*, layanan.nama as nama_layanan')
                                ->join('layanan', 'layanan.id = transaction_detail.product_id')
                                ->where('transaction_id', $item['id'])
                                ->findAll();
                $product[$item['id']] = $detail;
            }
        }
        
        $data = [
            'buy' => $buy,
            'product' => $product,
            'title' => 'Profil Pengguna'
        ];

        return view('v_profile', $data);
    }

}
