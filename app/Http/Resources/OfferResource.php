<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category' => $this->category->name,
            'name' => $this->title,
            'sub_name' => $this->sub_title,
            'slug' => $this->slug,
            'description' => $this->description,
            'image' => $this->thumbnail_path
                ? asset('storage/' . $this->thumbnail_path)
                : null,
            'price before' => $this->original_price,
            'discount percentage %' => $this->discount_percentage,
            'price' => $this->price,
            'gift' => $this->gift,
            'rating' => $this->rating,
            'type' => 'offer',

        ];    }
}
