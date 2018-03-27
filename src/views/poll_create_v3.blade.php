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
  @if (session('status'))
      <div class="alert alert-danger">
          {{ session('status') }}
      </div>
  @endif
  <div class="card">
    <div class="card-header">
      <h3>{{__('larapolls::larapolls.action_create_poll')}}</h3>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('larapolls.create') }}">
          @csrf
          <div class="form-group row">
              <label for="topic" class="col-sm-2 col-form-label text-md-right">{{ __('larapolls::larapolls.text_topic') }}</label>
              <div class="col-sm-9">
                  <input id="topic" type="text" class="form-control{{ $errors->has('topic') ? ' is-invalid' : '' }}" name="topic" value="{{ old('topic') }}" required autofocus>
                  @if ($errors->has('topic'))
                      <span class="invalid-feedback">
                          <strong>{{ $errors->first('topic') }}</strong>
                      </span>
                  @endif
              </div>
          </div>
          <div class="form-group row">
              <label for="info" class="col-sm-2 col-form-label text-md-right">{{ __('larapolls::larapolls.text_info') }}</label>
              <div class="col-sm-9">
                  <textarea id="info" type="text" class="form-control{{ $errors->has('info') ? ' is-invalid' : '' }}" name="info" rows="3">{{old('info')}}</textarea>
                  @if ($errors->has('info'))
                      <span class="invalid-feedback">
                          <strong>{{ $errors->first('info') }}</strong>
                      </span>
                  @endif
              </div>
          </div>
          <div class="form-group row">
              <label for="finishes_at" class="col-sm-2 col-form-label text-md-right">{{ __('larapolls::larapolls.text_finishes_at') }}</label>
              <div class="col-sm-9">
                  <input id="finishes_at" type="date" class="form-control{{ $errors->has('finishes_at') ? ' is-invalid' : '' }}" name="finishes_at" rows="3" value="{{old('finishes_at')}}">
                  @if ($errors->has('finishes_at'))
                      <span class="invalid-feedback">
                          <strong>{{ $errors->first('finishes_at') }}</strong>
                      </span>
                  @endif
              </div>
          </div>
          <div class="form-group row">
              <label for="category" class="col-sm-2 col-form-label text-md-right">{{ __('larapolls::larapolls.text_category') }}</label>
              <div class="col-sm-9">
                  @if(Auth::user()->can(config('larapolls.permissions.prefix') . config('larapolls.permissions.createNewCategory')))
                    <input id="category" type="text" class="form-control{{ $errors->has('category') ? ' is-invalid' : '' }}" name="category" value="{{ old('category') }}" required autofocus>
                    @if ($errors->has('category'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('category') }}</strong>
                        </span>
                    @endif
                  @else
                  <select class="form-control" id="category" name="category">
                    @foreach($categories as $c)
                    <option>{{$c}}</option>
                    @endforeach
                  </select>
                  @endif
              </div>
          </div>
          <div class="form-group row">
              <label for="topic" class="col-sm-2 col-form-label text-md-right">{{ __('larapolls::larapolls.text_pollOptions') }}</label>
              <div class="col-sm-9">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" type="checkbox" id="multiple" name="multiple" {{old('multiple') == 'on' ? 'checked' : ''}}{{Auth::user()->can(config('larapolls.permissions.prefix') . config('larapolls.permissions.createPollMultiple')) ? '' : 'disabled'}}>
                  <label class="custom-control-label" for="multiple">{{ __('larapolls::larapolls.information_multiple') }}</label>
                </div>
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" type="checkbox" id="contra" name="contra" {{old('contra') == 'on' ? 'checked' : ''}}{{Auth::user()->can(config('larapolls.permissions.prefix') . config('larapolls.permissions.createPollContra')) ? '' : 'disabled'}}>
                  <label class="custom-control-label" for="contra">{{ __('larapolls::larapolls.information_contra') }}</label>
                </div>
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" type="checkbox" id="sticky" name="sticky" {{old('sticky') == 'on' ? 'checked' : ''}}{{Auth::user()->can(config('larapolls.permissions.prefix') . config('larapolls.permissions.createPollSticky')) ? '' : 'disabled'}}>
                  <label class="custom-control-label" for="sticky">{{ __('larapolls::larapolls.information_sticky') }}</label>
                </div>
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" type="checkbox" id="allowed" name="allowed" {{old('allowed') == 'on' || Auth::user()->can(config('larapolls.permissions.prefix') . config('larapolls.permissions.allowPoll')) ? 'checked' : 'disabled'}}>
                  <label class="custom-control-label" for="allowd">{{ __('larapolls::larapolls.information_allowed') }}</label>
                </div>
              </div>
          </div>
          <div class="form-group row">
              <label for="option1" class="col-sm-2 col-form-label text-md-right">{{ __('larapolls::larapolls.text_option', ['value' => 1]) }}</label>
              <div class="col-sm-9">
                  <input id="option1" type="text" class="form-control{{ $errors->has('option1') ? ' is-invalid' : '' }}" name="option1" value="{{old('option1')}}">
                  @if ($errors->has('option1'))
                      <span class="invalid-feedback">
                          <strong>{{ $errors->first('option1') }}</strong>
                      </span>
                  @endif
              </div>
          </div>
          <div class="form-group row">
              <label for="option2" class="col-sm-2 col-form-label text-md-right">{{ __('larapolls::larapolls.text_option', ['value' => 2]) }}</label>
              <div class="col-sm-9">
                  <input id="option2" type="text" class="form-control{{ $errors->has('option2') ? ' is-invalid' : '' }}" name="option2" value="{{old('option2')}}">
                  @if ($errors->has('option2'))
                      <span class="invalid-feedback">
                          <strong>{{ $errors->first('option2') }}</strong>
                      </span>
                  @endif
              </div>
          </div>
          <div id="options"></div>
          <div class="form-group row mb-0">
              <div class="col-md-6 offset-md-2">
                  <button type="submit" class="btn btn-primary">{{ __('larapolls::larapolls.action_create_poll') }}</button>
                  <button id="addOptionButton" type="button" class="btn btn-secondary">Option hinzufügen</button>
            			<button id="resetOptionButton" type="button" class="btn btn-secondary">Zurücksetzen</button>
                  <input id="option_count" name="option_count" type="hidden" value="2"/>
              </div>
          </div>
      </form>
    </div>
  </div>
</div>
</div>
<script>
var count = 2;
$(document).ready(function(){
  $("#addOptionButton").click(function(){
    count++;
    $("#options").html($("#options").html() + getOptionField(count));
    $("#option_count").val(count);
  });
  $("#resetOptionButton").click(function(){
    $("#options").html("");
    count = 2;
    $("#option_count").val(count);
  });
});

function getOptionField(fieldId){
  return '<div class="form-group row"><label for="option' + fieldId + '" class="col-sm-2 col-form-label text-md-right">'+fieldId+'. Option</label><div class="col-sm-9"><input id="option' + fieldId + '" type="text" class="form-control" name="option' + fieldId + '" value="<?php old("option'+fieldId'") ?>"></div></div>';
}
</script>
@endsection
