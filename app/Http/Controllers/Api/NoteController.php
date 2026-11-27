<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Http\Resources\NoteResource;
use App\Models\BikeRack;
use App\Models\Note;
use App\Services\NoteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class NoteController extends Controller
{
    public function index(
        BikeRack $bike_rack,
    ): AnonymousResourceCollection {
        Gate::authorize(
            'viewAny',
            [Note::class, $bike_rack],
        );

        return NoteResource::collection(
            $bike_rack->notes()->get()
        );
    }

    public function show(
        BikeRack $bike_rack,
        Note $note,
    ): NoteResource {
        Gate::authorize('view', $note);

        return new NoteResource($note);
    }

    public function store(
        StoreNoteRequest $request,
        BikeRack $bike_rack,
        NoteService $service,
    ): JsonResponse {
        $note = $service->create(
            $request->user(),
            $bike_rack,
            $request->noteData(),
        );

        return (new NoteResource($note))
            ->response()
            ->setStatusCode(201)
            ->header('Location', route(
                'api.bike-racks.notes.show',
                [
                    'bike_rack' => $bike_rack,
                    'note' => $note,
                ],
            ));
    }

    public function update(
        UpdateNoteRequest $request,
        BikeRack $bike_rack,
        Note $note,
        NoteService $service,
    ): NoteResource {
        $note = $service->update(
            $request->user(),
            $note,
            $request->noteData(),
        );

        return new NoteResource($note);
    }

    public function destroy(
        Request $request,
        BikeRack $bike_rack,
        Note $note,
        NoteService $service,
    ): Response {
        $service->delete(
            $request->user(),
            $note,
        );

        return response()->noContent();
    }
}
