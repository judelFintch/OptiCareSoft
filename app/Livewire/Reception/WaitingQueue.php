<?php

namespace App\Livewire\Reception;

use App\Enums\VisitStatus;
use App\Models\Visit;
use Livewire\Component;

class WaitingQueue extends Component
{
    public function updateStatus(int $visitId, string $status): void
    {
        $this->authorize('visits.manage');

        $visit = Visit::findOrFail($visitId);
        $visit->update(['status' => VisitStatus::from($status)]);

        $this->dispatch('visit-updated');
    }

    public function closeVisit(int $visitId): void
    {
        $this->authorize('visits.manage');

        $visit = Visit::findOrFail($visitId);
        $visit->update([
            'status'    => VisitStatus::Closed,
            'closed_at' => now(),
            'closed_by' => auth()->id(),
        ]);
    }

    public function render()
    {
        $visits = Visit::with(['patient', 'consultations'])
            ->today()
            ->whereNotIn('status', [VisitStatus::Closed->value, VisitStatus::Cancelled->value])
            ->orderBy('opened_at')
            ->get();

        return view('livewire.reception.waiting-queue', compact('visits'));
    }
}
