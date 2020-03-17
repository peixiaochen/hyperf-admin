<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $status
 * @property string $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class AdminRole extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_role';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'status',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'         => 'int',
        'status'     => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public static function getAdminRoleList($param)
    {
        $AdminRoleQuery = self::AdminRoleQuery();
        if ($param['keywords']) {
            $AdminRoleQuery = $AdminRoleQuery->where(function ($query) use ($param) {
                $query->where('name', 'like', "%{$param['keywords']}%")
                    ->orWhere('description', 'like', "%{$param['keywords']}%");
            });
        }
        return [
            'count'     => $AdminRoleQuery->count('id'),
            'data_list' => $AdminRoleQuery
                ->forPage($param['page'], $param['limit'])
                ->orderBy('id', 'desc')
                ->get()
                ->each(function ($item, $key) {
                    return self::eachFunction($item, $key);
                })
        ];

    }

    public static function getAdminRoleOne($id)
    {
        return self::AdminRoleQuery()
            ->where('id', $id)
            ->limit(1)
            ->get()
            ->each(function ($item, $key) {
                return self::eachFunction($item, $key);
            })
            ->first();
    }

    public static function AdminRoleQuery()
    {
        return self::query()
            ->select([
                'id',
                'name',
                'description',
                'status',
                'created_at',
                'updated_at'
            ]);
    }

    public static function eachFunction($item)
    {
        $item->munus       = AdminRoleMenu::menus($item->id);
        $item->permissions = AdminRolePermission::permissions($item->id);
        $item->users       = AdminRoleUser::users($item->id);
        return $item;
    }

    public function user()
    {
        return $this->belongsToMany(AdminUser::class, 'admin_role_user', 'role_id', 'user_id');
    }

    public function roleUser()
    {
        return $this->hasMany(AdminRoleUser::class, 'role_id', 'id');
    }

    public function menu()
    {
        return $this->belongsToMany(AdminMenu::class, 'admin_user_menu', 'role_id', 'menu_id');
    }

    public function roleMenu()
    {
        return $this->hasMany(AdminRoleMenu::class, 'role_id', 'id');
    }

    public function permission()
    {
        return $this->belongsToMany(AdminPermission::class, 'admin_role_permission', 'role_id', 'permission_id');
    }

    public function rolePermission()
    {
        return $this->hasMany(AdminRolePermission::class, 'role_id', 'id');
    }


}