<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Patient;
use Carbon\Carbon;

class PaymentsTableSeeder extends Seeder
{
    public function run()
    {
        $patients = Patient::all();
        foreach ($patients as $p) {
            Payment::create([
                'patient_id' => $p->id,
                'amount' => rand(100, 300),
                'description' => 'Initial admission fee',
                'paid_at' => Carbon::now()->subDays(rand(0, 15))
            ]);
        }
    }
}
