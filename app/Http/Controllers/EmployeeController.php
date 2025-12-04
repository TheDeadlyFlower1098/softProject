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
        \Log::info("FILTER REQUEST", $request->all());

        try {
            $q = Employee::query();

            // Log which filters are filled
            \Log::info("Filters Filled", [
                'id'          => $request->filled('id'),
                'name'        => $request->filled('name'),
                'role'        => $request->filled('role'),
                'min_salary'  => $request->filled('min_salary'),
                'max_salary'  => $request->filled('max_salary'),
            ]);

            // ID filter
            if ($request->filled('id')) {
                $data = Employee::where('id', $request->id)->get();
                \Log::info("Returning by ID", $data->toArray());
                return response()->json($data);
            }

            // Name filter
            if ($request->filled('name')) {
                $data = Employee::where('name', 'LIKE', '%' . $request->name . '%')->get();
                \Log::info("Returning by NAME", $data->toArray());
                return response()->json($data);
            }

            // Role filter
            if ($request->filled('role')) {
                $role = strtolower(trim($request->role));
                $data = Employee::whereRaw("LOWER(role) LIKE ?", ["%{$role}%"])->get();
                \Log::info("Returning by ROLE", $data->toArray());
                return response()->json($data);
            }

            // Min salary
            if ($request->filled('min_salary')) {
                $data = Employee::where('salary', '>=', $request->min_salary)->get();
                \Log::info("Returning by MIN SALARY", $data->toArray());
                return response()->json($data);
            }

            // Max salary
            if ($request->filled('max_salary')) {
                $data = Employee::where('salary', '<=', $request->max_salary)->get();
                \Log::info("Returning by MAX SALARY", $data->toArray());
                return response()->json($data);
            }

            // Nothing filled → return all
            $data = Employee::all();
            \Log::info("Returning ALL", $data->toArray());
            return response()->json($data);

        } catch (\Exception $e) {
            \Log::error("FILTER ERROR", ["message" => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


}
