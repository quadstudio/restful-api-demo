<?php

namespace App\Repositories;


use App\Contracts\FilterInterface;
use App\Contracts\RepositoryInterface;
use App\Exceptions\BadFilterInstanceException;
use App\Exceptions\BadModelException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Response;
use Illuminate\Pipeline\Pipeline;

abstract class Repository implements RepositoryInterface
{


	/**
	 * @var \Illuminate\Database\Eloquent\Builder
	 */
	protected $builder;

	/**
	 * @var array
	 */
	private $filters;

	/**
	 * AbstractRepository constructor.
	 *
	 * @throws BadModelException
	 */
	public function __construct()
	{
		$this->boot();
		$this->reset();

	}

	/**
	 * Perform boot action.
	 */
	public function boot(): void
	{
		//
	}

	/**
	 * Reset repository query builder and filters.
	 *
	 * @return Repository
	 * @throws BadModelException
	 */
	final public function reset(): self
	{
		$this->resetBuilder();
		$this->resetFilters();

		return $this;
	}

	/**
	 * Reset model query builder.
	 * @throws BadModelException
	 */
	private function resetBuilder(): void
	{

		if (!in_array(Model::class, class_parents($this->model()))) {

			throw new BadModelException(trans('exception.filter.instance', ['instance' => Model::class]), Response::HTTP_BAD_REQUEST);
		}

		/** @var \Illuminate\Database\Eloquent\Model $model */
		$model = $this->model();

		$this->builder = $model::query();

	}

	/**
	 * Specify Model class name.
	 *
	 * @return string
	 */
	abstract public function model();

	/**
	 * Reset all filters.
	 *
	 * Add Boot and Route filters
	 *
	 * @return $this
	 */
	private function resetFilters(): self
	{
		$this->filters = [];

		$this->addBootFilters();
		$this->addRouteFilters();

		return $this;
	}

	/**
	 * Add Boot filters.
	 */
	private function addBootFilters(): void
	{
		$this->addFilters($this->bootFilters());
	}

	/**
	 * Add some user filters.
	 *
	 * @param array $filters
	 *
	 * @return $this
	 */
	final public function addFilters(array $filters): self
	{
		array_map([$this, "addFilter"], $filters);

		return $this;
	}

	/**
	 * @return array
	 */
	protected function bootFilters(): array
	{
		return [];
	}

	/**
	 * Add Route filters
	 */
	private function addRouteFilters(): void
	{
		if (request()->route() && array_key_exists(request()->route()->getName(), $this->routeFilters())) {
			$this->addFilters($this->routeFilters()[request()->route()->getName()]);
		}
	}

	/**
	 * @return array
	 */
	protected function routeFilters(): array
	{
		return [];
	}

	/**
	 * Add one user filter
	 *
	 * @param FilterInterface|string $filter
	 *
	 * @return $this
	 * @throws BadFilterInstanceException
	 */
	final public function addFilter($filter): self
	{

		if (($class = $this->isFilterInstance($filter)) === false) {
			throw new BadFilterInstanceException(trans(
				'repository::exception.filter.instance',
				['instance' => FilterInterface::class]
			));
		}

		$this->filters[$class] = $filter;

		return $this;
	}

	/**
	 * @param $filter
	 *
	 * @return bool|string
	 */
	private function isFilterInstance($filter)
	{
		if (is_string($filter) && in_array(FilterInterface::class, class_implements($filter))) {
			return $filter;
		} elseif (is_object($filter) && $filter instanceof FilterInterface) {
			return get_class($filter);
		} else {
			return false;
		}
	}

	/**
	 * @param array $data
	 *
	 * @return Model
	 */
	public function store(array $data)
	{
		return $this->builder()->create($data);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	private function builder(): Builder
	{
		return $this->builder;
	}

	/**
	 * @param Relation $relation
	 * @param array $data
	 *
	 * @return Model
	 */
	public function createRelation(Relation $relation, array $data)
	{
		return $relation->create($data);
	}

	public function update($id, array $data)
	{
		$model = $this->find($id);

		$this->beforeUpdate($model, $data);

		$model->update($data);

		$this->afterUpdate($model, $data);

		return $model;
	}

	function find($id)
	{
		$this->applyFilters();

		$builder = $this->builder()->where($this->getKey(), $id);

		return $builder->firstOrFail();
	}

	/**
	 * Apply filters
	 *
	 * @return $this
	 */
	private function applyFilters(): self
	{

		$this->loadFilters();

		$this->builder = (app(Pipeline::class))
			->send($this->builder())
			->through($this->filters())
			->thenReturn();

		return $this;
	}

	/**
	 * Make objects of filters from class names array
	 */
	private function loadFilters(): void
	{
		array_walk($this->filters, function (&$filter) {
			$filter = $this->loadFilter($filter);
		});
	}

	/**
	 * Make filter instance
	 *
	 * @param string $filter
	 *
	 * @return FilterInterface
	 */
	private function loadFilter($filter): FilterInterface
	{

		return $filter instanceof FilterInterface ? $filter : app($filter);
	}

	/**
	 * @return array
	 */
	final public function filters(): array
	{
		return $this->filters;
	}

	/**
	 * @return string
	 */
	public function getKey()
	{

		$model = $this->builder()->getModel();
		$key = $model->getRouteKeyName();

		return $model->qualifyColumn($key);
	}

	protected function beforeUpdate(Model $model, array $data)
	{

	}

	protected function afterUpdate(Model $model, array $data)
	{

	}

	/**
	 * @param $id
	 *
	 * @return bool|null
	 * @throws \Exception
	 */
	public function destroy($id)
	{
		$model = $this->find($id);

		return $model->delete();

	}

	/**
	 * @param $chunk
	 * @param callable $callback
	 *
	 * @return bool
	 */
	public function chunk($chunk, callable $callback)
	{

		$this->applyFilters();

		return $this->builder()->chunk($chunk, $callback);
	}

	/**
	 * @return int
	 */
	public function count(): int
	{

		$this->applyFilters();

		return $this->builder()->count();
	}

	/**
	 * @param $offset
	 * @param $limit
	 * @param array $columns
	 *
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function offset($offset, $limit, $columns = ['*'])
	{

		$this->applyFilters();

		return $this->builder()
			->select($columns)
			->offset($offset)
			->limit($limit)
			->get();
	}

	/**
	 * @param $id
	 * @param null $group
	 *
	 * @return Model
	 * @throws BadModelException
	 */
	public function getModel($id, $group = null)
	{
		return $this
			->reset()
			->addGroupFilters($group ?? [])
			->find($id);
	}

	/**
	 * @param string|array $group
	 *
	 * @return $this
	 */
	final public function addGroupFilters($group): self
	{


		if (!empty($this->groupFilters())) {
			$groups = (array)$group;

			foreach ($this->groupFilters() as $group => $filters) {

				if (in_array($group, $groups)) {

					$this->addFilters($filters);
				}

			}
		}

		return $this;
	}

	/**
	 * User-defined group filters
	 *
	 * @return array
	 */
	protected function groupFilters(): array
	{
		return [];
	}

	/**
	 * @param null $group
	 *
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getCollection($group = null)
	{
		return $this
			->addGroupFilters($group ?? [])
			->get();
	}

	/**
	 * @param array $columns
	 *
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	function get($columns = ['*'])
	{

		$this->applyFilters();

		$builder = $this
			->builder()
			->select($columns);

		return $builder->get();
	}

	/**
	 * @param string $group
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	protected function getPaginator($group = null)
	{
		return $this
			->addGroupFilters($group ?? [])
			->paginate();
	}

	/**
	 * @param null $perPage
	 * @param array $columns
	 * @param string $pageName
	 * @param null $page
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
	{

		$this->applyFilters();

		return $this
			->builder()
			->paginate($perPage, $columns, $pageName, $page)
			->appends((request()->except(['page', '_token'])));
	}

}