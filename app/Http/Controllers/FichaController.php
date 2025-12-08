<?php

namespace App\Http\Controllers;

use App\Models\Ficha;
use App\Models\Aula;
use Illuminate\Http\Request;

class FichaController extends Controller
{
    public function index()
    {
        $fichas = Ficha::with('aula')->get();
        return view('fichas.index', compact('fichas'));
    }

    public function create()
    {
        $aulas = Aula::all();
        return view('fichas.create', compact('aulas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|unique:fichas',
            'programa' => 'required',
            'cantidad_estudiantes' => 'required|integer',
            'aula_id' => 'nullable|exists:aulas,id',
        ]);

        Ficha::create($request->all());

        return redirect()->route('fichas.index')
            ->with('success', 'Ficha creada correctamente');
    }

    public function edit(Ficha $ficha)
    {
        $aulas = Aula::all();
        return view('fichas.edit', compact('ficha', 'aulas'));
    }

    public function update(Request $request, Ficha $ficha)
    {
        $request->validate([
            'codigo' => "required|unique:fichas,codigo,{$ficha->id}",
            'programa' => 'required',
            'cantidad_estudiantes' => 'required|integer',
            'aula_id' => 'nullable|exists:aulas,id',
        ]);

        $ficha->update($request->all());

        return redirect()->route('fichas.index')
            ->with('success', 'Ficha actualizada correctamente');
    }

    public function destroy(Ficha $ficha)
    {
        $ficha->delete();

        return redirect()->route('fichas.index')
            ->with('success', 'Ficha eliminada correctamente');
    }
}