<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getId(),
            'full_name' => $this->getName(),
            'email' => $this->getEmail(),
            'username' => $this->when(request()->routeIs('users.show'), $this->getUsername()),
            'gender' => $this->when(request()->routeIs('users.show'), $this->getGender()),
            'country' => $this->getCountry(),
            'city' => $this->when(request()->routeIs('users.show'), $this->getCity()),
            'phone' => $this->when(request()->routeIs('users.show'), $this->getPhone()),
        ];
    }
}
