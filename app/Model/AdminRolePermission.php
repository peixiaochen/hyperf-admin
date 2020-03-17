<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Db;

/**
 * @property int $role_id
 * @property int $permission_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class AdminRolePermission extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_role_permission';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id',
        'permission_id',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'role_id'       => 'integer',
        'permission_id' => 'integer',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime'
    ];

    public static function permissions($role_id)
    {
        return Db::table('admin_role_permission as r_p')
            ->select([
                'r_p.permission_id',
                'p.name'
            ])
            ->leftJoin('admin_permission as p', 'p.id', '=', 'r_p.permission_id')
            ->where('r_p.role_id', $role_id)
            ->get();
    }

    public static function roles($permission_id)
    {
        return Db::table('admin_role_permission as r_p')
            ->select([
                'r_p.role_id',
                'r.name'
            ])
            ->leftJoin('admin_role as r', 'r.id', '=', 'r_p.role_id')
            ->where('r_p.permission_id', $permission_id)
            ->get();
    }
}