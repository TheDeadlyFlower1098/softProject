<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class MedicineCheck extends Model
{
    use HasFactory;

    protected $table = 'medicine_checks';

    protected $fillable = [
        'patient_id',
        'caregiver_id',
        'date',
        'morning',
        'afternoon',
        'night',
        'breakfast',
        'lunch',
        'dinner',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // assuming caregivers are stored in employees table
    public function caregiver()
    {
        return $this->belongsTo(Employee::class, 'caregiver_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors / Helpers
    |--------------------------------------------------------------------------
    */

    public function getMedicationSlotsAttribute(): array
    {
        return [
            'morning'   => $this->morning,
            'afternoon' => $this->afternoon,
            'night'     => $this->night,
        ];
    }

    public function getMealSlotsAttribute(): array
    {
        return [
            'breakfast' => $this->breakfast,
            'lunch'     => $this->lunch,
            'dinner'    => $this->dinner,
        ];
    }

    public function getAllSlotsAttribute(): array
    {
        return array_merge(
            $this->medication_slots,
            $this->meal_slots
        );
    }

    /**
     * Overall status for this record:
     * - 'none'     => all known slots are missed OR everything is null
     * - 'partial'  => mix of taken/missed
     * - 'complete' => all known slots are taken
     */
    public function status(): string
    {
        // Only consider slots that are not null
        $values = array_values(
            array_filter($this->all_slots, fn ($v) => ! is_null($v))
        );

        // no data at all
        if (empty($values)) {
            return 'none';
        }

        $allTaken  = collect($values)->every(fn ($v) => $v === 'taken');
        $allMissed = collect($values)->every(fn ($v) => $v === 'missed');

        if ($allTaken) {
            return 'complete';
        }

        if ($allMissed) {
            return 'none';
        }

        return 'partial';
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('date', Carbon::parse($date));
    }

    public function scopeForPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }
}
