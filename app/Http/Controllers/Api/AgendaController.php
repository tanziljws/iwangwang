<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function index()
    {
        $agendas = Agenda::orderBy('date')->latest()->get();
        return response()->json($agendas, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'time' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
        ]);

        $agenda = Agenda::create($validated);
        return response()->json($agenda, 201);
    }

    public function show(Agenda $agenda)
    {
        return response()->json($agenda, 200);
    }

    public function update(Request $request, Agenda $agenda)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'sometimes|required|date',
            'time' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
        ]);

        $agenda->update($validated);
        return response()->json($agenda);
    }

    public function destroy(Agenda $agenda)
    {
        $agenda->delete();

        return response()->json(['message' => 'Agenda deleted']);
    }
}
