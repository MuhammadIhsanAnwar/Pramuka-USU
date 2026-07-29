<div class="rounded-[24px] border border-slate-200 bg-white p-4 shadow-sm">
    <form method="get" action="{{ $baseUrl }}" class="flex items-center gap-3">
        <input type="hidden" name="_" value="1" class="hidden" />
        @php
            $renderHiddenInput = function ($key, $value) use (&$renderHiddenInput) {
                if (is_array($value)) {
                    return collect($value)
                        ->map(fn ($item) => $renderHiddenInput($key . '[]', $item))
                        ->implode('');
                }

                return '<input type="hidden" name="' . e($key) . '" value="' . e($value) . '" />';
            };
        @endphp

        @foreach ($query as $queryKeyName => $queryValue)
            {!! $renderHiddenInput($queryKeyName, $queryValue) !!}
        @endforeach

        <select id="year-filter" name="{{ $queryKey }}" onchange="this.form.submit()"
            class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm transition focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">
            <option value="all" {{ $activeYear === 'all' ? 'selected' : '' }}>Semua</option>
            @foreach ($years as $yearButton)
                <option value="{{ $yearButton }}" {{ $activeYear === $yearButton ? 'selected' : '' }}>{{ $yearButton }}</option>
            @endforeach
        </select>
    </form>
</div>
