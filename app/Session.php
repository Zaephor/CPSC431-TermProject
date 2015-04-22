<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model {

    protected $table = 'sessions';
    protected $fillable = ['course_id','professor_id'];

    public function course(){
        return $this->belongsTo('Course');
    }
    public function professor(){
        return $this->belongsTo('User','professor_id','id');
    }
}
