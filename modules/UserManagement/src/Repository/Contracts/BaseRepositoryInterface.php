<?php

namespace UrlHub\UserManagement\Repository\Contracts;

interface BaseRepositoryInterface
{
    public function all(array $columns = [], array $relations = [], array $pluck = []);

    public function find(int $ID, array $columns = null);

    public function store(array $item);

    public function update(int $ID, array $item);

    public function delete(int $ID);

    public function findBy(array $criteria, array $columns = [], bool $single = true);

    public function updateBy(array $criteria, array $data);

    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null);

}
