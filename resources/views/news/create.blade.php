@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row mb-3">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Добавление новости</h2>
                </div>
                <div>
                    <a class="btn btn-primary" href="{{ route('news.index') }}"> Вернуться назад</a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <hr>
        <form action="{{route('news.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <p>Язык статьи</p>
                <div class="form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="lang" value="ru">Русский язык
                    </label>
                </div>
                <div class="form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="lang" value="en">English
                    </label>
                </div>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="uname" placeholder="Заголовок" name="title" required>
            </div>
            <textarea class="form-control" id="shortText" rows="3" name="short_text" placeholder="Краткое описание" required></textarea>
            <textarea class="form-control mt-3" id="longText" rows="4" name="full_text" placeholder="Полное описание" required></textarea>
            <div class="form-group mt-3">
                <label for="image">Выберите картинку</label>
                <input id="image" type="file" name="image" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Submit</button>
        </form>
    </div>

@endsection