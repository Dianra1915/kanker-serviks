<?php

namespace App\Http\Controllers;

use App\Models\Gejala;
use Illuminate\Http\Request;

class GejalaController extends Controller
{
    // Menampilkan daftar gejala
    public function index()
    {
        $gejalas = Gejala::all();

        // LOGIKA KODE OTOMATIS (Tambahkan Bagian Ini)
        $lastGejala = Gejala::orderBy('id', 'desc')->first();
        if (!$lastGejala) {
            $kodeOtomatis = "G01";
        } else {
            $noUrut = (int) substr($lastGejala->kode_gejala, 1);
            $kodeOtomatis = "G" . str_pad($noUrut + 1, 2, "0", STR_PAD_LEFT);
        }

        // Kirim $kodeOtomatis ke View
        return view('admin.gejala.index', compact('gejalas', 'kodeOtomatis'));
    }

    // Menyimpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_gejala' => 'required',
        ]);

        // Logika penentuan kode (tetap dipertahankan agar aman saat simpan)
        $lastGejala = Gejala::orderBy('id', 'desc')->first();
        if (!$lastGejala) {
            $kodeBaru = "G01";
        } else {
            $noUrut = (int) substr($lastGejala->kode_gejala, 1);
            $kodeBaru = "G" . str_pad($noUrut + 1, 2, "0", STR_PAD_LEFT);
        }

        Gejala::create([
            'kode_gejala' => $kodeBaru,
            'nama_gejala' => $request->nama_gejala,
        ]);

        return redirect()->back()->with('success', "Gejala berhasil ditambah dengan kode $kodeBaru");
    }

    public function update(Request $request, $id)
    {
        $gejala = Gejala::findOrFail($id);
        $gejala->update($request->all());

        return redirect()->route('gejala.index')->with('success', 'Data berhasil diubah!');
    }

    public function destroy($id)
    {
        Gejala::findOrFail($id)->delete();
        return redirect()->route('gejala.index')->with('success', 'Data berhasil dihapus!');
    }
}