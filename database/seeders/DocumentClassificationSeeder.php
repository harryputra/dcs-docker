<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DocumentClassification;
use Illuminate\Support\Facades\DB;

class DocumentClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DocumentClassification::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // HM - Hubungan Masyarakat
        $hm = DocumentClassification::create([
            'code' => 'HM',
            'name' => 'Hubungan Masyarakat',
            'level' => 1,
            'order' => 1,
            'is_active' => true
        ]);

        $hmSubs = [
            ['code' => '01', 'name' => 'Protokol'],
            ['code' => '02', 'name' => 'Hubungan Antar Lembaga'],
            ['code' => '03', 'name' => 'Hubungan Dengan Organisasi Non Pemerintah', 'children' => [
                ['code' => '01', 'name' => 'Organisasi Profesi'],
                ['code' => '02', 'name' => 'Organisasi Internasional'],
            ]],
            ['code' => '04', 'name' => 'Hubungan Masyarakat'],
            ['code' => '05', 'name' => 'Kunjungan Tamu'],
            ['code' => '06', 'name' => 'Ucapan/Penghargaan'],
            ['code' => '07', 'name' => 'Penerangan dan Publikasi'],
            ['code' => '08', 'name' => 'Dokumentasi dan Perpustakaan'],
        ];

        foreach ($hmSubs as $index => $sub) {
            $hmSub = DocumentClassification::create([
                'code' => $sub['code'],
                'name' => $sub['name'],
                'parent_id' => $hm->id,
                'level' => 2,
                'order' => $index + 1,
                'is_active' => true
            ]);

            if (isset($sub['children'])) {
                foreach ($sub['children'] as $childIndex => $child) {
                    DocumentClassification::create([
                        'code' => $child['code'],
                        'name' => $child['name'],
                        'parent_id' => $hmSub->id,
                        'level' => 3,
                        'order' => $childIndex + 1,
                        'is_active' => true
                    ]);
                }
            }
        }

        // HK - Hukum
        $hk = DocumentClassification::create([
            'code' => 'HK',
            'name' => 'Hukum',
            'level' => 1,
            'order' => 2,
            'is_active' => true
        ]);

        $hkSubs = [
            ['code' => '01', 'name' => 'Perundang-undangan'],
            ['code' => '02', 'name' => 'Peraturan/Keputusan Pimpinan'],
            ['code' => '03', 'name' => 'Pertimbangan Hukum'],
            ['code' => '04', 'name' => 'Bantuan Hukum'],
            ['code' => '05', 'name' => 'Dokumentasi Hukum'],
        ];

        foreach ($hkSubs as $index => $sub) {
            DocumentClassification::create([
                'code' => $sub['code'],
                'name' => $sub['name'],
                'parent_id' => $hk->id,
                'level' => 2,
                'order' => $index + 1,
                'is_active' => true
            ]);
        }

        // KP - Kepegawaian
        $kp = DocumentClassification::create([
            'code' => 'KP',
            'name' => 'Kepegawaian',
            'level' => 1,
            'order' => 3,
            'is_active' => true
        ]);

        $kpSubs = [
            ['code' => '01', 'name' => 'Pengadaan'],
            ['code' => '02', 'name' => 'Mutasi'],
            ['code' => '03', 'name' => 'Kedudukan'],
            ['code' => '04', 'name' => 'Kesejahteraan Pegawai'],
            ['code' => '05', 'name' => 'Cuti'],
            ['code' => '06', 'name' => 'Penilaian'],
            ['code' => '07', 'name' => 'Tata Usaha Kepegawaian'],
            ['code' => '08', 'name' => 'Pemberhentian'],
        ];

        foreach ($kpSubs as $index => $sub) {
            DocumentClassification::create([
                'code' => $sub['code'],
                'name' => $sub['name'],
                'parent_id' => $kp->id,
                'level' => 2,
                'order' => $index + 1,
                'is_active' => true
            ]);
        }

        // RT - Kerumahtanggaan
        $rt = DocumentClassification::create([
            'code' => 'RT',
            'name' => 'Kerumahtanggaan',
            'level' => 1,
            'order' => 4,
            'is_active' => true
        ]);

        $rtSubs = [
            ['code' => '01', 'name' => 'Rumah Tangga'],
            ['code' => '02', 'name' => 'Perlengkapan'],
            ['code' => '03', 'name' => 'Transportasi'],
            ['code' => '04', 'name' => 'Telekomunikasi'],
            ['code' => '05', 'name' => 'Keamanan'],
        ];

        foreach ($rtSubs as $index => $sub) {
            DocumentClassification::create([
                'code' => $sub['code'],
                'name' => $sub['name'],
                'parent_id' => $rt->id,
                'level' => 2,
                'order' => $index + 1,
                'is_active' => true
            ]);
        }

        // TU - Ketatausahaan
        $tu = DocumentClassification::create([
            'code' => 'TU',
            'name' => 'Ketatausahaan',
            'level' => 1,
            'order' => 5,
            'is_active' => true
        ]);

        $tuSubs = [
            ['code' => '01', 'name' => 'Pengurusan Surat'],
            ['code' => '02', 'name' => 'Arsip'],
            ['code' => '03', 'name' => 'Ekspedisi'],
            ['code' => '04', 'name' => 'Penggandaan'],
            ['code' => '05', 'name' => 'Rapat/Pertemuan'],
        ];

        foreach ($tuSubs as $index => $sub) {
            DocumentClassification::create([
                'code' => $sub['code'],
                'name' => $sub['name'],
                'parent_id' => $tu->id,
                'level' => 2,
                'order' => $index + 1,
                'is_active' => true
            ]);
        }

        // KU - Keuangan
        $ku = DocumentClassification::create([
            'code' => 'KU',
            'name' => 'Keuangan',
            'level' => 1,
            'order' => 6,
            'is_active' => true
        ]);

        $kuSubs = [
            ['code' => '01', 'name' => 'Anggaran'],
            ['code' => '02', 'name' => 'Pembukuan/Verifikasi'],
            ['code' => '03', 'name' => 'Perbendaharaan'],
            ['code' => '04', 'name' => 'Gaji'],
            ['code' => '05', 'name' => 'Audit'],
        ];

        foreach ($kuSubs as $index => $sub) {
            DocumentClassification::create([
                'code' => $sub['code'],
                'name' => $sub['name'],
                'parent_id' => $ku->id,
                'level' => 2,
                'order' => $index + 1,
                'is_active' => true
            ]);
        }

        // TL - Organisasi dan Tata Laksana
        $tl = DocumentClassification::create([
            'code' => 'TL',
            'name' => 'Organisasi dan Tata Laksana',
            'level' => 1,
            'order' => 7,
            'is_active' => true
        ]);

        $tlSubs = [
            ['code' => '01', 'name' => 'Organisasi'],
            ['code' => '02', 'name' => 'Tata Laksana'],
            ['code' => '03', 'name' => 'Penelitian dan Pengembangan'],
            ['code' => '04', 'name' => 'Pelaporan'],
        ];

        foreach ($tlSubs as $index => $sub) {
            DocumentClassification::create([
                'code' => $sub['code'],
                'name' => $sub['name'],
                'parent_id' => $tl->id,
                'level' => 2,
                'order' => $index + 1,
                'is_active' => true
            ]);
        }

        // KS - Kesehatan
        $ks = DocumentClassification::create([
            'code' => 'KS',
            'name' => 'Kesehatan',
            'level' => 1,
            'order' => 8,
            'is_active' => true
        ]);

        $ksSubs = [
            ['code' => '01', 'name' => 'Upaya Kesehatan'],
            ['code' => '02', 'name' => 'Promosi Kesehatan'],
            ['code' => '03', 'name' => 'Kesehatan Lingkungan'],
            ['code' => '04', 'name' => 'Pencegahan dan Pengendalian Penyakit'],
            ['code' => '05', 'name' => 'Kefarmasian dan Alat Kesehatan'],
            ['code' => '06', 'name' => 'Gizi Masyarakat'],
            ['code' => '07', 'name' => 'Kesehatan Ibu dan Anak'],
            ['code' => '08', 'name' => 'Keluarga Berencana'],
            ['code' => '09', 'name' => 'Kesehatan Kerja'],
            ['code' => '10', 'name' => 'Kesehatan Jiwa'],
        ];

        foreach ($ksSubs as $index => $sub) {
            DocumentClassification::create([
                'code' => $sub['code'],
                'name' => $sub['name'],
                'parent_id' => $ks->id,
                'level' => 2,
                'order' => $index + 1,
                'is_active' => true
            ]);
        }
    }
}
