<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;
use GuzzleHttp\Client;

class TransaksiController extends BaseController
{
    protected $cart;
    protected $client;
    protected $apiKey;
    protected $transaction;
    protected $transaction_detail;

    function __construct()
    {
        helper('number');
        helper('form');
        $this->cart = \Config\Services::cart();
        $this->transaction = new TransactionModel();
        $this->transaction_detail = new TransactionDetailModel();
        
        // Inisialisasi untuk Webservice
        $this->client = new Client();
        $this->apiKey = env('RAJAONGKIR_API_KEY');
    }

    public function add()
    {
        $this->cart->insert(array(
            'id'      => $this->request->getPost('id'),
            'qty'     => 1,
            'price'   => $this->request->getPost('harga'),
            'name'    => $this->request->getPost('nama'),
        ));
        session()->setFlashdata('success', 'Layanan berhasil ditambahkan. Lihat di menu <a href="'.base_url('riwayat').'">Riwayat</a>.');
        return redirect()->to(base_url('/'));
    }

    public function index()
    {
        $data['items'] = $this->cart->contents();
        $data['total'] = $this->cart->total();
        return view('v_riwayat', $data);
    }

    public function delete($rowid)
    {
        $this->cart->remove($rowid);
        session()->setFlashdata('success', 'Layanan berhasil dihapus dari riwayat.');
        return redirect()->to(base_url('riwayat'));
    }

    public function clear()
    {
        $this->cart->destroy();
        session()->setFlashdata('success', 'Riwayat pilihan berhasil dikosongkan.');
        return redirect()->to(base_url('riwayat'));
    }

    // --- FUNGSI BARU UNTUK CHECKOUT & WEBSERVICE ---

    public function checkout()
    {
        $data['items'] = $this->cart->contents();
        $data['total'] = $this->cart->total();
        return view('v_checkout', $data);
    }
    
    // Fungsi untuk AJAX request ke RajaOngkir API
    public function getLocation()
    {
        if ($this->request->isAJAX()){
            $search = $this->request->getGet('search');
            try {
                $response = $this->client->request('GET', 'https://api.rajaongkir.com/starter/city', [
                    'headers' => ['key' => $this->apiKey],
                    'query' => ['q' => $search]
                ]);
                $body = json_decode($response->getBody(), true);
                return $this->response->setJSON($body['rajaongkir']['results']);
            } catch (\Exception $e) {
                return $this->response->setStatusCode(500)->setJSON(['error' => $e->getMessage()]);
            }
        }
    }

    public function getCost()
    {
        if ($this->request->isAJAX()){
            $origin = 152; // ID Kota asal statis (misal: Jakarta Pusat)
            $destination = $this->request->getGet('destination');
            $weight = 1000; // Asumsi berat untuk antar jemput motor
            $courier = 'jne'; // Kurir yang digunakan

            try {
                $response = $this->client->request('POST', 'https://api.rajaongkir.com/starter/cost', [
                    'headers' => ['key' => $this->apiKey, 'content-type' => 'application/x-www-form-urlencoded'],
                    'form_params' => [
                        'origin' => $origin,
                        'destination' => $destination,
                        'weight' => $weight,
                        'courier' => $courier
                    ]
                ]);
                $body = json_decode($response->getBody(), true);
                return $this->response->setJSON($body['rajaongkir']['results'][0]['costs']);
            } catch (\Exception $e) {
                return $this->response->setStatusCode(500)->setJSON(['error' => $e->getMessage()]);
            }
        }
    }
    
    // Fungsi untuk menyimpan pesanan
    public function buy()
    {
        if ($this->request->getPost()) {
            $dataForm = [
                'username' => session()->get('username'),
                'total_harga' => $this->request->getPost('total_harga'),
                'alamat' => $this->request->getPost('alamat'),
                'ongkir' => $this->request->getPost('ongkir'),
                'status' => 0,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ];
            
            // Insert ke tabel transaction
            $this->transaction->insert($dataForm);
            
            // Ambil ID transaksi terakhir
            $last_insert_id = $this->transaction->getInsertID();

            // Insert detail layanan ke tabel transaction_detail
            foreach ($this->cart->contents() as $value) {
                $dataFormDetail = [
                    'transaction_id' => $last_insert_id,
                    'product_id' => $value['id'],
                    'jumlah' => $value['qty'],
                    'diskon' => 0,
                    'subtotal_harga' => $value['qty'] * $value['price'],
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ];
                $this->transaction_detail->insert($dataFormDetail);
            }

            // Kosongkan keranjang
            $this->cart->destroy();
            
            session()->setFlashdata('success', 'Pesanan Anda berhasil dibuat!');
            return redirect()->to(base_url('/'));
        }
    }
}
