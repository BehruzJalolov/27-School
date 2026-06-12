<?php

namespace App\Services;

use App\Models\Category;
use App\Models\CategoryTop;
use App\Models\Employee;
use App\Models\HomePageImageTag;
use App\Models\Infographic;
use App\Models\Post;
use App\Models\Schedule;
use App\Models\SmenaType;
use App\Models\Statistic;
use App\Models\UsefulResource;
use App\Models\Gallery;
use Illuminate\Support\Facades\App;

class FrontService
{
    /**
     * Bosh sahifa uchun barcha kerakli ma'lumotlarni olish
     */
    public function getHomePageData(): array
    {
        return [
            'statistics' => Statistic::all(),
            'posts'      => Post::latest()->take(6)->get(),
            'imageTags'  => HomePageImageTag::all(),
            'schedule'   => Schedule::with('smena')->get(),
            'categories' => Category::all(),
        ];
    }

    /**
     * Rahbariyat xodimlarini olish
     */
    public function getLeadershipEmployees()
    {
        return Employee::with(['position', 'category'])
            ->whereHas('category', fn ($q) => $q->where('name_uz', 'Rahbariyat'))
            ->get();
    }

    /**
     * O'qituvchilarni olish (rahbariyatdan tashqari)
     */
    public function getTeachers(string $query = null)
    {
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

        return $teachersQuery->get()
            ->groupBy(fn ($item) => $item->category
                ? $item->category['name_' . App::getLocale()]
                : 'Boshqa toifa'
            );
    }

    /**
     * Yangiliklarni olish
     */
    public function getLatestPosts(int $limit = 6)
    {
        return Post::latest()->take($limit)->get();
    }

    /**
     * Yangiliklarni qidirish
     */
    public function searchPosts(string $query = null)
    {
        return Post::when($query, function ($q) use ($query) {
            $q->where('title_uz', 'like', "%{$query}%")
              ->orWhere('title_ru', 'like', "%{$query}%")
              ->orWhere('body_uz', 'like', "%{$query}%")
              ->orWhere('body_ru', 'like', "%{$query}%");
        })->get();
    }

    /**
     * Dars jadvalini olish
     */
    public function getEducationData(string $query = null, $categoryId = null): array
    {
        $educations = Schedule::query()
            ->when($query, fn ($q) => $q->where('week_day', 'like', "%{$query}%"))
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->get();

        $smenaType = SmenaType::all();

        return compact('educations', 'smenaType');
    }

    /**
     * Galereya rasmlarini olish
     */
    public function getGallery()
    {
        return Gallery::all();
    }

    /**
     * Infografika ma'lumotlarini olish
     */
    public function getInfographics()
    {
        return Infographic::all();
    }

    /**
     * Foydali resurslarni olish
     */
    public function getUsefulResources()
    {
        return UsefulResource::all();
    }
}