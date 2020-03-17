<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $http_method
 * @property string $http_path
 * @property string $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class AdminPermission extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_permission';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'http_method',
        'http_path',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'         => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsToMany(AdminUser::class, 'admin_user_permission', 'permission_id', 'user_id');
    }

    public function userPermission()
    {
        return $this->hasMany(AdminUserPermission::class, 'permission_id', 'id');
    }

    public function role()
    {
        return $this->belongsToMany(AdminPermission::class, 'admin_role_permission', 'permission_id', 'role_id');
    }

    public function rolePermission()
    {
        return $this->hasMany(AdminRolePermission::class, 'permission_id', 'id');
    }

    public static function eachFunction($item, $key)
    {
        $item->roles = AdminRolePermission::roles($item->id);
        $item->users = AdminUserPermission::users($item->id);
        return $item;
    }

    public static function getAdminPermissionOne($id)
    {
        return self::AdminPermissionQuery()
            ->where('id', $id)
            ->limit(1)
            ->get()
            ->each(function ($item, $key) {
                return self::eachFunction($item, $key);
            })
            ->first();
    }

    public static function getAdminPermissionList($param)
    {
        $AdminPermissionQuery = self::AdminPermissionQuery();
        if ($param['keywords']) {
            $AdminPermissionQuery = $AdminPermissionQuery->where(function ($query) use ($param) {
                $query->where('name', 'like', "%{$param['keywords']}%")
                    ->orWhere('description', 'like', "%{$param['keywords']}%");
            });
        }
        return [
            'count'     => $AdminPermissionQuery->count('id'),
            'data_list' => $AdminPermissionQuery
                ->forPage($param['page'], $param['limit'])
                ->orderBy('id', 'desc')
                ->get()
                ->each(function ($item, $key) {
                    return self::eachFunction($item, $key);
                })
        ];
    }

    public static function AdminPermissionQuery()
    {
        return self::query()
            ->select([
                'id',
                'name',
                'description',
                'http_method',
                'http_path',
                'created_at',
                'updated_at'
            ]);
    }
}