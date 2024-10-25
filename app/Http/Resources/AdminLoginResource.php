<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminLoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => 'Login successful',
            'token' => $this->plainTextToken,
            'admin' => [
                'id' => $this->id,
                'email' => $this->email,
                // Add any other admin details you want to return
            ],
        ];
    }
}
