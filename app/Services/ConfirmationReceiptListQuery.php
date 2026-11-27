<?php

namespace App\Queries;

use App\Models\ConfirmationReceipt;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class ConfirmationReceiptListQuery
{
    public function paginate(
        User $user,
        array $filters = [],
    ): LengthAwarePaginator {
        $query = ConfirmationReceipt::query()
            ->with('bikeRack.district')
            ->whereHas('bikeRack', function (Builder $query) use ($user): void {
                $query->where('owner_id', $user->id);
            });

        $search = $filters['search'] ?? null;

        if ($search !== null && $search !== '') {
            $pattern = '%' . $search . '%';

            $query->where(function (Builder $query) use ($pattern): void {
                $query->whereLike('summary', $pattern)
                    ->orWhereHas('bikeRack', function (Builder $query) use ($pattern): void {
                        $query->whereLike('title', $pattern);
                    });
            });
        }

        return $query
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(
                perPage: (int) ($filters['per_page'] ?? 10),
                page: (int) ($filters['page'] ?? 1),
            )
            ->appends(Arr::except($filters, ['page']));
    }
}
