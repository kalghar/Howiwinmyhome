<?php

interface OrmInterface
{
    public function getById(int $id): array|false;

    public function delete(int $id): bool;
}