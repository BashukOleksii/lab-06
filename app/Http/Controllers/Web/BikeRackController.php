<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexBikeRackRequest;
use App\Models\District;
use App\Queries\BikeRackListQuery;
use Illuminate\Contracts\View\View;
use App\Http\Requests\StoreBikeRackRequest;
use App\Models\BikeRack;
use App\Services\BikeRackWriter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use App\Exceptions\BikeRackOpeningException;
use App\Http\Requests\UpdateBikeRackRequest;
use Illuminate\Http\Request;

class BikeRackController extends Controller
{
    public function index(
        IndexBikeRackRequest $request,
        BikeRackListQuery $listQuery,
    ): View {
        $filters = $request->validated();

        return view('bike-racks.index', [
            'bikeRacks' => $listQuery->paginate($request->user(), $filters),
            'districts' => District::query()->orderBy('name')->get(),
            'filters' => $filters,
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', BikeRack::class);

        return view('bike-racks.create', [
            'districts' => District::query()->orderBy('name')->get(),
        ]);
    }

    public function store(
        StoreBikeRackRequest $request,
        BikeRackWriter $writer,
    ): RedirectResponse {
        $bikeRack = $writer->create(
            $request->user(),
            $request->bikeRackData(),
        );

        return redirect()
            ->route('bike-racks.show', ['bike_rack' => $bikeRack])
            ->with('success', 'Велопарковку збережено як чернетку.');
    }

    public function show(BikeRack $bike_rack): View
    {
        Gate::authorize('view', $bike_rack);

        return view('bike-racks.show', [
            'bikeRack' => $bike_rack->load('district'),
        ]);
    }

    public function edit(BikeRack $bike_rack): View
    {
        Gate::authorize('update', $bike_rack);

        return view('bike-racks.edit', [
            'bikeRack' => $bike_rack,
            'districts' => District::query()->orderBy('name')->get(),
        ]);
    }

    public function update(
        UpdateBikeRackRequest $request,
        BikeRack $bike_rack,
        BikeRackWriter $writer,
    ): RedirectResponse {
        $bikeRack = $writer->update(
            $request->user(),
            $bike_rack,
            $request->bikeRackData(),
        );

        return redirect()
            ->route('bike-racks.show', ['bike_rack' => $bikeRack])
            ->with('success', 'Зміни збережено.');
    }

    public function destroy(
        Request $request,
        BikeRack $bike_rack,
        BikeRackWriter $writer,
    ): RedirectResponse {
        $writer->delete($request->user(), $bike_rack);

        return redirect()
            ->route('bike-racks.index')
            ->with('success', 'Велопарковку видалено.');
    }

    public function open(
        Request $request,
        BikeRack $bike_rack,
        BikeRackWriter $writer,
    ): RedirectResponse {
        try {
            $bikeRack = $writer->open($request->user(), $bike_rack);
        } catch (BikeRackOpeningException $exception) {
            return redirect()
                ->route('bike-racks.show', ['bike_rack' => $bike_rack])
                ->with('opening_error', $exception->getMessage());
        }

        return redirect()
            ->route('bike-racks.show', ['bike_rack' => $bikeRack])
            ->with('success', 'Відкриття велопарковки підтверджено.');
    }
}
