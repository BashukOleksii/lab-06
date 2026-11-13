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
}
