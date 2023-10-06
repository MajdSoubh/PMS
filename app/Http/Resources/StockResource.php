<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *    schema="StockSchema",
 *        @OA\Property(
 *            property="id",
 *            description="Drug identifier",
 *            type="integer",
 *            nullable="false",
 *        ),
 *        @OA\Property(
 *            property="mfd",
 *            description="Drug manufactoring date",
 *            type="date",
 *            nullable="false",
 *        ),
 *        @OA\Property(
 *            property="exp",
 *            description="Drug expiration date",
 *            type="date",
 *            nullable="false",
 *        ),
 *        @OA\Property(
 *            property="drug",
 *            description="Related drug",
 *            type="object",
 *            nullable="false",
 *            ref="#/components/schemas/DrugSchema",
 *        ),
 *        @OA\Property(
 *            property="qty",
 *            description="Drug quantity",
 *            type="int",
 *            nullable="false",
 *        ),
 *        @OA\Property(
 *            property="cost",
 *            description="Drug cost",
 *            type="double",
 *            nullable="false",
 *        ),
 *        @OA\Property(
 *            property="pharmacy",
 *            description="Related pharmacy",
 *            type="object",
 *            nullable="false",
 *              ref="#/components/schemas/PharmacySchema",
 *        ),
 *    )
 * )
 */
class StockResource extends JsonResource
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
            'id' => $this->id,
            'mfd' => $this->mfd,
            'exp' => $this->exp,
            'drug' => $this->whenLoaded('drug'),
            'qty' => $this->qty,
            'cost' => $this->cost,
            'pharmacy' => $this->whenLoaded('pharmacy'),

        ];
    }
}
