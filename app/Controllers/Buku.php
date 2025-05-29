<?php

namespace App\Controllers;

use App\Models\M_Buku;
use App\Models\M_Kategori;
use App\Models\M_Rak;

class Buku extends BaseController
{
    public function input_data_buku()
    {
        $modelKategori = new M_Kategori();
        $modelRak = new M_Rak();

        $data['data_kategori'] = $modelKategori->findAll();
        $data['data_rak'] = $modelRak->findAll();

        echo view('Backend/Template/header');
        echo view('Backend/Template/sidebar');
        echo view('Backend/Buku/input_data_buku', $data);
        echo view('Backend/Template/footer');
    }

    public function simpan_data_buku()
    {
        $modelBuku = new M_Buku();

        $judul_buku = $this->request->getPost('judul_buku');
        $pengarang = $this->request->getPost('pengarang');
        $penerbit = $this->request->getPost('penerbit');
        $tahun = $this->request->getPost('tahun');
        $jumlah_eksemplar = $this->request->getPost('jumlah_eksemplar');
        $id_kategori = $this->request->getPost('id_kategori');
        $id_rak = $this->request->getPost('id_rak');
        $keterangan = $this->request->getPost('keterangan');

        $cover_buku = $this->request->getFile('cover_buku');
        $e_book = $this->request->getFile('e_book');

        if (empty($judul_buku) || empty($pengarang)) {
            session()->setFlashdata('error', 'Judul dan Pengarang harus diisi.');
            return redirect()->to(base_url('buku/input-data-buku'))->withInput();
        }

        $nama_cover = '';
        if ($cover_buku->isValid() && !$cover_buku->hasMoved()) {
            $nama_cover = $cover_buku->getRandomName();
            $cover_buku->move(ROOTPATH . 'public/uploads/covers', $nama_cover);
        }

        $nama_ebook = '';
        if ($e_book->isValid() && !$e_book->hasMoved()) {
            $nama_ebook = $e_book->getRandomName();
            $e_book->move(ROOTPATH . 'public/uploads/ebooks', $nama_ebook);
        }

        $data = [
            'judul_buku' => $judul_buku,
            'pengarang' => $pengarang,
            'penerbit' => $penerbit,
            'tahun_terbit' => $tahun,
            'jumlah_eksemplar' => $jumlah_eksemplar,
            'id_kategori' => $id_kategori,
            'id_rak' => $id_rak,
            'keterangan' => $keterangan,
            'cover_buku' => $nama_cover,
            'e_book' => $nama_ebook,
        ];

        $modelBuku->save($data);

        session()->setFlashdata('success', 'Data buku berhasil disimpan.');
        return redirect()->to(base_url('buku/master-data-buku'));
    }

    // ... Method lainnya untuk edit, update, delete, dll.
}