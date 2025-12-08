<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Roster extends Model
{
    protected $fillable = [
        'date',
        'supervisor_id',
        'doctor_id',
        'caregiver_1',
        'caregiver_2',
        'caregiver_3',
        'caregiver_4',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'supervisor_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'doctor_id');
    }

    public function caregiver1(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'caregiver_1');
    }

    public function caregiver2(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'caregiver_2');
    }

    public function caregiver3(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'caregiver_3');
    }

    public function caregiver4(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'caregiver_4');
    }
}
