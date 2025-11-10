<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataKonflikSosial extends Model
{
    use HasFactory;

    protected $table = 'data_konfliksosial';
    protected $primaryKey = 'no_kk';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $fields = ['no_kk'];
        for ($i = 1; $i <= 32; $i++) {
            $fields[] = "konfliksosial_$i";
        }

        $this->fillable = $fields;
    }

    public function keluarga()
    {
        return $this->belongsTo(DataKeluarga::class, 'no_kk', 'no_kk');
    }
}
