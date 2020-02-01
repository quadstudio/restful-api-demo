<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageRequest;
use App\Http\Resources\ImageResource;
use App\Repositories\ImageRepository;
use Illuminate\Http\Response;
use Intervention\Image\Facades\Image;

class ImageController extends Controller
{

	/**
	 * @var ImageRepository
	 */
	private $images;

	/**
	 * NewsController constructor.
	 *
	 * @param ImageRepository $images
	 */
	public function __construct(ImageRepository $images)
	{

		$this->images = $images;
	}

	public function store(ImageRequest $request)
	{

		$requestData = $request->validationData();
		$data = $requestData['data']['attributes'];

		$image = $data['image']->store('images', 'public');
		Image::make($data['image'])
			->fit($data['width'], $data['height'])
			->save(storage_path('app/public/images/'.$data['image']->hashName()));
		$imageModel = $this->images->store([
			'path' => '/storage/'.$image,
			'width' => $data['width'],
			'height' => $data['height'],
			'storage' => $data['storage'],
		]);
		return response()->json(
			ImageResource::make($imageModel),
			Response::HTTP_CREATED
		);
	}
}
