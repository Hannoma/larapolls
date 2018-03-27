<?php
namespace Hannoma\Larapolls\Models;
use Illuminate\Database\Eloquent\Model;

class Poll_Option extends Model
{
    protected $table = 'larapolls_poll_options';
    public $timestamps = false;

    public function hasRated($user_id){
      $poll_rating = Poll_Vote::where('user_id', $user_id)->where('poll_option_id', $this->id)->first();
      if($poll_rating){
        if($poll_rating->pro == 1){
          return 1;
        } else {
          return -1;
        }
      }
      return 0;
    }

    public function hasRatedBootstrapCode($user_id, $pro = true){
      if(config('larapolls.bootstrap_v4')){
        switch ($this->hasRated($user_id)) {
          case 1:
          if($pro){return 'btn btn-success';}else{return 'btn btn-outline-´danger';}
          break;
          case -1:
          if(!$pro){return 'btn btn-danger';}else{return 'btn btn-outline-success';}
          break;
          default:
          if($pro){return 'btn btn-outline-success';}else{return 'btn btn-outline-danger';}
          break;
        }
      } else {
        switch ($this->hasRated($user_id)) {
          case 1:
          if($pro){return 'btn btn-success';}else{return 'btn btn-default';}
          break;
          case -1:
          if(!$pro){return 'btn btn-danger';}else{return 'btn btn-default';}
          break;
          default:
          return 'btn btn-default';
          break;
        }
      }
    }

    public function rateOption($user_id, $pro = true){
      switch ($this->hasRated($user_id)) {
        case 1:
          if(!$pro){
            $this->removeRate($user_id, true);
            $this->addRate($user_id, false);
          } else {
            $this->removeRate($user_id, true);
          }
          break;
        case -1:
          if($pro){
            $this->removeRate($user_id, false);
            $this->addRate($user_id, true);
          } else {
            $this->removeRate($user_id, false);
          }
          break;
        default:
          $this->addRate($user_id, $pro);
          break;
      }
    }

    public function addRate($user_id, $pro = true){
      $rating = new Poll_Vote();
      $rating->poll_option_id = $this->id;
      $rating->user_id = $user_id;
      if($pro){
        $rating->pro = 1;
      } else {
        $rating->pro = 0;
      }
      $rating->save();
      $this->addInsRate(1);
    }

    public function removeRate($user_id, $pro = true){
      $rating = Poll_Vote::where('user_id', $user_id)->where('poll_option_id', $this->id)->where('pro',(int)$pro)->first();
      if($rating){
        $rating->delete();
        $this->addInsRate(-1);
      }
    }

    public function addInsRate($add){
      $poll = Poll::where('id', $this->poll_id)->first();
      $new = $poll->votes + $add;
      $poll->votes = $new;
      $poll->save();
    }
    public function getPercent($pro){
      $poll = Poll::where('id', $this->poll_id)->first();
      if($poll->votes == 0){
        return 0;
      } else {
        if($pro){
          $pro_ = count(Poll_Vote::where('poll_option_id', $this->id)->where('pro',1)->get());
          return $pro_ / $poll->votes;
        } else {
          $con_ = count(Poll_Vote::where('poll_option_id', $this->id)->where('pro',0)->get());
          return $con_ / $poll->votes;
        }
      }
    }

    public function getProgressbarColor(){
      $pro = count(Poll_Vote::where('poll_option_id', $this->id)->where('pro',1)->get());
      $con = count(Poll_Vote::where('poll_option_id', $this->id)->where('pro',0)->get());
      if($pro - $con < 0){
        return 'danger';
      } else {
        return 'primary';
      }
    }
}
