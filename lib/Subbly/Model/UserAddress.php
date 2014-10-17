<?php

namespace Subbly\Model;

class UserAddress extends Model
{
    use Concerns\AddressTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_addresses';

    public function user()
    {
        return $this->belongsTo('Subbly\\Model\\User');
    }
}
