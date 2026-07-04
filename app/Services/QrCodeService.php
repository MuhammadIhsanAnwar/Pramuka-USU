<?php

namespace App\Services;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Support\Facades\Storage;

class QrCodeService
{
    public function generateSvg(string $text, string $relativePath): string
    {
        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_MARKUP_SVG,
            'svgAddXmlHeader' => false,
            'scale' => 8,
            'quietzoneSize' => 2,
        ]);

        $svg = (new QRCode($options))->render($text);

        Storage::disk('public')->put($relativePath, $svg);

        return $relativePath;
    }
}