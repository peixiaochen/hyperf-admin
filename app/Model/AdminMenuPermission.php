<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Db;

/**
 * @property int $menu_id
 * @property int $permission_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class AdminMenuPermission extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_menu_permission';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'menu_id',
        'permission_id',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['menu_id' => 'integer', 'permission_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public static function permissions($menu_id)
    {
        return Db::table('admin_menu_permission as m_p')
            ->select([
                'm_p.permission_id',
                'p.name'
            ])
            ->leftJoin('admin_permission as p', 'p.id', '=', 'm_p.permission_id')
            ->where('m_p.menu_id', $menu_id)
            ->get();
    }
}