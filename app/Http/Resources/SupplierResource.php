<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *    schema="SupplierSchema",
 *        @OA\Property(
 *            property="id",
 *            description="Supplier identifier",
 *            type="integer",
 *            nullable="false",
 *        ),
 *        @OA\Property(
 *            property="name",
 *            description="Supplier name",
 *            type="string",
 *            nullable="false",
 *        ),
 *        @OA\Property(
 *            property="contact",
 *            description="Supplier phone number",
 *            type="string",
 *            nullable="false",
 *        ),
 *        @OA\Property(
 *            property="address",
 *            description="Supplier address",
 *            type="string",
 *            nullable="false",
 *        ),
 *        @OA\Property(
 *            property="email",
 *            description="Supplier email",
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
class SupplierResource extends JsonResource
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
            'email' => $this->email,
            'contact' => $this->contact,
            'address' => $this->address,
            'pharmacy' => $this->whenLoaded('pharmacy'),
        ];
    }
}
