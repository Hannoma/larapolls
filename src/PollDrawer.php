<?php

namespace Hannoma\Larapolls;

use Hannoma\Larapolls\Models\Poll;

class PollDrawer{

  public static function draw($poll_id){
    $poll = Poll::findOrFail($poll_id);
    StandardPermissionsHelper::giveStandardPermission();
    if(config('larapolls.bootstrap_v4')){
      return view('larapolls::poll_partial_v4', ['poll' => $poll]);
    } else {
      return view('larapolls::poll_partial_v3', ['poll' => $poll]);
    }
  }
}
