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

    <div class="fi-dropdown-header px-4 py-4">
        <div class="min-w-0">
            <p class="text-sm font-semibold text-slate-900 leading-5 break-words">{{ filament()->getUserName($user) }}</p>
            <p class="text-xs text-slate-500 leading-4 break-words">{{ $user->email }}</p>
        </div>
    </div>

    <x-filament::dropdown.list>
        <x-filament::dropdown.list.item
            tag="a"
            href="{{ auth()->user()->hasRole('Admin') ? url('/admin') : url('/dashboard') }}"
            :icon="\Filament\Support\Icons\Heroicon::RectangleStack"
            icon-color="primary"
            class="rounded-none w-full justify-start"
        >
            Dashboard
        </x-filament::dropdown.list.item>
        <x-filament::dropdown.list.item
            tag="a"
            href="{{ auth()->user()->hasRole('Admin') ? url('/admin/profile') : url('/user/profile') }}"
            :icon="\Filament\Support\Icons\Heroicon::UserCircle"
            icon-color="primary"
            class="rounded-none w-full justify-start"
        >
            Profil
        </x-filament::dropdown.list.item>
        <x-filament::dropdown.list.item
            tag="a"
            href="{{ route('home') }}"
            :icon="\Filament\Support\Icons\Heroicon::Home"
            icon-color="primary"
            class="rounded-none w-full justify-start"
        >
            Beranda Utama
        </x-filament::dropdown.list.item>
        <x-filament::dropdown.list.item
            tag="form"
            action="{{ route('logout') }}"
            :icon="\Filament\Support\Icons\Heroicon::ArrowLeftEndOnRectangle"
            icon-color="primary"
            class="rounded-none w-full justify-start fi-dropdown-list-item-logout"
        >
            @csrf
            Keluar
        </x-filament::dropdown.list.item>
    </x-filament::dropdown.list>

    @if (filament()->hasDarkMode() && (! filament()->hasDarkModeForced()))
        <x-filament::dropdown.list>
            <x-filament-panels::theme-switcher />
        </x-filament::dropdown.list>
    @endif
</x-filament::dropdown>

{{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::USER_MENU_AFTER) }}
