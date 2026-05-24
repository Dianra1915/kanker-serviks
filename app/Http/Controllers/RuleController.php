<?php

namespace App\Http\Controllers;

use App\Models\Rule;
use App\Models\Gejala;
use App\Models\Jenis;
use Illuminate\Http\Request;

class RuleController extends Controller
{
    public function index()
    {
        return view('admin.rules.index', [
            'title' => 'Basis Aturan (Rules)',
            'rules' => Rule::with(['gejala', 'jenis'])->get(),
            'gejalas' => Gejala::all(),
            'jenisKankers' => Jenis::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_id' => 'required',
            'mb' => 'required|array',
        ]);

        $jenis_id = $request->jenis_id;
        $input_count = 0;
        $skip_count = 0;

        foreach ($request->mb as $gejala_id => $mb_value) {
            if ($mb_value > 0) {
                // Cek apakah kombinasi Jenis + Gejala ini sudah pernah ada
                $exists = Rule::where('jenis_id', $jenis_id)
                            ->where('gejala_id', $gejala_id)
                            ->exists();

                if (!$exists) {
                    $md = 1 - $mb_value;
                    Rule::create([
                        'jenis_id' => $jenis_id,
                        'gejala_id' => $gejala_id,
                        'mb' => $mb_value,
                        'md' => $md,
                    ]);
                    $input_count++;
                } else {
                    $skip_count++;
                }
            }
        }

        if ($input_count > 0) {
            $pesan = "$input_count aturan baru berhasil disimpan.";
            if ($skip_count > 0) {
                $pesan .= " ($skip_count data duplikat diabaikan, silakan gunakan fitur edit untuk mengubahnya).";
            }
            return redirect()->route('rules.index')->with('success', $pesan);
        } else {
            return redirect()->back()->with('error', "Tidak ada data baru yang disimpan. $skip_count data sudah ada sebelumnya.");
        }
    }

    public function update(Request $request, $id)
    {
        $rule = Rule::findOrFail($id);
        
        // Validasi input MB
        $request->validate([
            'mb' => 'required|numeric',
        ]);

        $mb = $request->mb;
        $md = 1 - $mb; // Hitung otomatis MD

        $rule->update([
            'mb' => $mb,
            'md' => $md,
        ]);

        return redirect()->route('rules.index')->with('success', 'Detail aturan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Rule::findOrFail($id)->delete();
        return redirect()->route('rules.index')->with('success', 'Rule berhasil dihapus!');
    }
}