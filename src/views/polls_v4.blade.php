@extends(config('larapolls.master_file_extend'))

@section('css')
@if(!config('larapolls.fontawesome_v5'))
<script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js" integrity="sha384-SlE991lGASHoBfWbelyBPLsUlwY1GwNDJo3jSJO04KZ33K2bwfV9YBauFfnzvynJ" crossorigin="anonymous"></script>
@endif
<script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
@endsection

@section('content')
<div class="container">
	<a href="{{ route('larapolls.create') }}" class="btn btn-primary">{{ __('larapolls::larapolls.action_create_poll') }}</a>
  <a href="{{ route('larapolls.home') }}" class="btn btn-dark">{{ __('larapolls::larapolls.action_show_all_polls') }}</a>
  <button class="btn btn-secondary" data-toggle="collapse" data-target="#search">{{ __('larapolls::larapolls.action_search') }}</button>
  <div id="search" class="collapse">
    <br>
    <form class="form-inline">
      <label class="mb-2 mr-sm-2"for="searchType">{{ __('larapolls::larapolls.text_searchAttribute') }}</label>
      <select class="form-control mb-2 mr-sm-2" id="searchType" name="searchType">
        <option>{{ __('larapolls::larapolls.text_topic') }}</option>
        <option>{{ __('larapolls::larapolls.text_submitter') }}</option>
				<option>{{ __('larapolls::larapolls.text_category') }}</option>
      </select>
      <input type="text" class="form-control mb-2 mr-sm-2" id="searchInput" name="searchInput">
      <button class="btn btn-info mb-2 mr-sm-2">{{ __('larapolls::larapolls.action_search') }}</button>
    </form>
  </div>
	<hr>
  {!!Hannoma\Larapolls\PollDrawer::draw(1)!!}
  <!-- All Polls -->
  @if (count($polls) > 0)
		<h2>{{ __('larapolls::larapolls.text_currentPolls') }}</h2>
  	@foreach ($polls as $poll)
			@if($poll->category == $category || $category == null || $poll->sticky)
				@if(((strtotime($poll->finishes_at) + (1 * 24 * 60 * 60)) > time()) || $poll->finishes_at == null)
					@if($poll->allowed)
						<!-- Permissions -->
						@if(!in_array($poll->category, config('larapolls.protectedCategories')))
							@include('larapolls::poll_partial_v4', ['poll' => $poll])
						@else
							@if(!Auth::guest())
								@if(Auth::user()->can(config('larapolls.permissions.prefix') . config('larapolls.permissions.showPollWithCategory') . $poll->category) || Auth::user()->can(config('larapolls.permissions.prefix') . config('larapolls.permissions.showAllCategories')))
									@include('larapolls::poll_partial_v4', ['poll' => $poll])
								@endif
							@endif
						@endif
						<!-- end Permissions -->
					@else
						@if(!Auth::guest())
							@if($poll->created_by == Auth::user()->id || Auth::user()->can(config('larapolls.permissions.prefix') . config('larapolls.permissions.allowPoll')))
								@include('larapolls::poll_partial_v4', ['poll' => $poll])
							@endif
						@endif
					@endif
				@endif
			@endif
  	@endforeach
		<h2>{{ __('larapolls::larapolls.text_expiredPolls') }}</h2>
		@foreach ($polls as $poll)
			@if($poll->category == $category || $category == null)
				@if(((strtotime($poll->finishes_at) + (1 * 24 * 60 * 60)) < time()) && $poll->finishes_at != null)
					@if($poll->allowed)
						<!-- Permissions -->
						@if(!in_array($poll->category, config('larapolls.protectedCategories')))
							@include('larapolls::poll_partial_v4', ['poll' => $poll])
						@else
							@if(!Auth::guest())
								@if(Auth::user()->can(config('larapolls.permissions.prefix') . config('larapolls.permissions.showPollWithCategory') . $poll->category) || Auth::user()->can(config('larapolls.permissions.prefix') . config('larapolls.permissions.showAllCategories')))
									@include('larapolls::poll_partial_v4', ['poll' => $poll])
								@endif
							@endif
						@endif
						<!-- end Permissions -->
					@endif
				@endif
			@endif
		@endforeach
  @else
    <div class="card bg-warning">
      <div class="card-header"><h3>{{ __('larapolls::larapolls.information_noPollsFound') }}</h3></div>
      <div class="card-body">
        <p>{!! __('larapolls::larapolls.message_noPollsFound') !!}</p>
      </div>
    </div>
  @endif
</div>
<!-- ALLOW MODAL -->
<div class="modal" tabindex="-1" role="dialog" id="allowModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{__('larapolls::larapolls.action_allowPoll')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>{{__('larapolls::larapolls.message_allowPoll')}}</p>
      </div>
      <div class="modal-footer">
				<form action="{{route('larapolls.allow')}}" method="post">
					<input type="hidden" name="allowpollid" id="allowpollid" value="">
					{{ csrf_field() }}
					<button type="submit" class="btn btn-primary">{{__('larapolls::larapolls.action_allowPoll')}}</button>
				</form>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('larapolls::larapolls.action_close')}}</button>
      </div>
    </div>
  </div>
</div>
<!-- DELETE MODAL -->
<div class="modal" tabindex="-1" role="dialog" id="deleteModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{__('larapolls::larapolls.action_deletePoll')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>{{__('larapolls::larapolls.message_deletePoll')}}</p>
      </div>
      <div class="modal-footer">
				<form action="{{route('larapolls.delete')}}" method="post">
					<input type="hidden" name="deletepollid" id="deletepollid" value="">
					{{ csrf_field() }}
					<button type="submit" class="btn btn-primary">{{__('larapolls::larapolls.action_deletePoll')}}</button>
				</form>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('larapolls::larapolls.action_close')}}</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$('#deleteModal').on('show.bs.modal', function (event) {
	var button = $(event.relatedTarget) // Button that triggered the modal
	var id = button.data('pollid') // Extract info from data-* attributes
	var modal = $(this)
	$('#deletepollid').val(id)
	})
	$('#allowModal').on('show.bs.modal', function (event) {
	var button = $(event.relatedTarget) // Button that triggered the modal
	var id = button.data('pollid') // Extract info from data-* attributes
	var modal = $(this)
	console.log(id);
	$('#allowpollid').val(id)
	})
});
</script>
@endsection
