<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *    schema="DrugSchema",
 *        @OA\Property(
 *            property="id",
 *            description="Drug identifier",
 *            type="integer",
 *            nullable="false",
 *        ),
 *        @OA\Property(
 *            property="name",
 *            description="Drug name",
 *            type="string",
 *            nullable="false",
 *        ),
 *        @OA\Property(
 *            property="generic",
 *            description="Drug generic name",
 *            type="string",
 *            nullable="false",
 *        ),
 *        @OA\Property(
 *            property="description",
 *            description="Drug description",
 *            type="string",
 *            nullable="false",
 *        ),
 *        @OA\Property(
 *            property="price",
 *            description="Drug price",
 *            type="int",
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

class DrugResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    /*    public static $wrap = null; */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'generic' => $this->generic,
            'description' => $this->description,
            'image' => $this->whenNotNull($this->image),
            'price' => $this->price,
            'pharmacy' => $this->whenLoaded('pharmacy'),
        ];
    }
}
