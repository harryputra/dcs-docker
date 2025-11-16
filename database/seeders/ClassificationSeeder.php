<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Classification;

class ClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classifications = [
            // HM - Hubungan Masyarakat
            ['kode_klasifikasi' => 'HM.01.01.13', 'nama_klasifikasi' => 'Cuti Tahunan'],
            ['kode_klasifikasi' => 'HM.01.01.14', 'nama_klasifikasi' => 'Cuti Harian'],
            ['kode_klasifikasi' => 'HM.01.01.15', 'nama_klasifikasi' => 'Cuti Sakit'],

            // KS - Kesehatan
            ['kode_klasifikasi' => 'KS.01.01.13', 'nama_klasifikasi' => 'Pelayanan Rawat Jalan'],
            ['kode_klasifikasi' => 'KS.01.01.14', 'nama_klasifikasi' => 'Pelayanan Rawat Inap'],
            ['kode_klasifikasi' => 'KS.02.01.13', 'nama_klasifikasi' => 'Imunisasi'],
            ['kode_klasifikasi' => 'KS.02.01.14', 'nama_klasifikasi' => 'KB (Keluarga Berencana)'],

            // KP - Kepegawaian
            ['kode_klasifikasi' => 'KP.01.01.13', 'nama_klasifikasi' => 'Surat Keputusan Pegawai'],
            ['kode_klasifikasi' => 'KP.01.01.14', 'nama_klasifikasi' => 'Surat Tugas'],
            ['kode_klasifikasi' => 'KP.02.01.13', 'nama_klasifikasi' => 'Kenaikan Pangkat'],

            // AD - Administrasi
            ['kode_klasifikasi' => 'AD.01.01.13', 'nama_klasifikasi' => 'Surat Masuk'],
            ['kode_klasifikasi' => 'AD.01.01.14', 'nama_klasifikasi' => 'Surat Keluar'],
            ['kode_klasifikasi' => 'AD.02.01.13', 'nama_klasifikasi' => 'Laporan Bulanan'],
            ['kode_klasifikasi' => 'AD.02.01.14', 'nama_klasifikasi' => 'Laporan Tahunan'],
        ];

        foreach ($classifications as $classification) {
            Classification::create($classification);
        }
    }
}
