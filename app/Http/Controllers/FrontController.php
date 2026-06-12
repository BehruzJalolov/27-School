<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryTop;
use App\Models\CategoryChild;
use App\Models\EmpCategory;
use App\Models\Employee;
use App\Models\Gallery;
use App\Models\Infographic;
use App\Models\Post;
use App\Models\Schedule;
use App\Models\HomePageImageTag;
use App\Models\SmenaType;
use App\Models\Statistic;
use App\Models\UsefulResource;
use App\Services\FrontService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Message;
use Illuminate\Support\Facades\App;

class FrontController extends Controller
{
    protected $frontService;

    public function __construct(FrontService $frontService)
    {
        $this->frontService = $frontService;
        
        // Share common data to all views
        view()->share('categories', Category::all());
        view()->share('categoryTop', CategoryTop::all());
    }

    /**
     * Bosh sahifa
     */
    public function index()
    {
        $data = $this->frontService->getHomePageData();
        return view('index', $data);
    }

    /**
     * Maktab haqida ma'lumot
     */
    public function schoolTack(Request $request)
    {
        $category = Category::with('children')->find($request->category);
        return view('frond.schoolTack', compact('category'));
    }

    /**
     * Rahbariyat sahifasi
     */
    public function leaderShep()
    {
        $teachers = $this->frontService->getLeadershipEmployees();
        return view('frond.leaderShep', compact('teachers'));
    }

    /**
     * Rahbariyat batafsil
     */
    public function LeaderShepDatail()
    {
        $teachers = $this->frontService->getLeadershipEmployees();
        return view('frond.LeaderShepDeatil', compact('teachers'));
    }

    /**
     * O'qituvchilar sahifasi
     */
    public function teachers(Request $request)
    {
        $query = $request->input('query');
        $teachers = $this->frontService->getTeachers($query);
        return view('frond.teachers', compact('teachers', 'query'));
    }

    /**
     * Rekvizitlar
     */
    public function rekvizit()
    {
        return view('frond.rekvizit');
    }

    /**
     * Yangiliklar ro'yxati
     */
    public function schoolNews()
    {
        $posts = $this->frontService->getLatestPosts(6);
        return view('frond.schoolNews', compact('posts'));
    }

    /**
     * Yangilik batafsil
     */
    public function newsDetail($id)
    {
        $post = Post::findOrFail($id);
        return view('frond.newsDetail', compact('post'));
    }

    /**
     * Yangiliklarni qidirish
     */
    public function searchPosts(Request $request)
    {
        $query = $request->input('query');
        $posts = $this->frontService->searchPosts($query);
        return view('frond.schoolNews', compact('posts', 'query'));
    }

    /**
     * Dars jadvali (ta'lim)
     */
    public function education(Request $request)
    {
        $query      = $request->input('query');
        $categoryId = $request->input('category');

        $data = $this->frontService->getEducationData($query, $categoryId);
        
        return view('frond.education', $data);
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
        
        $data = $this->frontService->getEducationData($query);
        
        return view('frond.education', $data + ['query' => $query]);
    }

    /**
     * Foydali resurslar
     */
    public function usefulresurs()
    {
        $usefulresource = $this->frontService->getUsefulResources();
        return view('frond.usefulResurs', compact('usefulresource'));
    }

    public function usefulResourceDetail($id)
    {
        $resource = UsefulResource::findOrFail($id);
        return view('frond.usefulresoursedetail', compact('resource'));
    }

    /**
     * Galereya
     */
    public function Gallery()
    {
        $gallery = $this->frontService->getGallery();
        return view('frond.gallery', compact('gallery'));
    }

    /**
     * Infografika
     */
    public function infoGrafika()
    {
        $infografika = $this->frontService->getInfographics();
        return view('frond.infoGrafika', compact('infografika'));
    }

    /**
     * Bog'lanish
     */
    public function connect()
    {
        return view('frond.connect');
    }

    /**
     * Email yuborish
     */
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