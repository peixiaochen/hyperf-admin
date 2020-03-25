<?php

declare (strict_types=1);

namespace App\Model;

/**
 * @property int $id
 * @property int $parent_id
 * @property int $order
 * @property string $title
 * @property string $icon
 * @property string $uri
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class AdminMenu extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_menu';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'title',
        'icon',
        'uri',
        'order',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'         => 'int',
        'parent_id'  => 'integer',
        'order'      => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany(AdminRole::class, 'admin_role_menu', 'menu_id', 'role_id');
    }

    public function menuRoles()
    {
        return $this->hasMany(AdminRoleMenu::class, 'menu_id', 'id');
    }

    public function permission()
    {
        return $this->belongsToMany(AdminPermission::class, 'admin_menu_permission', 'menu_id', 'permission_id');
    }

    public function menuPermission()
    {
        return $this->hasMany(AdminMenuPermission::class, 'menu_id', 'id');
    }


    public static function sortAdminMenu($order_id, &$start = 1, $parent_id = 1)
    {
        foreach ($order_id as $k => $v) {
            AdminMenu::query()->where('id', (int)$v['id'])->update(['order' => $start, 'parent_id' => $parent_id]) && $start++;
            isset($v['children']) && self::sortAdminMenu($v['children'], $start, (int)$v['id']);
        }
        return true;
    }

    public static function getAdminMenuOne($id)
    {
        $info              = self::query()->find($id);
        $info->roles       = AdminRoleMenu::roles($info->id);
        $info->peimissions = AdminMenuPermission::permissions($info->id);
        return $info;
    }

    public static function getAdminMenuTree($parent_id = 1, array $menu_ids = [])
    {
        $AdminMenuQuery = self::query()
            ->where([['parent_id', '=', $parent_id]]);
        if ($menu_ids) {
            $AdminMenuQuery->whereIn('id', $menu_ids);
        }
        return $AdminMenuQuery
            ->orderBy('order', 'asc')
            ->orderBy('id', 'asc')
            ->get()
            ->each(function ($item, $key) use ($menu_ids) {
                $item->child_data = self::getAdminMenuTree($item->id, $menu_ids);
                return $item;
            });
    }
}