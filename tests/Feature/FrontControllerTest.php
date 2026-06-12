<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CategoryTop;
use App\Models\Gallery;
use App\Models\HomePageImageTag;
use App\Models\Post;
use App\Models\Schedule;
use App\Models\SmenaType;
use App\Models\Statistic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Category::create(['name_uz' => 'K1', 'name_ru' => 'К1']);
        CategoryTop::create(['name_uz' => 'T1', 'name_ru' => 'Т1', 'url' => '#']);
    }

    /** @test */
    public function index_sahifasi_200_qaytaradi(): void
    {
        Statistic::create([
            'classesCount' => 30,
            'studentsCount' => 1200,
            'teachersCount' => 50,
            'graduatesCount' => 200,
        ]);
        Post::create(['title_uz' => 'N1', 'title_ru' => 'N1', 'body_uz' => '...', 'body_ru' => '...', 'image' => 't.jpg']);
        HomePageImageTag::create(['title_uz' => 'S', 'title_ru' => 'П', 'body_uz' => 'Salom', 'body_ru' => 'Привет']);
        SmenaType::create(['name_uz' => '1-s', 'name_ru' => '1-с']);
        Schedule::create(['week_day' => 'Du', 'smena_id' => 1]);

        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /** @test */
    public function index_statistika_sonlarni_korsatadi(): void
    {
        Statistic::create(['classesCount' => 25, 'studentsCount' => 950, 'teachersCount' => 45, 'graduatesCount' => 180]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('25');
        $response->assertSee('950');
    }

    /** @test */
    public function schoolNews_6tagacha_postni_korsatadi(): void
    {
        for ($i = 1; $i <= 8; $i++) {
            Post::create(['title_uz' => "Y{$i}", 'title_ru' => "N{$i}", 'body_uz' => 'M', 'body_ru' => 'T', 'image' => "i{$i}.jpg"]);
        }

        $response = $this->get('/news');
        $response->assertStatus(200);
        $this->assertCount(6, $response->viewData('posts'));
    }

    /** @test */
    public function gallery_sahifasi_barcha_rasmlarni_korsatadi(): void
    {
        Gallery::create(['title_uz' => 'R1', 'title_ru' => 'Р1', 'image' => '1.jpg']);

        $response = $this->get('/gallery');
        $response->assertStatus(200);
        $this->assertCount(1, $response->viewData('gallery'));
    }

    /** @test */
    public function newsDetail_mavjud_postni_korsatadi(): void
    {
        $post = Post::create(['title_uz' => 'Batafsil', 'title_ru' => 'Деталь', 'body_uz' => 'Matn', 'body_ru' => 'Текст', 'image' => 'd.jpg']);

        $response = $this->get('/news/'.$post->id);
        $response->assertStatus(200);
        $response->assertSee('Batafsil');
    }

    /** @test */
    public function newsDetail_topilmasa_404_qaytaradi(): void
    {
        $response = $this->get('/news/999');
        $response->assertStatus(404);
    }
}