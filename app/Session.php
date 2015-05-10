<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model {

    protected $table = 'sessions';
    protected $fillable = ['course_id','professor_id','begins_on','ends_on','room'];

    public function course(){
        return $this->belongsTo('App\Course');
    }
    public function professor(){
        return $this->belongsTo('App\User','professor_id','id');
    }
    public function assignments(){
        return $this->hasMany('App\Assignment');
    }
    public function students(){
        return $this->belongsToMany('App\User')->withTimestamps();
    }
}
