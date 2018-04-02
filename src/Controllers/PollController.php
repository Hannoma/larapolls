<?php

namespace Hannoma\Larapolls\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Hannoma\Larapolls\Models\Poll;
use Hannoma\Larapolls\Models\Poll_Option;
use Illuminate\Support\Facades\Auth;
use Hannoma\Larapolls\Requests\CreatePollRequest;
use Spatie\Permission\Models\Permission;
use Hannoma\Larapolls\Helpers\StandardPermissionsHelper;
use App\User;
use App;

class PollController extends Controller
{
    public function home(Request $request, $category = null){
      StandardPermissionsHelper::giveStandardPermission();
      if(config('larapolls.bootstrap_v4')){
        return view('larapolls::polls_v4', ['polls' => $this->getPolls($request), 'category' => $category]);
      } else {
        return view('larapolls::polls_v3', ['polls' => $this->getPolls($request), 'category' => $category]);
      }
	  }
    public function vote(Request $request){
      $multiple = true;
      $pro = true;
      $poll_option_id = 0;
      //Get Data
      if($request->input('voteButton')){
        $multiple = false;
        $poll_option_id = $request->input('voteButton');
      } else {
        if($request->input('voteMultipleConButton')){
          $pro = false;
          $poll_option_id = $request->input('voteMultipleConButton');
        } else {
          if($request->input('voteMultipleProButton')){
            $poll_option_id = $request->input('voteMultipleProButton');
          } else {
            var_dump("ERROR!");
            die();
          }
        }
      }

      $poll_option = Poll_Option::where('id', $poll_option_id)->first();
      if($poll_option){
        $poll = Poll::where('id', $poll_option->poll_id)->first();
        if($poll){
          if($poll->allowed == 1){
            if(((strtotime($poll->finishes_at) + (1 * 24 * 60 * 60)) < time()) && $poll->finishes_at != null){
              $request->session()->flash('status', 'Diese Umfrage ist beendet!');
              return redirect(route('larapolls.home'));
            } else {
              $poll->vote(Auth::user()->id, $poll_option_id, $pro);
              return redirect()->back();
            }
          } else {
            $request->session()->flash('status', 'Diese Umfrage wurde noch nicht abgesegnet!');
            return redirect(route('larapolls.home'));
          }
        }
      }
	  }
    public function allowPoll(Request $request){
      $poll = Poll::where('id', $request->input('allowpollid'))->first();
      if($poll){
        if(Auth::user()->can(config('larapolls.permissions.prefix') . config('larapolls.permissions.allowPoll')) || Auth::user()->can(config('larapolls.permissions.prefix') . config('larapolls.permissions.allowPollWithCategory') . $poll->category)){
          $poll->allowed = true;
          $poll->save();
        }
      }
      return redirect()->back();
    }
    public function deletePoll(Request $request){
      $poll = Poll::where('id', $request->input('deletepollid'))->first();
      if($poll){
        if((config('larapolls.delete_own_poll') && Auth::user()->id == $poll->created_by) || Auth::user()->can(config('larapolls.permissions.prefix') . config('larapolls.permissions.deletePoll')) || Auth::user()->can(config('larapolls.permissions.prefix') . config('larapolls.permissions.deletePollWithCategory') . $poll->category)){
          $poll->delete();
        }
      }
      return redirect()->back();
    }

    public function showCreatePoll($category = null){
      if($category == null){
        $categories = array();
        $categoryPermissions = Permission::where('name', 'LIKE', config('larapolls.permissions.prefix') . config('larapolls.permissions.createPollWithCategory') . '%')->get();
        foreach ($categoryPermissions as $p) {
          if(Auth::user()->hasPermissionTo($p->id)){
            array_push($categories, str_replace(config('larapolls.permissions.prefix') . config('larapolls.permissions.createPollWithCategory'), '', $p->name));
          }
        }
        if(config('larapolls.bootstrap_v4')){
          return view('larapolls::poll_create_v4', ['categories' => $categories]);
        } else {
          return view('larapolls::poll_create_v3', ['categories' => $categories]);
        }
      } else {
        if(Auth::user()->can(config('larapolls.permissions.prefix') . config('larapolls.permissions.createPollWithCategory') . $category)){
          if(config('larapolls.bootstrap_v4')){
            return view('larapolls::poll_create_v4', ['categories' => [$category]]);
          } else {
            return view('larapolls::poll_create_v3', ['categories' => [$category]]);
          }
        }
      }
      return redirect(route('larapolls.home'));
    }
    public function postCreatePoll(CreatePollRequest $request){
      //CHECK OPTION COUNT
      $option_count = $request->input('option_count');
      if($option_count < 2 || $option_count > config('larapolls.maxOptionCount')){
        $request->session()->flash('status', __('larapolls::larapolls.message_optionCount', ['min' => 2, 'max' => config('larapolls.maxOptionCount')]));
        return redirect(route('larapolls.create'));
      }
      //CHECK IF EMPTY
      for($i = 1; $i<= $option_count; $i++){
        if($request->input('option'. $i) == null){
          $request->session()->flash('status', __('larapolls::larapolls.message_optionNull'));
          return redirect(route('larapolls.create'));
        }
      }
      //CHECK UNALLOWED POLL COUNT
      $poll_owned = Poll::where('created_by', Auth::user()->id)->get();
      $notAllowedPollCount = 0;
      foreach ($poll_owned as $poll) {
        if($poll->allowed == 0){
          $notAllowedPollCount++;
        }
      }
      if($notAllowedPollCount >= config('larapolls.maxUnallowedPolls')){
        $request->session()->flash('status', __('larapolls::larapolls.message_unallowedPollCount', config('larapolls.maxUnallowedPolls')));
        return redirect(route('larapolls.create'));
      }
      //Retrieve Values
      $multiple = $request->input('multiple') == null ? false : true;
      $contra = $request->input('contra') == null ? false : true;
      $sticky = $request->input('sticky') == null ? false : true;
      $allow = $request->input('allowed') == null ? false : true;
      $category = $request->input('category');
      //Check for permissions!
      if(!Auth::user()->can(config('larapolls.permissions.prefix') . config('larapolls.permissions.createPollSticky'))) $sticky = false;
      if(!Auth::user()->can(config('larapolls.permissions.prefix') . config('larapolls.permissions.createPollContra'))) $contra = false;
      if(!Auth::user()->can(config('larapolls.permissions.prefix') . config('larapolls.permissions.createPollMultiple'))) $multiple = false;
      if(!Auth::user()->can(config('larapolls.permissions.prefix') . config('larapolls.permissions.allowPoll'))){
        if(!Auth::user()->can(config('larapolls.permissions.prefix') . config('larapolls.permissions.allowPollWithCategory') . $category)){
          $allowed = false;
        }
      }
      if(!Auth::user()->can(config('larapolls.permissions.prefix') . config('larapolls.permissions.createNewCategory'))){
        if(!Auth::user()->can(config('larapolls.permissions.prefix') . config('larapolls.permissions.createPollWithCategory') . $category)){
          $request->session()->flash('status', __('larapolls::larapolls.message_notAllowed'));
          return redirect(route('larapolls.create'));
        }
      }

      //Create Poll
      $poll = new Poll;
      $poll->created_by = Auth::user()->id;
      $poll->allowed = $allow;
      $poll->category = $category;
      $poll->topic = $request->input('topic');
      $poll->info = $request->input('info');
      $poll->sticky = $sticky;
      $poll->contra = $contra;
      $poll->multiple = $multiple;
      $poll->finishes_at = $request->input('finishes_at');
      $poll->scale = 1;
      $poll->save();

      //ADD statements
      for($i = 1; $i<= $option_count; $i++){
        $option = new Poll_Option;
        $option->poll_id = $poll->id;
        $option->option = $request->input('option'.$i);
        $option->save();
      }
      return redirect(route('larapolls.home'));
    }

    private function getPolls($request){
      $type = $request->input('searchType');
      $search = $request->input('searchInput');
      $polls = Poll::orderBy('sticky', 'DESC')->get();
      switch ($type) {
        case __('larapolls::larapolls.text_category'):
          $polls = Poll::where('category', $search)->orderBy('sticky', 'DESC')->get();
          break;
        case __('larapolls::larapolls.text_submitter'):
          $user = User::where(config('larapolls.username_key'), 'LIKE', '%'.$search.'%')->orderBy('sticky', 'DESC')->first();
          if($user){
            $polls = Poll::where('created_by', $user->id)->orderBy('sticky', 'DESC')->get();
          }
          break;
        case __('larapolls::larapolls.text_topic'):
          $polls = Poll::where('topic', 'LIKE', '%'.$search.'%')->orderBy('sticky', 'DESC')->get();
          break;
        default:
          break;
      }
      return $polls;
    }
}
