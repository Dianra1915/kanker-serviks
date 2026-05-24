<?php

namespace App\Http\Controllers;

use App\Models\Jenis;
use Illuminate\Http\Request;

class JenisController extends Controller
{
    public function index()
    {
        // Logika kode otomatis J01, J02...
        $lastJenis = Jenis::orderBy('id', 'desc')->first();
        $nextNo = $lastJenis ? ((int) substr($lastJenis->kode_jenis, 1)) + 1 : 1;
        $kodeOtomatis = "J" . str_pad($nextNo, 2, "0", STR_PAD_LEFT);

        return view('admin.jenis.index', [
            'title' => 'Data Jenis Kanker',
            'jenisKanker' => Jenis::all(),
            'kodeOtomatis' => $kodeOtomatis
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_jenis' => 'required|unique:jenis,kode_jenis',
            'nama_jenis' => 'required',
            'solusi'     => 'required'
        ], [
            'kode_jenis.unique' => 'Kode jenis sudah ada!',
            'nama_jenis.required' => 'Nama jenis wajib diisi.',
            'solusi.required' => 'Solusi wajib diisi.'
        ]);

        Jenis::create($request->all());

        return redirect()->route('jenis.index')->with('success', 'Jenis kanker berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_jenis' => 'required',
            'solusi'     => 'required'
        ]);

        $jenis = Jenis::findOrFail($id);
        $jenis->update($request->all());

        return redirect()->route('jenis.index')->with('success', 'Jenis kanker berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Jenis::findOrFail($id)->delete();
        return redirect()->route('jenis.index')->with('success', 'Jenis kanker berhasil dihapus!');
    }
}