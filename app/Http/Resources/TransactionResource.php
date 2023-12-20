<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'operation' => $this->operation,
            'account' => $this->account_id,
            'amount' => $this->amount,
            'operation_date' => Carbon::parse($this->created_at)->format('d-m-Y H:i:s'),
        ];
    }
}
