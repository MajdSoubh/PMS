<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *    schema="PurchaseSchema",
 *        @OA\Property(
 *            property="id",
 *            description="Purchase identifier",
 *            type="integer",
 *            nullable="false",
 *        ),
 *        @OA\Property(
 *            property="total",
 *            description="Purchase total price",
 *            type="integer",
 *            nullable="false",
 *        ),
 *        @OA\Property(
 *            property="paid",
 *            description="Paid amount",
 *            type="integer",
 *            nullable="false",
 *        ),
 *        @OA\Property(
 *            property="date",
 *            description="Date of Purchase created",
 *            type="date",
 *            nullable="false",
 *        ),
 *        @OA\Property(
 *            property="supplier",
 *            description="Supplier related to Purchase",
 *            type="object",
 *            nullable="false",
 *   ref="#/components/schemas/SupplierSchema",
 *        ),
 * )
 *        ),
 *    )
 * )
 */
class PurchaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "total" => $this->total,
            "paid" => $this->paid,
            "rest" => $this->rest,
            "date" => $this->date,
            "items" => $this->whenLoaded('purchaseItems'),
            "supplier" => $this->whenLoaded('supplier'),
            "pharmacy" => $this->whenLoaded('pharmacy'),
        ];
    }
}
