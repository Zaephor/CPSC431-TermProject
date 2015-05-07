<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model {

    protected $table = 'courses';
    protected $fillable = ['department_id','title','description','code','unitval'];

    public function department(){
        return $this->belongsTo('App\Department');
    }

    public function sessions(){
        return $this->hasMany('App\Session');
    }
}
