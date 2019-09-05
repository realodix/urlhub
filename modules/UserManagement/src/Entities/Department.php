<?php

namespace UrlHub\UserManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config("laravel_user_management.user_department_table"));
    }

    protected $fillable = [
        'title',
        'parent_id',
    ];

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function parent()
    {
        return $this->hasOne(Department::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->belongsTo(Department::class, 'parent_id', 'id');
    }

    public function users()
    {
        $table  = config("laravel_user_management.user_department_user_table");

        return $this->belongsToMany(
            User::class,
            $table,
            'department_id',
            'user_id'
        );
    }
}
