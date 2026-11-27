<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Models\BikeRack;
use App\Models\Note;
use App\Services\NoteService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class NoteController extends Controller
{
    public function create(
        BikeRack $bike_rack,
    ): View {
        Gate::authorize(
            'create',
            [Note::class, $bike_rack],
        );

        return view('notes.create', [
            'bikeRack' => $bike_rack,
        ]);
    }

    public function show(
        BikeRack $bike_rack,
        Note $note,
    ): View {
        Gate::authorize('view', $note);

        return view('notes.show', [
            'bikeRack' => $bike_rack,
            'note' => $note,
        ]);
    }

    public function store(
        StoreNoteRequest $request,
        BikeRack $bike_rack,
        NoteService $service,
    ): RedirectResponse {
        $note = $service->create(
            $request->user(),
            $bike_rack,
            $request->noteData(),
        );

        return redirect()
            ->route('bike-racks.notes.show', [
                'bike_rack' => $bike_rack,
                'note' => $note,
            ])
            ->with('success', 'Примітку створено.');
    }

    public function edit(
        BikeRack $bike_rack,
        Note $note,
    ): View {
        Gate::authorize('update', $note);

        return view('notes.edit', [
            'bikeRack' => $bike_rack,
            'note' => $note,
        ]);
    }

    public function update(
        UpdateNoteRequest $request,
        BikeRack $bike_rack,
        Note $note,
        NoteService $service,
    ): RedirectResponse {
        $note = $service->update(
            $request->user(),
            $note,
            $request->noteData(),
        );

        return redirect()
            ->route('bike-racks.notes.show', [
                'bike_rack' => $bike_rack,
                'note' => $note,
            ])
            ->with('success', 'Примітку оновлено.');
    }

    public function destroy(
        Request $request,
        BikeRack $bike_rack,
        Note $note,
        NoteService $service,
    ): RedirectResponse {
        $service->delete(
            $request->user(),
            $note,
        );

        return redirect()
            ->route('bike-racks.show', [
                'bike_rack' => $bike_rack,
            ])
            ->with('success', 'Примітку видалено.');
    }
}
