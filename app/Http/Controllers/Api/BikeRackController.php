<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\BikeRackOpeningException;
use App\Http\Controllers\Controller;
use App\Http\Requests\IndexBikeRackRequest;
use App\Http\Requests\StoreBikeRackRequest;
use App\Http\Requests\UpdateBikeRackRequest;
use App\Http\Resources\BikeRackResource;
use App\Http\Resources\DistrictResource;
use App\Models\BikeRack;
use App\Models\District;
use App\Queries\BikeRackListQuery;
use App\Services\BikeRackWriter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class BikeRackController extends Controller
{
    public function index(
        IndexBikeRackRequest $request,
        BikeRackListQuery $listQuery,
    ): AnonymousResourceCollection {
        $bikeRacks = $listQuery->paginate(
            $request->user(),
            $request->validated(),
        );

        return BikeRackResource::collection($bikeRacks);
    }

    public function districts(): AnonymousResourceCollection
    {
        $districts = District::query()->orderBy('name')->get();

        return DistrictResource::collection($districts);
    }

    public function show(BikeRack $bike_rack): BikeRackResource
    {
        Gate::authorize('view', $bike_rack);

        return new BikeRackResource($bike_rack->load('district'));
    }

    public function store(
        StoreBikeRackRequest $request,
        BikeRackWriter $writer,
    ): JsonResponse {
        $bikeRack = $writer->create(
            $request->user(),
            $request->bikeRackData(),
        );

        return (new BikeRackResource($bikeRack->load('district')))
            ->response()
            ->setStatusCode(201)
            ->header('Location', route('api.bike-racks.show', [
                'bike_rack' => $bikeRack,
            ]));
    }

    public function update(
        UpdateBikeRackRequest $request,
        BikeRack $bike_rack,
        BikeRackWriter $writer,
    ): BikeRackResource {
        $bikeRack = $writer->update(
            $request->user(),
            $bike_rack,
            $request->bikeRackData(),
        );

        return new BikeRackResource($bikeRack->load('district'));
    }

    public function destroy(
        Request $request,
        BikeRack $bike_rack,
        BikeRackWriter $writer,
    ): Response {
        $writer->delete($request->user(), $bike_rack);

        return response()->noContent();
    }

    public function open(
        Request $request,
        BikeRack $bike_rack,
        BikeRackWriter $writer,
    ): BikeRackResource|JsonResponse {
        try {
            $bikeRack = $writer->open($request->user(), $bike_rack);
        } catch (BikeRackOpeningException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 409);
        }

        return new BikeRackResource($bikeRack->loadMissing('district'));
    }
}
