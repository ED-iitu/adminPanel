@extends('layouts.app')

@section('content')
   <div class="container">
       <div class="row">
           <div class="col-lg-12 margin-tb">
               <div class="pull-left">
                   <h2>Новость: {{$news->title}}</h2>
               </div>
               <div>
                   <a class="btn btn-primary" href="{{ route('news.index') }}"> Вернуться назад</a>
                   <a class="btn btn-success" href="{{ route('news.edit', $news) }}"> Редактировать</a>
               </div>
           </div>
       </div>

       <div class="row mt-5">
           <div class="image-container" style="width: 300px; height: 300px">
               <img src="{{$news->image}}" alt="" style="max-width: 100%; max-height: 100%">
           </div>
           <div class="ml-5">
               <h4>Язык</h4>
               <p><b>{{$news->lang}}</b></p>

               <h4>Заголовок</h4>
               <p><b>{{$news->title}}</b></p>

               <h4>Краткое описание:</h4>
               <p><b>{{$news->short_text}}</b></p>

               <h4>Полный текст:</h4>
               <p><b>{{$news->full_text}}</b></p>

               <h4>Дата создания:</h4>
               <p><b>{{$news->created_at}}</b></p>
           </div>
       </div>
   </div>
@endsection