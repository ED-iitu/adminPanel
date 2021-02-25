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
            $subject = 'New message from eurasia-en.com';
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

    public function getCommodityData()
    {
        $client = new \GuzzleHttp\Client();

        try {

            $crudeOil = "https://sbcharts.investing.com/charts_xml/c4caa3c525726eb96b6ee67ddfa57896_1day.json";
            $crudeOilResponse = $client->request('GET', $crudeOil);
            $crudeOilContent = $crudeOilResponse->getBody()->getContents();
            $crudeOilContent = json_decode($crudeOilContent);
            $crudeOilPersent = $crudeOilContent->candles[0][1] - end($crudeOilContent->candles[1]);


            $brient = "https://sbcharts.investing.com/charts_xml/ecab723bd0c0050290d8fcb3b6ecc974_1day.json";
            $brientResponse = $client->request('GET', $brient);
            $brientOilContent = $brientResponse->getBody()->getContents();
            $brientOilContent = json_decode($brientOilContent);
            $brientPersent = $brientOilContent->candles[0][1] - end($brientOilContent->candles[1]);

            $gold = "https://sbcharts.investing.com/charts_xml/d565a7612f2d571d32033540114babb3_1day.json";
            $goldResponse = $client->request('GET', $gold);
            $goldContent = $goldResponse->getBody()->getContents();
            $goldContent = json_decode($goldContent);
            $goldPersent = $goldContent->candles[0][1] - end($goldContent->candles[1]);

            $gas = "https://sbcharts.investing.com/charts_xml/2862b1367008cea06e03f8cbaf7f40ed_1day.json";
            $gasResponse = $client->request('GET', $gas);
            $gasContent = $gasResponse->getBody()->getContents();
            $gasContent = json_decode($gasContent);
            $gasPersent = $gasContent->candles[0][1] - end($gasContent->candles[1]);

            $silver = "https://sbcharts.investing.com/charts_xml/c4651fb6e12fa8166f3d0af875aede53_1day.json";
            $silverResponse = $client->request('GET', $silver);
            $silverContent = $silverResponse->getBody()->getContents();
            $silverContent = json_decode($silverContent);
            $silverPersent = $silverContent->candles[0][1] - end($silverContent->candles[1]);


            return response(
                [
                    'status' => 'ok',
                    'data' => [
                        [
                            'name' => 'Crude Oil WTI',
                            'subject' => 'Energy',
                            'last_value' => $crudeOilContent->attr->last_value,
                            'persent' => $crudeOilPersent,
                            'date' => now(),
                            'decimals' => $crudeOilContent->attr->decimals ?? 2
                        ],
                        [
                            'name' => 'Brient Oil',
                            'subject' => 'Energy',
                            'last_value' => $brientOilContent->attr->last_value,
                            'persent' => $brientPersent,
                            'date' => now(),
                            'decimals' => $brientOilContent->attr->decimals ?? 2
                        ],
                        [
                            'name' => 'Gold',
                            'subject' => 'Metals',
                            'last_value' => $goldContent->attr->last_value,
                            'persent' => $goldPersent,
                            'date' => now(),
                            'decimals' => $goldContent->attr->decimals ?? 2
                        ],
                        [
                            'name' => 'Natural Gas',
                            'subject' => 'Metals',
                            'last_value' => $gasContent->attr->last_value,
                            'persent' => $gasPersent,
                            'date' => now(),
                            'decimals' => $gasContent->attr->decimals ?? 2
                        ],
                        [
                            'name' => 'Silver',
                            'subject' => 'Metals',
                            'last_value' => $silverContent->attr->last_value,
                            'persent' => $silverPersent,
                            'date' => now(),
                            'decimals' => $silverContent->attr->decimals ?? 2
                        ]
                    ]
                ], 200);
        } catch (\Exception $e) {
            return response(['error' => $e->getMessage()], 500);
        }
    }
}
