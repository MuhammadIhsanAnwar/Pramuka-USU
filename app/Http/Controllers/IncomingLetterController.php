<?php

namespace App\Http\Controllers;

use App\Models\IncomingLetter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IncomingLetterController extends Controller
{
    public function index(): View
    {
        $letters = IncomingLetter::latest()->get();

        return view('filament.admin.pages.surat-masuk', compact('letters'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'letter_date' => ['required', 'date'],
            'sender' => ['required', 'string', 'max:255'],
            'letter_number' => ['required', 'string', 'max:255'],
            'classification' => ['required', 'string', 'max:255'],
            'attachment' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string'],
            'file_path' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:4096'],
        ]);

        if ($request->hasFile('file_path')) {
            $data['file_path'] = $request->file('file_path')->store('incoming-letters', 'public');
        }

        IncomingLetter::create($data);

        return redirect()->back()->with('success', 'Data surat masuk berhasil disimpan.');
    }
}
