<?php


namespace App\Models;


class Role extends \Spatie\Permission\Models\Role
{
    const OWNER = 'owner';
    const ADMIN = 'admin';
}
