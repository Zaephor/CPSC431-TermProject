<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model {

    protected $table = 'departments';
    protected $fillable = ['title','code'];

    public function courses(){
        return $this->hasMany('Course');
    }
}
