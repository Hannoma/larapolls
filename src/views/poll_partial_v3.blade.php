<div class="panel panel-default" id="poll{{$poll->id}}">
  <div class="panel-body">
    <div class="page-header">
      <h3>{{$poll->topic}} <small> - {{$poll->votes}} Votes</small></h3>
    </div>
    <p class="lead">{{ $poll->info }}</p>
    <blockquote>
    @foreach ($poll->getOptions() as $option)
    <div class="row">
      <div class="col-md-4"><li>{{$option->option}}</li></div>
      <div class="col-md-6">
        <div class="progress">
          <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="{{$option->getPercent(true) * 100}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$option->getPercent(true) * 100 * $poll->scale}}%;">{{ number_format($option->getPercent(true) * 100, 2, ',', '.')}}%</div>
          <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="{{$option->getPercent(false) * 100}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$option->getPercent(false) * 100 * $poll->scale}}%;">{{ number_format($option->getPercent(false) * 100, 2, ',', '.')}}%</div>
        </div>
      </div>
      <div class="col-md-2">
        <form method="POST" target="{{route(poll.vote, ['id' => $poll->id])}}">
          {{ csrf_field() }}
          @if($poll->multiple)
            <button type="submit" class="btn btn-{{$option->hasRatedBootstrapCode(Auth::user()->id,true)}}" id="voteMultipleProButton" name="voteMultipleProButton" value="{{$option->id}}"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
            @if($poll->contra)
            <button type="submit" class="btn btn-{{$option->hasRatedBootstrapCode(Auth::user()->id,false)}}" id="voteMultipleConButton" name="voteMultipleConButton" value="{{$option->id}}"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></button>
            @endif
          @else
            <button type="submit" class="btn btn-{{$option->hasRatedBootstrapCode(Auth::user()->id,true)}}" id="voteButton" name="voteButton" value="{{$option->id}}"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
          @endif
        </form>
      </div>
    </div>
    @endforeach
      <footer>Poll created by <a href="">{{$poll->getCreator()->username}}</a> am {!! date("d.m.Y", strtotime($poll->created_at)) !!}
        @if($poll->finishes_at != 0)
        <strong>endet am {!! date("d.m.Y", strtotime($poll->finishes_at)) !!}</strong>
        @else
        @endif
       </footer>
    </blockquote>
  </div>
</div>
