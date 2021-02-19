@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Обновление адреса</h2>
                </div>
                <div>
                    <a class="btn btn-primary" href="{{ route('contacts.index') }}"> Вернуться назад</a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success mt-3" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <h1></h1>
        <hr>
        <form action="{{route('contacts.update', $contacts)}}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" value="{{$contacts->id}}" name="id">
            <div class="form-group">
                <input type="text" class="form-control" id="phone" placeholder="Телефон" name="phone" required value="{{$contacts->phone}}">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="address" placeholder="Адрес" name="address" required value="{{$contacts->address}}">
            </div>
            <button type="submit" class="btn btn-primary mt-3">Submit</button>
        </form>
    </div>

@endsection