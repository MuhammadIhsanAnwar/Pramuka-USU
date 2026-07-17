<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\NewsCategory;
use App\Models\EventAgenda;
use App\Models\Gallery;
use App\Models\NewsPost;
use App\Models\AboutGroup;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    public function home(): View
    {
        SiteSetting::ensureDefaultSettings();

        $homeData = Cache::get('public.home.data');

        if (! is_array($homeData)
            || ! isset($homeData['latestNews'])
            || ! $homeData['latestNews'] instanceof Collection
        ) {
            $homeData = $this->buildHomeData();
            Cache::put('public.home.data', $homeData, now()->addMinutes(5));
        }

        return view('public.home', [
            'siteName' => $this->siteName(),
            'backgroundImage' => $this->normalizeSettingPath($this->settingValue('home_background_image', '/storage/beranda/Beranda.png', true)),
            'videoIntro' => $this->normalizeSettingPath($this->settingValue('intro_video', '/storage/beranda/Intro.mp4', true)),
            ...$homeData,
        ]);
    }

    private function normalizeSettingPath(mixed $path): string
    {
        if (is_string($path) && Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return asset(ltrim((string) $path, '/'));
    }

    private function buildHomeData(): array
    {
        return [
            'latestNews' => NewsPost::query()
                ->published()
                ->with(['category', 'author'])
                ->latest('published_at')
                ->take(3)
                ->get(),
            'upcomingAgendas' => EventAgenda::query()
                ->published()
                ->upcoming()
                ->orderBy('starts_at')
                ->take(3)
                ->get(),
            'galleryItems' => Gallery::query()
                ->with('uploader')
                ->latest()
                ->take(6)
                ->get(),
            'stats' => [
                'users' => User::query()->active()->count(),
                'pembina' => User::query()->active()->pembina()->count(),
                'peserta_didik' => User::query()->active()->pesertaDidik()->count(),
                'news' => NewsPost::query()->published()->count(),
                'agendas' => EventAgenda::query()->published()->count(),
                'attendance' => Attendance::query()->count(),
            ],
        ];
    }

    public function about(): View
    {
        $groups = AboutGroup::query()
            ->active()
            ->orderBy('order')
            ->with(['members' => fn ($query) => $query->active()->orderBy('order')->orderBy('name')])
            ->get();

        return view('public.about', [
            'siteName' => $this->siteName(),
            'groups' => $groups,
        ]);
    }

    public function history(): View
    {
        $historyPage = \App\Models\HistoryPage::query()
            ->active()
            ->orderBy('updated_at', 'desc')
            ->first();

        if ($historyPage === null) {
            return view('public.history', [
                'siteName' => $this->siteName(),
                'title' => 'Sejarah',
                'lead' => 'Gerakan Pramuka di USU tumbuh sebagai bagian dari penguatan karakter mahasiswa dan kader kepemimpinan.',
                'content' => '<p>Unit ini berperan aktif dalam kegiatan kampus, pembinaan internal, dan jejaring antar gugus depan.</p><p>Perjalanan organisasi dibangun di atas semangat Dasa Dharma dan Tri Satya.</p>',
                'photo_paths' => [],
            ]);
        }

        return view('public.history', [
            'siteName' => $this->siteName(),
            'title' => $historyPage->title,
            'lead' => $historyPage->lead,
            'content' => $historyPage->content,
            'photo_paths' => $historyPage->photo_paths ?? [],
        ]);
    }

    public function visionMission(): View
    {
        return $this->pageView('Visi Misi', 'Visi dan misi organisasi menjadi arah pembinaan, pelayanan, dan pengabdian anggota.', [
            'Visi: menjadi organisasi Pramuka universitas yang unggul, inklusif, dan berdampak.',
            'Misi: membina kader yang berkarakter, menguatkan kegiatan kepemimpinan, dan memperluas kontribusi sosial.',
        ]);
    }

    public function structure(): View
    {
        return $this->pageView('Struktur Organisasi', 'Struktur organisasi memastikan koordinasi yang jelas antara pembina, pengurus, dan anggota.', [
            'Pembina bertugas mengarahkan pembinaan dan menjaga arah organisasi.',
            'Pengurus menjalankan program kerja, administrasi, dan koordinasi kegiatan.',
        ]);
    }

    public function newsIndex(Request $request): View
    {
        $selectedCategory = $request->string('kategori')->toString();

        $newsQuery = NewsPost::query()
            ->published()
            ->with(['category', 'author'])
            ->latest('published_at');

        if ($selectedCategory !== '') {
            $newsQuery->whereHas('category', function ($query) use ($selectedCategory): void {
                $query->active()->where('slug', $selectedCategory);
            });
        }

        $newsPosts = $newsQuery->paginate(9)->withQueryString();

        return view('public.news.index', [
            'siteName' => $this->siteName(),
            'newsPosts' => $newsPosts,
            'categories' => NewsCategory::query()
                ->active()
                ->orderBy('name')
                ->get(),
            'selectedCategory' => $selectedCategory,
        ]);
    }

    public function newsShow(string $slug): View
    {
        $newsPost = NewsPost::query()
            ->published()
            ->with(['category', 'author'])
            ->where('slug', $slug)
            ->firstOrFail();

        $newsPost->increment('viewer_count');

        $relatedNews = NewsPost::query()
            ->published()
            ->where('id', '!=', $newsPost->id)
            ->where('news_category_id', $newsPost->news_category_id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('public.news.show', [
            'siteName' => $this->siteName(),
            'newsPost' => $newsPost,
            'relatedNews' => $relatedNews,
        ]);
    }

    public function agendaIndex(): View
    {
        $agendas = EventAgenda::query()
            ->published()
            ->orderBy('starts_at')
            ->paginate(9);

        return view('public.agenda.index', [
            'siteName' => $this->siteName(),
            'agendas' => $agendas,
        ]);
    }

    public function agendaShow(EventAgenda $eventAgenda): View
    {
        return view('public.agenda.show', [
            'siteName' => $this->siteName(),
            'agenda' => $eventAgenda,
        ]);
    }

    public function galleryIndex(): View
    {
        $galleries = Gallery::query()
            ->with('uploader')
            ->latest()
            ->paginate(12);

        return view('public.gallery.index', [
            'siteName' => $this->siteName(),
            'galleries' => $galleries,
        ]);
    }

    public function galleryShow(Gallery $gallery): View
    {
        return view('public.gallery.show', [
            'siteName' => $this->siteName(),
            'gallery' => $gallery,
        ]);
    }

    public function contact(): View
    {
        return view('public.contact', [
            'siteName' => $this->siteName(),
            'contactEmail' => $this->settingValue('contact_email', 'pramuka@usu.ac.id'),
            'contactPhone' => $this->settingValue('contact_phone', '+62 61 12345678'),
        ]);
    }

    private function pageView(string $title, string $lead, array $points): View
    {
        return view('public.page', [
            'siteName' => $this->siteName(),
            'title' => $title,
            'lead' => $lead,
            'points' => $points,
        ]);
    }

    private function siteName(): string
    {
        return (string) $this->settingValue('site_name', 'Pramuka USU');
    }

    private function settingValue(string $key, mixed $default = null): mixed
    {
        $setting = SiteSetting::query()
            ->where('setting_key', $key)
            ->first();

        if ($setting === null) {
            return $default;
        }

        $value = $setting->value;

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $value = $decoded;
            }
        }

        if (is_array($value)) {
            if (array_key_exists(0, $value)) {
                return $value[0];
            }

            return $default;
        }

        if ($value === null || $value === '') {
            return $default;
        }

        return $value;
    }
}
