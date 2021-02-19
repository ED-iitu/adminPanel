<?php

namespace App\Http\Controllers;

use App\News;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Mail;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $news = News::all();

        return view('news.index', [
            'news' => $news
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('news.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->hasFile('image')) {
            $validated = $request->validate([
                'title' => 'string|max:300',
                'short_text' => 'string|max:3000',
                'full_text' => 'string|max:300000',
                'lang' => 'string|max:4',
                'image' => 'mimes:jpeg,png|max:1014',
            ]);


            $image_link = $request->file('image');
            $extensionImage = $image_link->getClientOriginalExtension();
            Storage::disk('public')->put($image_link->getFilename().'.'.$extensionImage,  File::get($image_link));

            News::create([
                'title' => $validated['title'],
                'short_text' => $validated['short_text'],
                'full_text' => $validated['full_text'],
                'lang'  => $validated['lang'],
                'image' => '/uploads/' . $image_link->getFilename() . '.' . $extensionImage,
            ]);

            return redirect()->back()->with('success', 'Новость успешно добавлена');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\News  $news
     * @return \Illuminate\Http\Response
     */
    public function show(News $news)
    {
        return view('news.show', [
            'news' => $news
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\News  $news
     * @return \Illuminate\Http\Response
     */
    public function edit(News $news)
    {
        return view('news.edit', [
            'news' => $news
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\News  $news
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'string|max:300',
            'short_text' => 'string|max:3000',
            'full_text' => 'string|max:300000',
            'image' => 'mimes:jpeg,png|max:1014',
        ]);

        $image_link = $request->file('image');

        if (null !== $image_link) {
            $extensionImage = $image_link->getClientOriginalExtension();
            Storage::disk('public')->put($image_link->getFilename().'.'.$extensionImage,  File::get($image_link));
        } else {
            $img = $news->image;
        }


        $news->update([
            'title' => $validated['title'],
            'short_text' => $validated['short_text'],
            'full_text' => $validated['full_text'],
            'lang'  => $news->lang,
            'image' => $img ?? '/uploads/' . $image_link->getFilename() . '.' . $extensionImage,
        ]);

        return redirect()->back()->with('success', 'Новость успешно обновлена');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\News  $news
     * @return \Illuminate\Http\Response
     */
    public function destroy(News $news)
    {
        if($news->delete()){
            News::query()->where(['id' => $news->id])->delete();
        }

        return redirect()->back()->with('success', 'Новость успешно удалена');
    }


    /**
     * Возвращает список новостей
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getAllNews(Request $request)
    {

        $id = $request->id;
        $lang = $request->lang;
        $perPage = $request->per_page ?? 5;

        if (null == $lang) {
            $lang = 'ru';
        }

        if (null == $id) {
            $news = News::query()->where(['lang' => $lang])->paginate($perPage);

            if ($news->isEmpty()) {
                return response(['error' => 'Список новостей пуст'], 404);
            }

            return response([
                'news' => $news,
            ], 200);
        }

        $news = News::query()->where(['id' => $id, 'lang' => $lang])->paginate($perPage);

        if ($news->isEmpty()) {
            return response(['error' => 'Новость не найдена'], 404);
        }

        return response(['news' => $news], 200);
    }

    /**
     * Отправка письма
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function basic_email(Request $request) {
        try {
            $to      = 'info@eurasia-en.com';
            $subject = 'Новое сообщение с сайта eurasia-en.com';
            $message = "Name: ". $request->name . "<br>" . "Phone: " . $request->phone;
            $headers = 'From: operator@eurasia-en.com' . "\r\n" .
                'Reply-To: operator@eurasia-en.com' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

            mail($to, $subject, $message, $headers);

            return response(['status' => 'ok'], 200);
        } catch (Exception $e) {
            return response(['error' => $e->getMessage()], 500);
        }
    }
}
