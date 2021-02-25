<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{
    protected $table = 'new_contacts';

    protected $fillable = [
        'first_phone',
        'second_phone',
        'address_kz',
        'address_eu',
        'first_email',
        'second_email',
        'lang'
    ];
}
