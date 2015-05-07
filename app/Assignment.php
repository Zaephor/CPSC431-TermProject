<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model {

    protected $table = 'assignments';
    protected $fillable = ['session_id','student_id','assignment_code','score'];

    public function session(){
        return $this->belongsTo('App\Session');
    }

    public function students(){
        return $this->belongsTo('App\User','student_id','id');
    }
}
