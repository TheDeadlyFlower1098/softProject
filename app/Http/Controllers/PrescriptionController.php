<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function index()
    {
        return response()->json(Prescription::with(['patient','doctor'])->paginate(20));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id'    => 'required|exists:patients,id',
            'doctor_id'     => 'required|exists:employees,id',
            'appointment_id'=> 'nullable|exists:appointments,id',
            'content'       => 'nullable|string',
            'notes'         => 'nullable|string',

            'items'                     => 'required|array',
            'items.*.name'              => 'required|string',
            'items.*.dosage'            => 'nullable|string',
            'items.*.frequency'         => 'nullable|string',
            'items.*.instructions'      => 'nullable|string',
        ]);

        // Create the base prescription
        $prescription = Prescription::create([
            'patient_id'     => $data['patient_id'],
            'doctor_id'      => $data['doctor_id'],
            'appointment_id' => $data['appointment_id'] ?? null,
            'content'        => $data['content'] ?? null,
            'notes'          => $data['notes'] ?? null,
        ]);

        // Create each prescription item
        foreach ($data['items'] as $itemData) {
            $prescription->items()->create($itemData);
        }

        return redirect()
            ->back()
            ->with('success', 'Prescription saved.');
    }


    public function show($id)
    {
        return response()->json(Prescription::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $p = Prescription::findOrFail($id);
        $data = $request->validate([
            'content' => 'sometimes|required|string',
            'notes' => 'nullable|string',
        ]);
        $p->update($data);
        return response()->json($p);
    }

    public function destroy($id)
    {
        Prescription::findOrFail($id)->delete();
        return response()->json(['message'=>'Deleted']);
    }
}
