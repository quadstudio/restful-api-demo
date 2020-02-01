<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class NewsResource
 * @package App\Http\Resources
 */
class NewsShowResource extends JsonResource
{
    /**
     * Transform the news resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

	    return [
		    'type' => 'news',
		    'id' => $this->getKey(),
		    'attributes' => [
			    'title' => $this->getAttribute('title'),
			    'annotation' => $this->getAttribute('annotation'),
			    'body' => $this->getAttribute('body'),
			    'published_at_ru' => $this->getAttribute('published_at')->format('d.m.Y'),
			    'published_at' => $this->getAttribute('published_at')->format('Y-m-d'),
		    ],
		    "links" => [
			    "self" => url("/news/{$this->getkey()}"),
		    ],
		    'relationships' => [
			    'author' => AuthorResource::make($this->getAttribute('author')),
			    'image' => ImageResource::make($this->getAttribute('image')),
		    ],
	    ];
    }
}
