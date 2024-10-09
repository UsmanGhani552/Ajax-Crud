<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    use ImageUploadTrait;
    public function index()
    {
        return view('student.index');
    }

    public function fetchstudent()
    {
        $students = Student::all();
        return response()->json([
            'students' => $students,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'course' => 'required|max:191',
            'email' => 'required|email|max:191',
            'phone' => 'required',
            'image' => 'required|image',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ]);
        } else {
            $student = new Student;
            $student->name = $request->input('name');
            $student->course = $request->input('course');
            $student->email = $request->input('email');
            $student->phone = $request->input('phone');
            $filename = $this->uploadImage($request, 'images', 'image');
            $student->image = $filename;

            $student->save();
            return response()->json([
                'status' => 200,
                'message' => 'Student Added Successfully.'
            ]);
        }
    }

    public function edit($id)
    {
        $student = Student::find($id);
        if ($student) {
            return response()->json([
                'status' => 200,
                'student' => $student,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Student Found.'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'course' => 'required|max:191',
            'email' => 'required|email|max:191',
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ]);
        } else {
            $student = Student::find($id);
            if ($student) {
                $destination = "images/{$student->image}";
                if (File::exists(public_path($destination))) {
                    File::delete(public_path($destination));
                }
                $student->name = $request->input('name');
                $student->course = $request->input('course');
                $student->email = $request->input('email');
                $student->phone = $request->input('phone');
                $student->image = $this->uploadImage($request, 'images', 'image',$student->image);
                // if ($image != null)
                //     $student->image = $image;
                $student->update();
                return response()->json([
                    'status' => 200,
                    'message' => 'Student Updated Successfully.'
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'No Student Found.'
                ]);
            }
        }
    }

    public function destroy($id)
    {
        $student = Student::find($id);
        if ($student) {
            $destination = "images/{$student->image}";
            if (File::exists(public_path($destination))) {
                File::delete(public_path($destination));
            }
            $student->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Student Deleted Successfully.'
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Student Found.'
            ]);
        }
    }
}
