<?php
declare (strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Db;
use Hyperf\Database\Model\SoftDeletes;

/**
 * @property int $id
 * @property int $status
 * @property string $phone
 * @property string $invite_code
 * @property int $diamond_count
 * @property string $extra
 * @property int $grade
 * @property int $challenge_number
 * @property int $fighter_number
 * @property int $cash_out_number
 * @property string $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class User extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';
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
    protected $casts = ['id' => 'int', 'status' => 'integer', 'diamond_count' => 'integer', 'grade' => 'integer', 'challenge_number' => 'integer', 'fighter_number' => 'integer', 'cash_out_number' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    private static function UserQuery()
    {
        return self::query()
            ->select([
                'id',
                'status',
                'phone',
                'invite_code',
                'diamond_count',
                'extra',
                'grade',
                'challenge_number',
                'fighter_number',
                'cash_out_number',
                'created_at',
                'updated_at'
            ]);
    }

    public static function getUserOne($id)
    {
        return self::UserQuery()
            ->where('id', $id)
            ->get()
            ->each(function ($item, $key) {
                return self::eachFunction($item, $key);
            })
            ->first();
    }

    public static function getUserList($param)
    {
        $where = [];
        if ($param['start_time']) {
            $where[] = ['created_at', '>=', $param['start_time']];
        }
        if ($param['end_time']) {
            $where[] = ['created_at', '<=', $param['end_time']];
        }
        if ($param['user_status'] >= 0 && $param['user_status'] <= 2) {
            $where[] = ['status', '=', $param['user_status']];
        }
        $user_query = self::UserQuery()
            ->where($where);
        if ($param['keywords']) {
            $user_query = $user_query->where(function ($query) use ($param) {
                $query->where('phone', '=', $param['keywords'])
                    ->orWhere('id', '=', $param['keywords']);
            });
        }
        return [
            'count'     => $user_query->count('id'),
            'data_list' => $user_query
                ->forPage($param['page'], $param['limit'])
                ->orderBy('id', 'desc')
                ->get()
                ->each(function ($item, $key) {
                    return self::eachFunction($item, $key);
                })
        ];
    }

    private static function eachFunction($item, $key)
    {
        $item->extra               = json_decode($item->extra);
        $item->sum_vnd_count       = Db::table('user_cash as uc')->leftJoin('cash as c', 'c.id', '=', 'uc.cash_id')->where(['uc.user_id' => $item->id, 'uc.status' => 2])->sum('c.diamond_count');
        $item->sum_watch_adv_count = Db::table('user_advlog')->where('user_id', $item->id)->count();
        //禁止登录前台未控制
        return $item;
    }
}