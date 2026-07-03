<?php

namespace Database\Seeders;

use App\Models\TicketCategory;
use Illuminate\Database\Seeder;

class TicketCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'ERP - Bug/error',
                'description' => 'Error, bug, atau perilaku tidak sesuai pada sistem ERP Daidan (modul Finance, HRIS, Shipping, dll)',
            ],
            [
                'name' => 'ERP - Akses & Training',
                'description' => 'Kendala login/akses ke ERP, permintaan pelatihan modul, reset akses ERP',
            ],
            [
                'name' => 'ERP - Data & Sinkronisasi',
                'description' => 'Data tidak sync antar modul/entitas, ketidaksesuaian master data',
            ],
            [
                'name' => 'Hardware',
                'description' => 'Laptop/PC rusak, printer, scanner, monitor, perangkat fisik lain',
            ],
            [
                'name' => 'Jaringan & Konektivitas',
                'description' => 'WiFi, VPN, internet lambat, koneksi ke server/aplikasi',
            ],
            [
                'name' => 'Software & Aplikasi Non-ERP',
                'description' => 'Microsoft Office, browser, aplikasi pihak ketiga lain',
            ],
            [
                'name' => 'Akses & Akun (Non-ERP)',
                'description' => 'Reset password email/domain, akun terkunci, permintaan akses sistem non-ERP',
            ],
            [
                'name' => 'Email & Komunikasi',
                'description' => 'Outlook/email issue, kuota mailbox, distribution list',
            ],
            [
                'name' => 'Permintaan Baru (Provisioning)',
                'description' => 'Laptop/software/akses baru untuk karyawan baru',
            ],
            [
                'name' => 'Maintenance Terjadwal',
                'description' => 'Pemberitahuan/permintaan maintenance rutin (bukan laporan kerusakan)',
            ],
            [
                'name' => 'Lainnya',
                'description' => 'Di luar kategori di atas (dipantau berkala; indikasi taksonomi perlu revisi jika proporsinya tinggi)',
            ],
        ];

        foreach ($categories as $category) {
            TicketCategory::create($category);
        }
    }
}
