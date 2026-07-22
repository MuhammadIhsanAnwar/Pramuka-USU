@props([
    'inlinePrefix' => false,
    'inlineSuffix' => false,
])

<input
    {{
        $attributes->class([
            'fi-input',
            'fi-input-has-inline-prefix' => $inlinePrefix,
            'fi-input-has-inline-suffix' => $inlineSuffix,
        ])
    }}
    style="border: 1px solid rgba(62, 39, 26, 0.2); border-radius: 14px; padding: 0.75rem 0.9rem; background-color: #fff; color: #3E271A; box-shadow: inset 0 1px 2px rgba(0,0,0,0.03);"
/>
