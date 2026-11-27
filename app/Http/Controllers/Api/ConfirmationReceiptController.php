<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexConfirmationReceiptRequest;
use App\Http\Resources\ConfirmationReceiptResource;
use App\Queries\ConfirmationReceiptListQuery;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ConfirmationReceiptController extends Controller
{
    public function index(
        IndexConfirmationReceiptRequest $request,
        ConfirmationReceiptListQuery $listQuery,
    ): AnonymousResourceCollection {
        $receipts = $listQuery->paginate(
            $request->user(),
            $request->validated(),
        );

        return ConfirmationReceiptResource::collection(
            $receipts
        );
    }
}
