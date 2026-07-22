<x-filament-panels::page>
    <div class="space-y-6 rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Lengkapi Profil</h2>
            </div>
        </div>

        <div class="rounded-[20px] border border-slate-200 bg-slate-50/70 p-4">
            <div>
                {{ $this->form }}
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button
                type="button"
                wire:click.prevent="saveSection"
                class="rounded-full bg-[#3E271A] px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#362116]"
            >
                Simpan
            </button>
        </div>
    </div>
</x-filament-panels::page>
