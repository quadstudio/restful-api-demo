<?php

namespace App\Repositories;


use App\Filters\News\OrderByFilter;
use App\Filters\News\SelectFullFilter;
use App\Filters\News\SelectLongFilter;
use App\Filters\News\SelectShortFilter;
use App\Filters\News\WithAuthorFilter;
use App\Filters\News\WithImageFilter;
use App\Models\Image;
use App\Models\News;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * Class NewsRepository
 * @package App\Repositories
 */
class NewsRepository extends Repository
{

	/**
	 * Get the News Model class name.
	 *
	 * @return string
	 */
	public function model()
	{
		return News::class;
	}

	public function createRelation(Relation $relation, array $data)
	{
		$model = parent::createRelation($relation, $data);
		$this->saveImage($model, $data);
		return $model;
	}

	private function saveImage(Model $model, array $data)
	{
		if (isset($data['image'])) {
			Image::query()->find($data['image'])->update([
				'imageable_type' => 'news',
				'imageable_id' => $model->getKey(),
			]);
		}

	}

	protected function afterUpdate(Model $model, array $data)
	{
		$this->saveImage($model, $data);
	}

	public function getModel($id, $group = 'getFullModel')
	{
		return parent::getModel($id, $group);
	}

	/**
	 * @return array
	 */
	protected function groupFilters(): array
	{
		return [
			'getAll' => [
				SelectShortFilter::class,
				WithAuthorFilter::class,
				WithImageFilter::class,
				OrderByFilter::class,
			],
			'getLongModel' => [
				SelectLongFilter::class,
				WithAuthorFilter::class,
				WithImageFilter::class,
			],
			'getFullModel' => [
				SelectFullFilter::class,
				WithAuthorFilter::class,
				WithImageFilter::class,
			],
		];
	}
}