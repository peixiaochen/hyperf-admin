<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Db;

/**
 * @property int $id
 * @property int $parent_user_id
 * @property int $user_id
 * @property int $is_read
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class UserInvite extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_invite';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'int', 'parent_user_id' => 'integer', 'user_id' => 'integer', 'is_read' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public static function getUserInviteList($param)
    {
        $where = [];
        if ($param['start_time']) {
            $where[] = ['u_i.created_at', '>=', $param['start_time']];
        }
        if ($param['end_time']) {
            $where[] = ['u_i.created_at', '<=', $param['end_time']];
        }
        $user_invite_query = self::UserInviteQuery()
            ->where($where);
        if ($param['inviter_keywords']) {
            $user_invite_query = $user_invite_query->where(function ($query) use ($param) {
                $query->where('us.phone', '=', $param['inviter_keywords'])
                    ->orWhere('u_i.parent_user_id', '=', $param['inviter_keywords']);
            });
        }
        if ($param['invited_keywords']) {
            $user_invite_query = $user_invite_query->where(function ($query) use ($param) {
                $query->where('u.phone', '=', $param['invited_keywords'])
                    ->orWhere('u_i.user_id', '=', $param['invited_keywords']);
            });
        }
        return [
            'count'     => $user_invite_query->count('u_i.id'),
            'data_list' => $user_invite_query
                ->forPage($param['page'], $param['limit'])
                ->orderBy('u_i.id', 'desc')
                ->get()
                ->each(function ($item, $key) {
                    return self::eachFunction($item, $key);
                })
        ];
    }


    private static function eachFunction($item, $key)
    {
        $item->extra = json_decode($item->extra);
        return $item;
    }

    private static function UserInviteQuery()
    {
        return Db::table('user_invite as u_i')
            ->select([
                'u_i.id',
                'u_i.user_id',
                'u_i.parent_user_id',
                'u_i.created_at',
                'u_i.updated_at',
                'u.phone as parent_phone',
                'us.phone',
                'us.extra',
            ])
            ->leftJoin('users as u', 'u.id', '=', 'u_i.user_id')
            ->leftJoin('users as us', 'us.id', '=', 'u_i.parent_user_id');
    }
}