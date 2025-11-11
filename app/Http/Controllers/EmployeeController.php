<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        return response()->json(Employee::paginate(20));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'emp_identifier' => 'required|unique:employees,emp_identifier',
            'name' => 'required|string',
            'role' => 'required|string',
            'salary' => 'nullable|numeric'
        ]);

        $emp = Employee::create($data);
        return response()->json($emp, 201);
    }

    public function show($id)
    {
        return response()->json(Employee::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $emp = Employee::findOrFail($id);

        $data = $request->validate([
            'name' => 'sometimes|required|string',
            'role' => 'sometimes|required|string',
            'salary' => 'nullable|numeric'
        ]);

        $emp->update($data);

        return response()->json($emp);
    }

    public function destroy($id)
    {
        Employee::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
