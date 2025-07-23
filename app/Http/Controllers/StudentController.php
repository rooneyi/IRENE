<?php
namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::orderBy('nom')->paginate(15);
        return view('students.index', compact('students'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'matricule' => 'required|unique:students,matricule',
            'classe' => 'required',
        ]);
        Student::create($request->all());
        return redirect()->route('students.index')->with('success', 'Élève ajouté avec succès.');
    }

    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'matricule' => 'required|unique:students,matricule,'.$student->id,
            'classe' => 'required',
        ]);
        $student->update($request->all());
        return redirect()->route('students.index')->with('success', 'Élève modifié avec succès.');
    }
}

