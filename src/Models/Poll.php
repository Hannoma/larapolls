<?php
namespace Hannoma\Larapolls\Models;
use Illuminate\Database\Eloquent\Model;
use App\User;

class Poll extends Model
{
    protected $table = 'larapolls_polls';
    public $timestamps = true;

    public function getOptions(){
      return Poll_Option::where('poll_id', $this->id)->get();
    }
    public function getCreator(){
      return User::where('id', $this->created_by)->first();
    }

    public function vote($user_id, $poll_option_id, $pro){
      if($this->multiple){
        $option = Poll_Option::where('id', $poll_option_id)->first();
        if($option) $option->rateOption($user_id, $pro);
      } else {
        $rated_option = null;
        foreach ($this->getOptions() as $option) {
          if($option->hasRated($user_id) == 1){
            $rated_option = $option;
          }
          if($option->id == $poll_option_id && $option->hasRated($user_id) == 0){
            $option->addRate($user_id, true);
          }
        }
        if($rated_option){
          $rated_option->removeRate($user_id, true);
        }
      }
    }
}
