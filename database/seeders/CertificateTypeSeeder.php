<?php

namespace Database\Seeders;

use App\Models\CertificateType;
use Illuminate\Database\Seeder;

class CertificateTypeSeeder extends Seeder
{
    public function run()
    {
        $certificates = [
            [
                'certificate_name' => 'Barangay Clearance',
                'description' => 'Certifies that an individual is a resident of the barangay',
                'fee' => 50.00
            ],
            [
                'certificate_name' => 'Certificate of Indigency',
                'description' => 'Certifies that an individual belongs to an indigent family',
                'fee' => 30.00
            ],
            [
                'certificate_name' => 'Business Clearance',
                'description' => 'Permit to operate a business within the barangay',
                'fee' => 200.00
            ],
            [
                'certificate_name' => 'Certificate of Residency',
                'description' => 'Proof of residency in the barangay',
                'fee' => 50.00
            ],
        ];

        foreach ($certificates as $certificate) {
            CertificateType::create($certificate);
        }
    }
}