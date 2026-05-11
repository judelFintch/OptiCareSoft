<?php

namespace App\Http\Controllers\Consultations;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Services\ConsultationService;
use Illuminate\Http\Request;

class ConsultationExamController extends Controller
{
    public function __construct(private ConsultationService $consultationService) {}

    public function edit(Consultation $consultation)
    {
        $this->authorize('update', $consultation);

        $consultation->load(['patient', 'visualAcuity', 'refraction', 'eyePressure']);

        return view('pages.consultations.exams.edit', compact('consultation'));
    }

    public function update(Request $request, Consultation $consultation)
    {
        $this->authorize('update', $consultation);

        $validated = $request->validate([
            'visual_acuity.right_eye_sc' => 'nullable|string|max:50',
            'visual_acuity.left_eye_sc' => 'nullable|string|max:50',
            'visual_acuity.right_eye_cc' => 'nullable|string|max:50',
            'visual_acuity.left_eye_cc' => 'nullable|string|max:50',
            'visual_acuity.near_right_sc' => 'nullable|string|max:50',
            'visual_acuity.near_left_sc' => 'nullable|string|max:50',
            'visual_acuity.near_right_cc' => 'nullable|string|max:50',
            'visual_acuity.near_left_cc' => 'nullable|string|max:50',
            'visual_acuity.remarks' => 'nullable|string|max:1000',

            'refraction.right_sphere' => 'nullable|numeric|min:-30|max:30',
            'refraction.right_cylinder' => 'nullable|numeric|min:-15|max:15',
            'refraction.right_axis' => 'nullable|integer|min:0|max:180',
            'refraction.right_addition' => 'nullable|numeric|min:0|max:5',
            'refraction.left_sphere' => 'nullable|numeric|min:-30|max:30',
            'refraction.left_cylinder' => 'nullable|numeric|min:-15|max:15',
            'refraction.left_axis' => 'nullable|integer|min:0|max:180',
            'refraction.left_addition' => 'nullable|numeric|min:0|max:5',
            'refraction.pd_right' => 'nullable|numeric|min:20|max:45',
            'refraction.pd_left' => 'nullable|numeric|min:20|max:45',
            'refraction.lens_type' => 'nullable|string|max:100',
            'refraction.remarks' => 'nullable|string|max:1000',

            'eye_pressure.right_eye_pressure' => 'nullable|numeric|min:0|max:80',
            'eye_pressure.left_eye_pressure' => 'nullable|numeric|min:0|max:80',
            'eye_pressure.measurement_method' => 'nullable|string|max:100',
            'eye_pressure.measured_at' => 'nullable|date',
            'eye_pressure.remarks' => 'nullable|string|max:1000',
        ]);

        if ($this->hasValues($validated['visual_acuity'] ?? [])) {
            $this->consultationService->saveVisualAcuity($consultation, $validated['visual_acuity']);
        }

        if ($this->hasValues($validated['refraction'] ?? [])) {
            $this->consultationService->saveRefraction($consultation, $validated['refraction']);
        }

        if ($this->hasValues($validated['eye_pressure'] ?? [])) {
            $this->consultationService->saveEyePressure($consultation, $validated['eye_pressure']);
        }

        return redirect()
            ->route('consultations.show', $consultation)
            ->with('success', 'Examens de consultation enregistrés.');
    }

    private function hasValues(array $data): bool
    {
        return collect($data)->filter(fn ($value) => filled($value))->isNotEmpty();
    }
}
