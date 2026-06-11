<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryTop;
use App\Models\EmpCategory;
use App\Models\Employee;
use App\Models\Gallery;
use App\Models\Infographic;
use App\Models\Position;
use App\Models\Post;
use App\Models\Schedule;
use App\Models\HomePageImageTag;
use App\Models\SmenaType;
use App\Models\Statistic;
use App\Models\UsefulResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Message;
use Illuminate\Support\Facades\App;

class FrontController extends Controller
{
    public function __construct()
    {
        // Share common data to all views — loaded once, used everywhere
        view()->share('categories', Category::all());
        view()->share('categoryTop', CategoryTop::all());
    }

    public function index()
    {
        $statistics  = Statistic::all();
        $posts       = Post::latest()->take(6)->get();
        $imageTags   = HomePageImageTag::all();
        $schedule    = Schedule::with('smena')->get();
        $categories  = Category::all();

        return view('index', compact('statistics', 'posts', 'imageTags', 'schedule', 'categories'));
    }

    public function schoolTack(Request $request)
    {
        $category = Category::with('children')->find($request->category);
        return view('frond.schoolTack', compact('category'));
    }

    public function leaderShep()
    {
        $teachers = Employee::with(['position', 'category'])
            ->whereHas('category', fn ($q) => $q->where('name_uz', 'Rahbariyat'))
            ->get();

        return view('frond.leaderShep', compact('teachers'));
    }

    public function LeaderShepDatail()
    {
        $teachers = Employee::with(['position', 'category'])
            ->whereHas('category', fn ($q) => $q->where('name_uz', 'Rahbariyat'))
            ->get();

        return view('frond.LeaderShepDeatil', compact('teachers'));
    }

    public function teachers(Request $request)
    {
        $query = $request->input('query');

        $teachersQuery = Employee::with(['position', 'category'])
            ->whereHas('category', fn ($q) => $q->where('name_uz', '!=', 'Rahbariyat'));

        if ($query) {
            $teachersQuery->where(function ($q) use ($query) {
                $q->where('name_uz', 'like', "%{$query}%")
                  ->orWhere('name_ru', 'like', "%{$query}%")
                  ->orWhereHas('category', fn ($cat) =>
                      $cat->where('name_uz', 'like', "%{$query}%")
                          ->orWhere('name_ru', 'like', "%{$query}%")
                  );
            });
        }

        $teachers = $teachersQuery->get()
            ->groupBy(fn ($item) => $item->category
                ? $item->category['name_' . App::getLocale()]
                : 'Boshqa toifa'
            );

        return view('frond.teachers', compact('teachers', 'query'));
    }

    public function rekvizit()
    {
        return view('frond.rekvizit');
    }

    public function schoolNews()
    {
        $posts = Post::latest()->take(6)->get();
        return view('frond.schoolNews', compact('posts'));
    }

    public function newsDetail($id)
    {
        $post = Post::findOrFail($id);
        return view('frond.newsDetail', compact('post'));
    }

    public function searchPosts(Request $request)
    {
        $query = $request->input('query');

        $posts = Post::when($query, function ($q) use ($query) {
            $q->where('title_uz', 'like', "%{$query}%")
              ->orWhere('title_ru', 'like', "%{$query}%")
              ->orWhere('body_uz', 'like', "%{$query}%")
              ->orWhere('body_ru', 'like', "%{$query}%");
        })->get();

        return view('frond.schoolNews', compact('posts', 'query'));
    }

    public function education(Request $request)
    {
        $query      = $request->input('query');
        $categoryId = $request->input('category');

        $educations = Schedule::query()
            ->when($query, fn ($q) => $q->where('week_day', 'like', "%{$query}%"))
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->get();

        $smenaType = SmenaType::all();

        return view('frond.education', compact('educations', 'smenaType'));
    }

    public function educationDetail($id)
    {
        $schedule = Schedule::findOrFail($id);
        return view('frond.educationDetail', compact('schedule'));
    }

    public function educationByCategory($slug)
    {
        $category = EmpCategory::where('slug', $slug)->firstOrFail();
        $teachers = Employee::where('emp_category_id', $category->id)->get();
        return view('frontend.pages.education_by_category', compact('category', 'teachers'));
    }

    public function educationSearch(Request $request)
    {
        $query = $request->input('query');

        $educations = Schedule::when($query, fn ($q) => $q->where('week_day', 'like', "%{$query}%"))->get();
        $smenaType  = SmenaType::all();

        return view('frond.education', compact('educations', 'smenaType', 'query'));
    }

    public function usefulresurs()
    {
        $usefulresource = UsefulResource::all();
        return view('frond.usefulResurs', compact('usefulresource'));
    }

    public function usefulResourceDetail($id)
    {
        $resource = UsefulResource::findOrFail($id);
        return view('frond.usefulresoursedetail', compact('resource'));
    }

    public function Gallery()
    {
        $gallery = Gallery::all();
        return view('frond.gallery', compact('gallery'));
    }

    public function infoGrafika()
    {
        $infografika = Infographic::all();
        return view('frond.infoGrafika', compact('infografika'));
    }

    public function connect()
    {
        return view('frond.connect');
    }

    public function SendEmail(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'required|string|max:20',
            'mavzu'   => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        Mail::to('behruzjalolov13@gmail.com')->send(new Message($data));

        return redirect()->route('connect')->with('success', 'Email muvaffaqiyatli yuborildi!');
    }
}
