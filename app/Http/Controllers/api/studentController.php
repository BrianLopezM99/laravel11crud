<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class studentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::all();

        // if ($students->count() == 0) {
        //     return response()->json([
        //        'message' => 'No se encontraron estudiantes',
        //         'status' => 200
        //     ]);
        // }

        $data = [
            'students' => $students,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
       $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:student',
            'phone' => 'required|digits:10',
            'language' => 'required|in:English,Spanish,French',
        ]);

        if ($validator->fails()){
            $data = [
                'message' => 'Error en la validacion de datos',
                'errors'=> $validator->errors(),
                'status'=> 400
            ];
            return response()->json($data, 400);
        }



        $student = Student::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'phone'=> $request->phone,
            'language'=> $request->language
        ]);

       


        if(!$student){
            $data = [
                'message'=> 'Error al crear el estudiante',
                'status'=> 500
            ];
            return response()->json($data, 500);
        }



        $data = [
           'message'=> 'Estudiante creado correctamente',
           'student'=> $student,
           'status'=> 200
        ];

        return response()->json($data, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //

        $student = Student::find($id);

        if(!$student){
            $data = [
               'message'=> 'No se encontro el estudiante',
               'status'=> 404
            ];
            return response()->json($data, 404);
        }

        $data = [
           'student'=> $student,
           'status'=> 200
        ];

        return response()->json($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $student = Student::find($id);

        if(!$student){
            $data = [
               'message'=> 'No se encontro el estudiante',
               'status'=> 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:student,email,'.$student->id,
            'phone' => 'required|digits:10',
            'language' => 'required|in:English,Spanish,French',
        ]);

        if ($validator->fails()){
            $data = [
               'message' => 'Error en la validacion de datos',
                'errors'=> $validator->errors(),
               'status'=> 400
            ];
            return response()->json($data, 400);
        }

        $student->name = $request->name;
        $student->email = $request->email;
        $student->phone = $request->phone;
        $student->language = $request->language;

        $student->save();

        $data = [
           'message'=> 'Estudiante actualizado correctamente',
           'student'=> $student,
           'status'=> 200
        ];

        return response()->json($data, 200);

    }

    public function updatePartial(Request $request, $id){
        $student = Student::find($id);

        if (!$student){
            $data = [
               'message'=> 'No se encontro el estudiante',
               'status'=> 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' =>'max:255',
            'email' =>'email|unique:student',
            'phone' =>'digits:10',
            'language' =>'in:English,Spanish,French'
        ]);

        if ($validator->fails()){
            $data = [
               'message' => 'Error en la validacion de datos',
                'errors'=> $validator->errors(),
               'status'=> 400
            ];
            return response()->json($data, 400);
        }

        if($request->has('name')){
            $student->name = $request->name;
        }

        if($request->has('email')){
            $student->email = $request->email;
        }

        if($request->has('phone')){
            $student->phone = $request->phone;
        }

        if($request->has('language')){
            $student->language = $request->language;
        }

        $student->save();

        $data = [
           'message'=> 'Estudiante actualizado correctamente',
           'student'=> $student,
           'status'=> 200
        ];

        return response()->json($data, 200);
    }

    public function destroy(string $id)
    {
        //
        $student = Student::find($id);

        if(!$student){
            $data = [
               'message'=> 'No se encontro el estudiante',
               'status'=> 404
            ];
            return response()->json($data, 404);
        }

        $student->delete();

        $data = [
           'message'=> 'Estudiante eliminado correctamente',
           'status'=> 200
        ];

        return response()->json($data, 200);
    }
}
