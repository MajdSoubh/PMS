<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *    schema="InvoiceSchema",
 *        @OA\Property(
 *            property="id",
 *            description="Invoice identifier",
 *            type="integer",
 *            nullable="false",
 *        ),
 *        @OA\Property(
 *            property="total",
 *            description="Invoice total price",
 *            type="integer",
 *            nullable="false",
 *        ),
 *        @OA\Property(
 *            property="totalDiscount",
 *            description="Invoice total discount",
 *            type="integer",
 *            nullable="false",
 *        ),
 *        @OA\Property(
 *            property="totalNet",
 *            description="Invoice total net",
 *            type="integer",
 *            nullable="false",
 *        ),
 *        @OA\Property(
 *            property="totalProfit",
 *            description="Invoice total Profit",
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
 *            description="Date of invoice created",
 *            type="date",
 *            nullable="false",
 *        ),
 *        @OA\Property(
 *            property="customer",
 *            description="Customer related to invoice",
 *            type="object",
 *            nullable="false",
 *   ref="#/components/schemas/CustomerSchema",
 *        ),
 * )
 *        ),
 *    )
 * )
 */




class InvoiceResource extends JsonResource
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
            "totalDiscount" => $this->totalDiscount,
            "totalNet" => $this->totalNet,
            "totalProfit" => $this->totalProfit,
            "paid" => $this->paid,
            "rest" => $this->rest,
            "date" => $this->date,
            "customer" => $this->whenLoaded('customer'),
            "items" => $this->whenLoaded('invoiceItems'),
            "pharmacy" => $this->whenLoaded('pharmacy'),
        ];
    }
}
