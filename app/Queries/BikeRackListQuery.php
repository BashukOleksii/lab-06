<?php

namespace App\Queries;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class BikeRackListQuery
{
    public const SORTS = [
        'opening_desc' => ['scheduled_opening_on', 'desc'],
        'opening_asc' => ['scheduled_opening_on', 'asc'],
        'title_asc' => ['title', 'asc'],
        'title_desc' => ['title', 'desc'],
        'slots_asc' => ['slot_count', 'asc'],
        'slots_desc' => ['slot_count', 'desc'],
        'created_desc' => ['created_at', 'desc'],
    ];

    public const DEFAULT_SORT = 'opening_desc';

    public function paginate(
        User $user,
        array $filters = [],
    ): LengthAwarePaginator {
        $query = $user->bikeRacks()->with('district');

        $districtId = $filters['district_id'] ?? null;
        $status = $filters['status'] ?? null;
        $search = $filters['search'] ?? null;
        $sort = $filters['sort'] ?? self::DEFAULT_SORT;

        if ($districtId !== null) {
            $query->where('district_id', $districtId);
        }

        if ($status !== null) {
            $query->where('status', $status);
        }

        if ($search !== null && $search !== '') {
            $pattern = '%' . $search . '%';

            $query->where(
                function (Builder $query) use ($pattern): void {
                    $query->whereLike('title', $pattern)
                        ->orWhereLike('description', $pattern);
                }
            );
        }

        [$column, $direction] = self::SORTS[$sort]
            ?? self::SORTS[self::DEFAULT_SORT];

        return $query
            ->orderBy($column, $direction)
            ->orderByDesc('id')
            ->paginate(
                perPage: (int) ($filters['per_page'] ?? 10),
                page: (int) ($filters['page'] ?? 1),
            )
            ->appends(Arr::except($filters, ['page']));
    }
}
