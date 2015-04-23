<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model {

    protected $table = 'courses';
    protected $fillable = ['department_id','title','description','code','unitval'];

    public function department(){
        return $this->belongsTo('Department');
    }

    public function sessions(){
        return $this->hasMany('Session');
    }
}
