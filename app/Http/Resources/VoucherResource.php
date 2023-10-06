<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VoucherResource extends JsonResource
{
    /**
     * @OA\Schema(
     *    schema="VoucherSchema",
     *        @OA\Property(
     *            property="id",
     *            description="Voucher identifier",
     *            type="integer",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="type",
     *            description="Voucher type",
     *            type="string",
     *            nullable="false",
     *          enum={
     *             "cash",
     *             "payment",
     *             "recipt"
     *          }
     *        ),
     *        @OA\Property(
     *            property="amount",
     *            description="Voucher amount",
     *            type="double",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="description",
     *            description="Voucher description",
     *            type="string",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="date",
     *            description="Voucher date",
     *            type="date",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="pharmacy",
     *            description="Related pharmacy",
     *            type="object",
     *            nullable="false",
     *              ref="#/components/schemas/PharmacySchema",
     *        ),
     *
     * )
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'amount' => $this->amount,
            'description' => $this->description,
            'date' => $this->date,
            'pharmacy' => $this->whenLoaded('pharmacy'),
        ];
    }
}
