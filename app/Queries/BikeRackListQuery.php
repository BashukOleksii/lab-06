<?php

namespace App\Queries;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class BikeRackListQuery
{
    public function paginate(User $user, array $filters = []): LengthAwarePaginator
    {
        $query = $user->bikeRacks()->with('district');

        $districtId = $filters['district_id'] ?? null;
        $status = $filters['status'] ?? null;
        $search = $filters['search'] ?? null;

        if ($districtId !== null) {
            $query->where('district_id', $districtId);
        }

        if ($status !== null) {
            $query->where('status', $status);
        }

        if ($search !== null && $search !== '') {
            $pattern = '%' . $search . '%';

            $query->where(function (Builder $query) use ($pattern): void {
                $query->whereLike('title', $pattern)
                    ->orWhereLike('description', $pattern);
            });
        }

        return $query
            ->orderByDesc('scheduled_opening_on')
            ->orderByDesc('id')
            ->paginate(
                perPage: (int) ($filters['per_page'] ?? 10),
                page: (int) ($filters['page'] ?? 1),
            )
            ->appends($filters);
    }
}
