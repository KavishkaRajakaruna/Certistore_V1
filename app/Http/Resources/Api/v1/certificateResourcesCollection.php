<?php

namespace App\Http\Resources\Api\v1;

use Illuminate\Http\Resources\Json\ResourceCollection;

class certificateResourcesCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Api\v1\certificateResources';
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//        return parent::toArray($request);
        return [
            'data' => $this->collection,
        ];

    }
}
