<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Db;

/**
 * @property int $user_id
 * @property int $permission_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class AdminUserPermission extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_user_permission';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'permission_id',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['user_id' => 'integer', 'permission_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public static function permission($user_id)
    {
        return Db::table('admin_user_permission as u_p')
            ->select([
                'u_p.permission_id',
                'p.name'
            ])
            ->leftJoin('admin_permission as p', 'p.id', '=', 'u_p.permission_id')
            ->where('u_p.user_id', $user_id)
            ->get();
    }

    public static function users($permission_id)
    {
        return Db::table('admin_user_permission as u_p')
            ->select([
                'u_p.user_id',
                'u.name'
            ])
            ->leftJoin('admin_user as u', 'u.id', '=', 'u_p.user_id')
            ->where('u_p.permission_id', $permission_id)
            ->get();
    }
}