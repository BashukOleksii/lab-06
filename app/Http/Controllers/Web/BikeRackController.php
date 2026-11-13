<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexBikeRackRequest;
use App\Models\District;
use App\Queries\BikeRackListQuery;
use Illuminate\Contracts\View\View;

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
}
