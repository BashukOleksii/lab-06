<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexConfirmationReceiptRequest;
use App\Queries\ConfirmationReceiptListQuery;
use Illuminate\Contracts\View\View;

class ConfirmationReceiptController extends Controller
{
    public function index(
        IndexConfirmationReceiptRequest $request,
        ConfirmationReceiptListQuery $listQuery,
    ): View {
        $filters = $request->validated();

        return view('confirmation-receipts.index', [
            'receipts' => $listQuery->paginate(
                $request->user(),
                $filters,
            ),
            'filters' => $filters,
        ]);
    }
}
