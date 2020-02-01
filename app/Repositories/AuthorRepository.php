<?php

namespace App\Repositories;


use App\Filters\Author\SelectFilter;
use App\Filters\Author\WithNewsFilter;
use App\User;
use Illuminate\Support\Facades\Hash;

/**
 * Class NewsRepository
 * @package App\Repositories
 */
class AuthorRepository extends Repository
{

	/**
	 * Get the News Model class name.
	 *
	 * @return string
	 */
	public function model()
	{
		return User::class;
	}

	public function store(array $data)
	{
		return parent::store([
			'name' => $data['name'],
			'email' => $data['email'],
			'password' => Hash::make($data['password']),
		]);
	}

	/**
	 * @param string $name
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function getPaginator($name = 'getPaginate')
	{
		return $this
			->addGroupFilters($name)
			->paginate();
	}

	public function getModel($id, $group = 'getModel')
	{
		return parent::getModel($id, $group);
	}

	/**
	 * @return array
	 */
	protected function groupFilters(): array
	{
		return [
			'getModel' => [
				SelectFilter::class,
				WithNewsFilter::class,
			],
		];
	}
}