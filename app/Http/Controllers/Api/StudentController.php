<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{

    public function index()
    {
        $students = Student::all();

		$data = [
			'students' => $students,
			'status' => 200
		];

		return response()->json($data, 200);
    }



    public function store(Request $request)
    {

		// validar datos
		$validator = Validator::make($request->all(), [
			'name' => 'required|max:255',
			'email' => 'required|email|unique:students',
			'phone' => 'required|digits:10|numeric',
			'language' => 'required|in:English,Spanish,Japanese',
		]);


		// errores en la validación
		if ($validator->fails()) {
			$data = [
				'status' => 400,
				'error' => $validator->errors(),
				'message' => 'Hay errores en el formato de los datos'
			];
			return response()->json($data, 400);
		}


		// crear nuevo estudiante
		$student = new Student();
		$student->name = $request->name;
		$student->email = $request->email;
		$student->phone = $request->phone;
		$student->language = $request->language;
		$student->save();


		// error
		if (!$student) {
			$data = [
				'status' => 500,
				'message' => 'No se pudo registrar el estudiante'
			];
			return response()->json($data, 500);
		}


		// guardado
		$data = [
			'status' => 201,
			'message' => 'Estudiante registrado'
		];
		return response()->json($data, 201);

    }



    public function show($id)
    {

		$student = Student::find($id);

		if (!$student) {

			$data = [
				'status' => 404,
				'message' => 'Estudiante no encontrado'
			];
			return response()->json($data, 404);
		} else {

			$data = [
				'student' => $student,
				'status' => 200
			];
			return response()->json($data, 200);
		}

    }


    public function update(Request $request, string $id)
    {

		// buscar estudiante
		$student = Student::find($id);

		// estudiante no encontrado
		if (!$student) {
			$data = [
				'status' => 404,
				'message' => 'Estudiante no encontrado'
			];
			return response()->json($data, 404);
		}


		// validar datos
		$validator = Validator::make($request->all(), [
			'name' => 'required|max:255',
			'email' => 'required|email|unique:students,email,' . $id,
			'phone' => 'required|digits:10|numeric',
			'language' => 'required|in:English,Spanish,Japanese',
		]);


		// fallos en validación
		if ($validator->fails()) {
			$data = [
				'status' => 400,
				'error' => $validator->errors(),
				'message' => 'Hay errores en el formato de los datos'
			];
			return response()->json($data, 400);
		}


		// actualizar estudiante
		$student->name = $request->name;
		$student->email = $request->email;
		$student->phone = $request->phone;
		$student->language = $request->language;
		$student->save();

		$data = [
			'status' => 200,
			'message' => 'Estudiante actualizado',
			'student' => $student
		];

		return response()->json($data, 200);

    }


	public function updatePartial(Request $request, string $id) {
		$student = Student::find($id);

		if (!$student) {
			$data = [
				'status' => 404,
				'message' => 'Estudiante no encontrado'
			];
			return response()->json($data, 404);
		}


		// validar datos
		$validator = Validator::make($request->all(), [
			'name' => 'max:255',
			'email' => 'email|unique:students,email,' . $id,
			'phone' => 'digits:10|numeric',
			'language' => 'in:English,Spanish,Japanese',
		]);


		// la validación falló
		if ($validator->fails()) {
			$data = [
				'status' => 400,
				'error' => $validator->errors(),
				'message' => 'Hay errores en el formato de los datos'
			];
			return response()->json($data, 400);
		}


		// actualizar estudiante
		$student->name = $request->name ?? $student->name;
		$student->email = $request->email ?? $student->email;
		$student->phone = $request->phone ?? $student->phone;
		$student->language = $request->language ?? $student->language;
		$student->save();

		$data = [
			'status' => 200,
			'message' => 'Estudiante actualizado',
			'student' => $student
		];

		return response()->json($data, 200);
	}


    public function destroy(string $id)
    {

		$student = Student::find($id);

		if (!$student) {

			$data = [
				'status' => 404,
				'message' => 'Estudiante no encontrado'
			];
			return response()->json($data, 404);

		}

		$student->delete();
		$data = [
			'status' => 200,
			'message' => 'Estudiante eliminado'
		];
		return response()->json($data, 200);

    }
}
