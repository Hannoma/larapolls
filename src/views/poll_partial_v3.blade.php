<div class="card" id="poll{{$poll->id}}">
  <div class="card-header {{$poll->sticky ? 'bg-warning' : 'bg-secondary text-white'}}">
    @if($poll->allowed == 0)
    <div class="alert alert-danger" role="alert">{{__('larapolls::larapolls.information_pollNotAllowed')}}
      @if(!Auth::guest())
        @if(Auth::user()->can(config('larapolls.permissions.prefix') . config('larapolls.permissions.allowPoll')))
        <button type="button" class="btn btn-warning" data-target="#allowModal" data-toggle="modal" data-pollid="{{$poll->id}}">{{__('larapolls::larapolls.action_allowPoll')}}</button>
        @endif
      @endif
    </div>
    @endif
    <h3>{{$poll->topic}} <small> - {{$poll->votes}} {{ trans_choice('larapolls::larapolls.text_votes', $poll->votes) }}</small> <a href="{{route('larapolls.category', ['category' => $poll->category])}}"><span class="badge badge-dark"><i class="fas fa-tag"></i> {{$poll->category}}</span></a>
      @if(!Auth::guest())
      @if(Auth::user()->can(config('larapolls.permissions.prefix') . config('larapolls.permissions.deletePoll')))
      <button type="button" class="btn btn-warning" data-target="#deleteModal" data-toggle="modal" data-pollid="{{$poll->id}}">{{__('larapolls::larapolls.action_deletePoll')}}</button>
      @endif
      @endif
    </h3>
  </div>
  <div class="card-body">
    <p class="lead">{{ $poll->info }}</p>
    <blockquote class="blockquote">
    @foreach ($poll->getOptions() as $option)
    <div class="row align-items-center">
      <div class="col"><li>{{$option->option}}</li></div>
      <div class="col-6">
        <div class="progress">
          <div class="progress-bar progress-bar-striped bg-success" role="progressbar" aria-valuenow="{{$option->getPercent(true) * 100}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$option->getPercent(true) * 100 * $poll->scale}}%;">{{ number_format($option->getPercent(true) * 100, 2, ',', '.')}}%</div>
          <div class="progress-bar progress-bar-striped bg-danger" role="progressbar" aria-valuenow="{{$option->getPercent(false) * 100}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$option->getPercent(false) * 100 * $poll->scale}}%;">{{ number_format($option->getPercent(false) * 100, 2, ',', '.')}}%</div>
        </div>
      </div>
      <div class="col">
        @guest
          @if((((strtotime($poll->finishes_at) + (1 * 24 * 60 * 60)) > time()) || $poll->finishes_at == null) && $poll->allowed)
            @if($poll->multiple)
            <a class="btn btn-outline-success" href="{{route(config('larapolls.routes.login'))}}" role="button"><i class="fas fa-plus"></i></a>
              @if($poll->contra)
                <a class="btn btn-outline-danger" href="{{route(config('larapolls.routes.login'))}}" role="button"><i class="fas fa-minus"></i></a>
              @endif
            @else
            <a class="btn btn-outline-success" href="{{route(config('larapolls.routes.login'))}}" role="button"><i class="fas fa-check"></i></a>
            @endif
          @else
            @if($poll->multiple)
              <button class="{{$option->hasRatedBootstrapCode(Auth::user()->id,true)}}" disabled><i class="fas fa-plus"></i></button>
              @if($poll->contra)
                <button class="{{$option->hasRatedBootstrapCode(Auth::user()->id,false)}}" disabled><i class="fas fa-minus"></i></button>
              @endif
            @else
              <button class="{{$option->hasRatedBootstrapCode(Auth::user()->id,true)}}" disabled><i class="fas fa-check"></i></button>
            @endif
          @endif
        @else
          @if((((strtotime($poll->finishes_at) + (1 * 24 * 60 * 60)) > time()) || $poll->finishes_at == null) && $poll->allowed)
            <form method="POST" action="{{route('larapolls.vote')}}">
              {{ csrf_field() }}
              @if($poll->multiple)
                <button type="submit" class="{{$option->hasRatedBootstrapCode(Auth::user()->id,true)}}" id="voteMultipleProButton" name="voteMultipleProButton" value="{{$option->id}}"><i class="fas fa-plus"></i></button>
                @if($poll->contra)
                  <button type="submit" class="{{$option->hasRatedBootstrapCode(Auth::user()->id,false)}}" id="voteMultipleConButton" name="voteMultipleConButton" value="{{$option->id}}"><i class="fas fa-minus"></i></button>
                @endif
              @else
                <button type="submit" class="{{$option->hasRatedBootstrapCode(Auth::user()->id,true)}}" id="voteButton" name="voteButton" value="{{$option->id}}"><i class="fas fa-check"></i></button>
              @endif
            </form>
          @else
            @if($poll->multiple)
              <button class="{{$option->hasRatedBootstrapCode(Auth::user()->id,true)}}" disabled><i class="fas fa-plus"></i></button>
              @if($poll->contra)
                <button class="{{$option->hasRatedBootstrapCode(Auth::user()->id,false)}}" disabled><i class="fas fa-minus"></i></button>
              @endif
            @else
              <button class="{{$option->hasRatedBootstrapCode(Auth::user()->id,true)}}" disabled><i class="fas fa-check"></i></button>
            @endif
          @endif
        @endguest
      </div>
    </div>
    <br>
    @endforeach
      <footer class="blockquote-footer">
        {{ __('larapolls::larapolls.text_poll_created_by') }}
        <a href="{{route(config('larapolls.routes.profile'), [config('larapolls.routes.profile_argument') => $poll->getCreator()->{config('larapolls.routes.profile_arg_value')}])}}">
          {{$poll->getCreator()->{config('larapolls.username_key')} }}
        </a>
        {{ __('larapolls::larapolls.text_poll_created_at', ['date' => date(config('larapolls.date_format'), strtotime($poll->created_at))]) }}
        @if($poll->finishes_at != 0)
        <strong>{{ __('larapolls::larapolls.text_poll_finishes', ['date' => date(config('larapolls.date_format'), strtotime($poll->finishes_at))]) }}</strong>
        @else
        @endif
       </footer>
    </blockquote>
  </div>
</div>
<br>
