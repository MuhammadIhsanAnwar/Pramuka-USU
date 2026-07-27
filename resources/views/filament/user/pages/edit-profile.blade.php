<x-filament-panels::page>
    <div class="space-y-6 rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm">
        <style>
            /* Immediate overrides for checkbox-like buttons (Samakan dengan Domisili)
               and common Filament toggle/track markup (cover multiple versions) */
            .filament-forms-field-wrapper input[type="checkbox"] + label,
            .filament-forms-field-wrapper input[type="checkbox"] ~ label,
            .filament-forms-field-wrapper label.filament-forms-checkbox-label,
            /* Filament toggle root */
            .fi-fo-toggle,
            .fi-toggle,
            .filament-forms-toggle,
            .filament-toggle,
            /* toggle track (different versions) */
            .fi-toggle-track,
            .fi-fo-toggle .fi-toggle-track,
            .filament-toggle .fi-toggle-track,
            .filament-forms-toggle .fi-toggle-track {
                background-color: #FAF3EB !important; /* cream */
                color: rgba(0,0,0,0.85) !important;
                padding: .35rem .6rem !important;
                border-radius: .5rem !important;
                border: 1px solid rgba(62,39,26,0.08) !important;
                display: inline-flex !important;
                align-items: center !important;
                gap: .35rem !important;
            }
            .filament-forms-field-wrapper input[type="checkbox"]:checked + label,
            .filament-forms-field-wrapper input[type="checkbox"]:checked ~ label,
            .filament-forms-field-wrapper input[type="checkbox"]:checked ~ .filament-forms-checkbox-label,
            .filament-forms-field-wrapper input[type="checkbox"]:checked ~ label.filament-forms-checkbox-label,
            /* target toggle components when aria-checked is true */
            .fi-fo-toggle[aria-checked="true"],
            .fi-toggle[aria-checked="true"],
            .filament-forms-toggle[aria-checked="true"],
            .filament-toggle[aria-checked="true"],
            .fi-toggle-track[aria-checked="true"] {
                background-color: #3E271A !important; /* brown */
                color: #fff !important;
                border-color: transparent !important;
            }
            /* also target elements with data-state attribute if present */
            .fi-fo-toggle[data-state="true"],
            .fi-toggle[data-state="true"],
            .filament-forms-toggle[data-state="true"] {
                background-color: #3E271A !important;
                color: #fff !important;
            }
        </style>
        <div class="flex items-center justify-between gap-3">
        </div>

        <div class="rounded-[20px] border border-slate-200 bg-slate-50/70 p-4">
            <div>
                {{ $this->form }}
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button
                type="button"
                wire:click="saveSection"
                wire:loading.attr="disabled"
                wire:target="saveSection"
                class="rounded-full bg-[#3E271A] px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#362116] disabled:cursor-wait disabled:opacity-70"
            >
                Simpan
            </button>
        </div>
    </div>
</x-filament-panels::page>
