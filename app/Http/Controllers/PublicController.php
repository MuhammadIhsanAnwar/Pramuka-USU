<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\NewsCategory;
use App\Models\EventAgenda;
use App\Models\Gallery;
use App\Models\NewsPost;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home(): View
    {
        $homeData = Cache::remember('public.home.data', now()->addMinutes(5), function (): array {
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
        });

        return view('public.home', [
            'siteName' => $this->siteName(),
            ...$homeData,
        ]);
    }

    public function about(): View
    {
        return $this->pageView('Tentang Kami', 'Pramuka USU adalah wadah pembinaan karakter dan kepemimpinan di lingkungan Universitas Sumatera Utara.', [
            'Kami membina anggota agar disiplin, mandiri, dan berjiwa pelayanan.',
            'Kegiatan mencakup latihan rutin, pengabdian masyarakat, kemah, dan pelatihan kepemimpinan.',
        ]);
    }

    public function history(): View
    {
        return $this->pageView('Sejarah', 'Gerakan Pramuka di USU tumbuh sebagai bagian dari penguatan karakter mahasiswa dan kader kepemimpinan.', [
            'Unit ini berperan aktif dalam kegiatan kampus, pembinaan internal, dan jejaring antar gugus depan.',
            'Perjalanan organisasi dibangun di atas semangat Dasa Dharma dan Tri Satya.',
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
        