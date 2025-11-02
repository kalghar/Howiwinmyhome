<?php

interface AuthInterface
{
    public function authenticate(string $email, string $password): array|false;
}