<?php

namespace App\Http\Controllers\Patients;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PatientDocumentController extends Controller
{
    public function store(Request $request, Patient $patient)
    {
        $this->authorize('patients.edit');

        $validated = $request->validate([
            'file'     => 'required|file|max:10240',
            'name'     => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'notes'    => 'nullable|string|max:1000',
        ]);

        $file = $request->file('file');
        $path = $file->store('patient-documents/' . $patient->id, 'private');

        PatientDocument::create([
            'patient_id'  => $patient->id,
            'name'        => $validated['name'] ?: $file->getClientOriginalName(),
            'file_path'   => $path,
            'file_type'   => $file->getClientMimeType(),
            'file_size'   => $file->getSize(),
            'category'    => $validated['category'] ?? null,
            'notes'       => $validated['notes'] ?? null,
            'uploaded_by' => $request->user()->id,
        ]);

        return back()->with('success', 'Document ajouté.');
    }

    public function download(Patient $patient, PatientDocument $document)
    {
        $this->authorize('view', $patient);

        abort_if($document->patient_id !== $patient->id, 403);

        return Storage::disk('private')->download($document->file_path, $document->name);
    }

    public function destroy(Patient $patient, PatientDocument $document)
    {
        $this->authorize('patients.edit');

        abort_if($document->patient_id !== $patient->id, 403);

        Storage::disk('private')->delete($document->file_path);
        $document->delete();

        return back()->with('success', 'Document supprimé.');
    }
}
