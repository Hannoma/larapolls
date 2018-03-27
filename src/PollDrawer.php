<?php

namespace Hannoma\Larapolls;

class PollDrawer{

  /**
  * Draw a Poll
  *
  * @param $poll_id
  */

  public function draw($poll_id){
    $poll = Poll::findOrFail($poll_id);
    if(config('larapolls.bootstrap_v4')){
      return view('larapolls::poll_partial_v4', ['poll' => $poll]);
    } else {
      return view('larapolls::poll_partial_v3', ['poll' => $poll]);
    }
  }
}
