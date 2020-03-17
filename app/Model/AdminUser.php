<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\SoftDeletes;
use Hyperf\DbConnection\Db;

/**
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property string $avatar
 * @property string $extra
 * @property int $status
 * @property string $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class AdminUser extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'username', 'password', 'avatar', 'extra'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'int', 'status' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function role()
    {
        return $this->belongsToMany(AdminRole::class, 'admin_user_role', 'user_id', 'role_id');
    }

    public function roleUser()
    {
        return $this->hasMany(AdminRoleUser::class, 'user_id', 'id');
    }

    public function permission()
    {
        return $this->belongsToMany(AdminPermission::class, 'admin_user_permission', 'user_id', 'permission_id');
    }

    public function userPermission()
    {
        return $this->hasMany(AdminUserPermission::class, 'user_id', 'id');
    }

    public function setExtraAttribute($value)
    {
        $this->attributes['extra'] = json_encode($value);
    }

    public function getExtraAttribute($value)
    {
        return json_decode($value);
    }

    public static function getAdminUserList($param)
    {
        $where = [];
        if ($param['start_time']) {
            $where[] = ['u.created_at', '>=', $param['start_time']];
        }
        if ($param['end_time']) {
            $where[] = ['u.created_at', '<=', $param['end_time']];
        }
        if ($param['role_id'] > 0) {
            $where[] = ['r_u.role_id', '=', $param['role_id']];
        }
        $AdminUserQuery = self::AdminUserQuery()->where($where);
        if ($param['keywords']) {
            $AdminUserQuery = $AdminUserQuery->where(function ($query) use ($param) {
                $query->where('u.username', 'like', "%{$param['keywords']}%")
                    ->orWhere('u.name', 'like', "%{$param['keywords']}%");
            });
        }
        return [
            'count'     => $AdminUserQuery->count('id'),
            'data_list' => $AdminUserQuery
                ->forPage($param['page'], $param['limit'])
                ->orderBy('u.id', 'desc')
                ->get()
                ->each(function ($item, $key) {
                    return self::eachFunction($item, $key);
                })];
    }

    public static function getAdminUserOne($id)
    {
        return self::AdminUserQuery()->where('u.id', $id)->limit(1)->get()->each(function ($item, $key) {
            return self::eachFunction($item, $key);
        })->first();
    }

    private static function AdminUserQuery()
    {
        return Db::table('admin_user as u')
            ->select([
                'u.id',
                'u.username',
                'u.name',
                'u.avatar',
                'u.extra',
                'u.status',
                'u.created_at',
                'u.updated_at'
            ])
            ->leftJoin('admin_role_user as r_u', 'r_u.user_id', '=', 'u.id')
            ->whereNull('u.deleted_at')
            ->where([
                ['u.id', '>', 1]
            ]);
    }

    private static function eachFunction($item, $key)
    {
        $item->extra         = json_decode($item->extra);
        $item->last_login_ip = AdminOperationLog::query(true)->where('user_id', $item->id)->value('ip');
        $item->roles         = AdminRoleUser::roles($item->id);
        $item->permissions   = AdminUserPermission::permission($item->id);
        return $item;
    }
}