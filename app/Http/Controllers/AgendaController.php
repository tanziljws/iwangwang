<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function index()
    {
        $agendas = Agenda::orderBy('date')->latest()->get();
        $petugas = auth()->guard('petugas')->user();
        return view('admin.agenda.index', compact('agendas', 'petugas'));
    }

    public function create()
    {
        $petugas = auth()->guard('petugas')->user();
        return view('admin.agenda.create', compact('petugas'));
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

        Agenda::create($validated);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Agenda berhasil ditambahkan!');
    }

    public function edit(Agenda $agenda)
    {
        $petugas = auth()->guard('petugas')->user();
        return view('admin.agenda.edit', compact('agenda', 'petugas'));
    }

    public function update(Request $request, Agenda $agenda)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'time' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
        ]);

        $agenda->update($validated);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Agenda berhasil diperbarui!');
    }

    public function destroy(Agenda $agenda)
    {
        $agenda->delete();
        return redirect()->route('admin.dashboard')
            ->with('success', 'Agenda berhasil dihapus!');
    }
}

