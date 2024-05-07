<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingModel extends Model
{
    use HasFactory;

    protected $table = 'settings';

    protected $fillable = [
        'NAME',
        'VALUE',
    ];

    public static function edit($data)
    {
        self::where('id', 1)->update(['VALUE' => $data['dollars_conversion']]);
        return self::where('id', 2)->update(['VALUE' => $data['email_recipient']]);
    }

    public static function get()
    {
        return self::all();
    }
    
}
