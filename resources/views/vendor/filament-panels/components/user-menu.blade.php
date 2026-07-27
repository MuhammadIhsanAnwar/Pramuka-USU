@props([
    'position' => null,
])

@php
    use Filament\Actions\Action;
    use Filament\Enums\UserMenuPosition;
    use Illuminate\Support\Arr;

    $user = filament()->auth()->user();

    $items = $this->getUserMenuItems();

    $itemsBeforeAndAfterThemeSwitcher = collect($items)
        ->groupBy(fn (Action $item): bool => $item->getSort() < 0, preserveKeys: true)
        ->all();
    $itemsBeforeThemeSwitcher = $itemsBeforeAndAfterThemeSwitcher[true] ?? collect();
    $itemsAfterThemeSwitcher = $itemsBeforeAndAfterThemeSwitcher[false] ?? collect();

    $hasProfileHeader = $itemsBeforeThemeSwitcher->has('profile') &&
        blank(($item = Arr::first($itemsBeforeThemeSwitcher))->getUrl()) &&
        (! $item->hasAction());

    if ($itemsBeforeThemeSwitcher->has('profile')) {
        $itemsBeforeThemeSwitcher = $itemsBeforeThemeSwitcher->prepend($itemsBeforeThemeSwitcher->pull('profile'), 'profile');
    }

    $position ??= filament()->getUserMenuPosition();

    $isSidebarCollapsibleOnDesktop = filament()->isSidebarCollapsibleOnDesktop();
@endphp

{{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::USER_MENU_BEFORE) }}

<x-filament::dropdown
    :placement="($position === UserMenuPosition::Topbar) ? 'bottom-end' : 'top-end'"
    :teleport="$position === UserMenuPosition::Topbar"
    :attributes="
        \Filament\Support\prepare_inherited_attributes($attributes)
            ->class(['fi-user-menu'])
    "
>
    <x-slot name="trigger">
        @if ($position === UserMenuPosition::Topbar)
            <button
                aria-label="{{ __('filament-panels::layout.actions.open_user_menu.label') }}"
                type="button"
                class="fi-user-menu-trigger"
            >
                <span class="fi-user-menu-trigger-avatar-wrapper">
                    <x-filament-panels::avatar.user :user="$user" loading="lazy" />
                </span>
                <span class="fi-user-menu-trigger-name">{{ filament()->getUserName($user) }}</span>
            </button>
        @else
            <button
                aria-label="{{ __('filament-panels::layout.actions.open_user_menu.label') }}"
                type="button"
                class="fi-user-menu-trigger"
            >
                <x-filament-panels::avatar.user :user="$user" loading="lazy" />

                <span
                    @if ($isSidebarCollapsibleOnDesktop)
                        x-show="$store.sidebar.isOpen"
                    @endif
                    class="fi-user-menu-trigger-text"
                >
                    {{ filament()->getUserName($user) }}
                </span>

                {{
                    \Filament\Support\generate_icon_html(\Filament\Support\Icons\Heroicon::ChevronUp, alias: \Filament\View\PanelsIconAlias::USER_MENU_TOGGLE_BUTTON, attributes: new \Illuminate\View\ComponentAttributeBag([
                        'x-show' => $isSidebarCollapsibleOnDesktop ? '$store.sidebar.isOpen' : null,
                    ]))
                }}
            </button>
        @endif
    </x-slot>

    <div class="border-b border-slate-200 px-4 py-3 text-left whitespace-normal break-words">
        <p class="text-sm font-semibold text-slate-900 break-words">{{ filament()->getUserName($user) }}</p>
        <p class="text-xs text-slate-500 break-words">{{ $user->email }}</p>
    </div>

    <div class="border-b border-slate-200 px-4 py-2">
        <a href="{{ route('home') }}" class="block rounded-xl px-3 py-2 text-sm text-slate-700 transition hover:bg-slate-100">Beranda Utama</a>
    </div>

    @if ($hasProfileHeader)
        @php
            $item = $itemsBeforeThemeSwitcher['profile'];
            $itemColor = $item->getColor();
            $itemIcon = $item->getIcon();

            unset($itemsBeforeThemeSwitcher['profile']);
        @endphp

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::USER_MENU_PROFILE_BEFORE) }}

        <x-filament::dropdown.header :color="$itemColor" :icon="$itemIcon">
            {{ $item->getLabel() }}
        </x-filament::dropdown.header>

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::USER_MENU_PROFILE_AFTER) }}
    @endif

    @if ($itemsBeforeThemeSwitcher->isNotEmpty())
        <x-filament::dropdown.list>
            @foreach ($itemsBeforeThemeSwitcher as $key => $item)
                @if ($key === 'profile')
                    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::USER_MENU_PROFILE_BEFORE) }}

                    {{ $item }}

                    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::USER_MENU_PROFILE_AFTER) }}
                @else
                    {{ $item }}
                @endif
            @endforeach
        </x-filament::dropdown.list>
    @endif

    @if (filament()->hasDarkMode() && (! filament()->hasDarkModeForced()))
        <x-filament::dropdown.list>
            <x-filament-panels::theme-switcher />
        </x-filament::dropdown.list>
    @endif

    @if ($itemsAfterThemeSwitcher->isNotEmpty())
        <x-filament::dropdown.list>
            @foreach ($itemsAfterThemeSwitcher as $key => $item)
                @if ($key === 'profile')
                    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::USER_MENU_PROFILE_BEFORE) }}

                    {{ $item }}

                    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::USER_MENU_PROFILE_AFTER) }}
                @else
                    {{ $item }}
                @endif
            @endforeach
        </x-filament::dropdown.list>
    @endif
</x-filament::dropdown>

{{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::USER_MENU_AFTER) }}
