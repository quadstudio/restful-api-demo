<?php

namespace App\Contracts;

interface RepositoryInterface
{

	function find($id);

	function get($columns = ['*']);

	function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null);

	function count();

	function chunk($chunk, callable $callback);

	function offset($offset, $limit, $columns = ['*']);

	function filters(): array;

	function store(array $data);

	function update($id, array $data);

	function destroy($id);

}