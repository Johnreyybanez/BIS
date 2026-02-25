<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            ['setting_key' => 'barangay_name', 'setting_value' => 'Barangay Sample'],
            ['setting_key' => 'barangay_captain', 'setting_value' => 'Hon. Juan Dela Cruz'],
            ['setting_key' => 'barangay_address', 'setting_value' => 'Sample Street, Barangay Sample, City'],
            ['setting_key' => 'barangay_contact', 'setting_value' => '123-4567'],
            ['setting_key' => 'barangay_email', 'setting_value' => 'barangay@sample.com'],
        ];

        foreach ($settings as $setting) {
            SystemSetting::create($setting);
        }
    }
}