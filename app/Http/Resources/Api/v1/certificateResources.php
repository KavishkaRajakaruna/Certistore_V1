<?php

namespace App\Http\Resources\Api\v1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class certificateResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            'CertiName' => $this->CertiName,
            'source' => $this->source,
            'type' => $this->type,
            'level' => $this->level,
            'url' => 'Storage::temporyUrl($this->location, now()->addMinutes(30))',
        ];
    }
}
