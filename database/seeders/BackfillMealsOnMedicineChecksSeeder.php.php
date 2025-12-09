<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MedicineCheck;

class BackfillMealsOnMedicineChecksSeeder extends Seeder
{
    /**
     * This ONLY updates existing medicine_checks rows.
     */
    public function run(): void
    {
        MedicineCheck::chunkById(100, function ($checks) {
            foreach ($checks as $check) {
                $updated = false;

                // Normalize existing med columns into 'taken'/'missed'/null
                $normalize = function ($val) {
                    if (is_null($val)) {
                        return null;
                    }

                    if ($val === 'taken' || $val === 'missed') {
                        return $val;
                    }

                    if ($val === true || $val === 1 || $val === '1') {
                        return 'taken';
                    }

                    if ($val === false || $val === 0 || $val === '0') {
                        return 'missed';
                    }

                    return null;
                };

                $morning   = $normalize($check->morning);
                $afternoon = $normalize($check->afternoon);
                $night     = $normalize($check->night);

                // ----- Backfill meals based on meds -----
                // If meal is null, we copy the closest related med slot.
                // If both are null, default to 'missed' so it's counted.

                if (is_null($check->breakfast)) {
                    $check->breakfast = $morning ?? 'missed';
                    $updated = true;
                }

                if (is_null($check->lunch)) {
                    $check->lunch = $afternoon ?? 'missed';
                    $updated = true;
                }

                if (is_null($check->dinner)) {
                    $check->dinner = $night ?? 'missed';
                    $updated = true;
                }

                if ($updated) {
                    $check->morning   = $morning;
                    $check->afternoon = $afternoon;
                    $check->night     = $night;
                    $check->save();
                }
            }
        });
    }
}
