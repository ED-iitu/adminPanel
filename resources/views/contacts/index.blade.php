@extends('layouts.app')

@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="mt-5">
            <h3>Контакты</h3>
        </div>
            @foreach($contacts as $contact)
            <ul class="list-group mt-3">
                <h3>{{$contact->lang}}</h3>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>
                        Телефон #1 - {{$contact->first_phone}} <br>
                        Телефон #2 - {{$contact->second_phone}} <br>
                        Email   #1 - {{$contact->first_email}} <br>
                        Email   #2 - {{$contact->second_email}} <br>
                        Адрес   KZ - {{$contact->address_kz}} <br>
                        Адрес   EU - {{$contact->address_eu}} <br>
                    </span>
                    <div>
                        <a class="btn btn-primary edit" href="{{ route('contacts.edit', $contact) }}">Изменить</a>
                    </div>
                </li>
            </ul>
@endforeach
    </div>
@endsection