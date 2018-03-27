@extends('layouts.app')

@section('css')
<script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js" integrity="sha384-SlE991lGASHoBfWbelyBPLsUlwY1GwNDJo3jSJO04KZ33K2bwfV9YBauFfnzvynJ" crossorigin="anonymous"></script>
@endsection

@section('content')
<div class="container">
  @include('larapolls::poll_partial_v4', ['poll' => Hannoma\Larapolls\Models\Poll::where('id', 1)->first()])

</div>
@endsection
