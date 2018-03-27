@extends(config('larapolls.master_file_extend'))

@section('content')
<div class="container">
	<a href="{{ route('larapolls.create') }}" class="btn btn-primary">{{ __('larapolls::larapolls.action_create_poll') }}</a>
  <a href="{{ route('larapolls.home') }}" class="btn btn-primary">{{ __('larapolls::larapolls.action_show_all_polls') }}</a>
  <button class="btn btn-primary" data-toggle="collapse" data-target="#search">{{ __('larapolls::larapolls.action_search') }}</button>
  <div id="search" class="collapse">
    <br>
    <form class="form-inline">
      <div class="form-group">
        <label for="searchType">{{ __('larapolls::larapolls.text_searchAttribute') }} </label>
        <select class="form-control" id="searchType" name="searchType">
          <option>{{ __('larapolls::larapolls.text_topic') }}</option>
          <option>{{ __('larapolls::larapolls.text_submitter') }}</option>
					<option>{{ __('larapolls::larapolls.text_category') }}</option>
        </select>
      </div>
      <div class="form-group">
        <label for="searchInput"></label>
        <input type="text" class="form-control" id="searchInput" name="searchInput">
      </div>
      <button class="btn btn-info">{{ __('larapolls::larapolls.action_search') }}</button>
    </form>
  </div>
  <!-- All Polls -->
  @if (count($polls) > 0)
		<h2>{{ __('larapolls::larapolls.text_currentPolls') }}</h2>
  	@foreach ($polls as $poll)
		@include('larapolls::poll_partial_v3', ['poll' => $poll]);
			@if($poll->category == $category)
				@if(((strtotime($poll->finishes_at) + (1 * 24 * 60 * 60)) > time()) || $poll->finishes_at == null)
				@endif
			@endif
  	@endforeach
		<h2>{{ __('larapolls::larapolls.text_expiredPolls') }}</h2>
		@foreach ($polls as $poll)
			@if($poll->category == $category)
				@if(((strtotime($poll->finishes_at) + (1 * 24 * 60 * 60)) < time()) && $poll->finishes_at != null)
					@include('larapolls::poll_partial_v3', ['poll' => $poll]);
				@endif
			@endif
		@endforeach
  @else
    <div class="panel panel-warning">
      <div class="panel-heading">{{ __('larapolls::larapolls.information_noPollsFound') }}</div>
      <div class="panel-body">
        <p>{{ __('larapolls::larapolls.message_noPollsFound') }}</p>
      </div>
    </div>
  @endif
</div>
@endsection
