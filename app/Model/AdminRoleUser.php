<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Db;

/**
 * @property int $role_id
 * @property int $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class AdminRoleUser extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_role_user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id',
        'user_id',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['role_id' => 'integer', 'user_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public static function roles($user_id)
    {
        return Db::table('admin_role_user as r_u')
            ->select([
                'r_u.role_id',
                'r.name'
            ])
            ->leftJoin('admin_role as r', 'r.id', '=', 'r_u.role_id')
            ->where('r_u.user_id', $user_id)
            ->get();
    }

    public static function users($role_id)
    {
        return Db::table('admin_role_user as r_u')
            ->select([
                'r_u.user_id',
                'u.name'
            ])
            ->leftJoin('admin_user as u', 'u.id', '=', 'r_u.role_id')
            ->where('r_u.role_id', $role_id)
            ->get();
    }
}