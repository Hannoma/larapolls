@extends('layouts.app')

@section('css')
<script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js" integrity="sha384-SlE991lGASHoBfWbelyBPLsUlwY1GwNDJo3jSJO04KZ33K2bwfV9YBauFfnzvynJ" crossorigin="anonymous"></script>
@endsection

@section('content')
<div class="container">
  {!! Hannoma\Larapolls\PollDrawer::draw(3) !!}

</div>
@endsection
