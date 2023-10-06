<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *    schema="CustomerSchema",
 *        @OA\Property(
 *            property="id",
 *            description="Customer identifier",
 *            type="integer",
 *            nullable="false",
 *        ),
 *        @OA\Property(
 *            property="name",
 *            description="Cutomer name",
 *            type="string",
 *            nullable="false",
 *        ),
 *        @OA\Property(
 *            property="contact",
 *            description="Customer phone number",
 *            type="string",
 *            nullable="false",
 *        ),
 *        @OA\Property(
 *            property="address",
 *            description="Customer address",
 *            type="string",
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


class CustomerResource extends JsonResource
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
            'name' => $this->name,
            'contact' => $this->contact,
            'address' => $this->address,
            'pharmacy' => $this->whenLoaded('pharmacy'),
        ];
    }
}
