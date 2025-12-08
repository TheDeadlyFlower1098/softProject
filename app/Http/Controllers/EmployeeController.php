<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        // If this is an AJAX / JSON request, return the data
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(Employee::paginate(20));
        }

        // Otherwise, return the Blade view for /employees
        return view('employees');
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
            'name'   => 'sometimes|string',
            'role'   => 'sometimes|string',
            'salary' => 'sometimes|numeric'
        ]);

        $emp->update($data);

        return response()->json($emp);
    }

    public function destroy($id)
    {
        Employee::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }

    public function filtered(Request $request)
    {
        try {
            $q = Employee::query();

            // ID filter
            if ($request->filled('id')) {
                $data = Employee::where('id', $request->id)->get();
                return response()->json($data);
            }

            // Name filter
            if ($request->filled('name')) {
                $data = Employee::where('name', 'LIKE', '%' . $request->name . '%')->get();
                return response()->json($data);
            }

            // Role filter
            if ($request->filled('role')) {
                $role = strtolower(trim($request->role));
                $data = Employee::whereRaw("LOWER(role) LIKE ?", ["%{$role}%"])->get();
                return response()->json($data);
            }

            // Min salary
            if ($request->filled('min_salary')) {
                $data = Employee::where('salary', '>=', $request->min_salary)->get();
                return response()->json($data);
            }

            // Max salary
            if ($request->filled('max_salary')) {
                $data = Employee::where('salary', '<=', $request->max_salary)->get();
                return response()->json($data);
            }

            // Nothing filled → return all
            $data = Employee::all();
            return response()->json($data);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


}
