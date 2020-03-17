<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Db;

/**
 * @property int $role_id
 * @property int $menu_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class AdminRoleMenu extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_role_menu';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id',
        'menu_id',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'role_id'    => 'integer',
        'menu_id'    => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public static function roles($menu_id)
    {
        return Db::table('admin_role_menu as r_m')
            ->select([
                'r_m.role_id',
                'r.name'
            ])
            ->leftJoin('admin_role as r', 'r.id', '=', 'r_m.role_id')
            ->where('r_m.menu_id', $menu_id)
            ->get();
    }

    public static function menus($role_id)
    {
        return Db::table('admin_role_menu as r_m')
            ->select([
                'r_m.menu_id',
                'm.title'
            ])
            ->leftJoin('admin_menu as m', 'm.id', '=', 'r_m.menu_id')
            ->where('r_m.role_id', $role_id)
            ->get();
    }
}