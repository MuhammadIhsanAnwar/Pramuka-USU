<?php

namespace App\Services;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Support\Facades\File;

class QrCodeService
{
    public function generateSvg(string $text, string $relativePath): string
    {
        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_MARKUP_SVG,
            'outputBase64' => false,
            'svgAddXmlHeader' => false,
            'scale' => 8,
            'quietzoneSize' => 2,
        ]);

        $absolutePath = public_path('storage/' . $relativePath);
        File::ensureDirectoryExists(dirname($absolutePath));

        (new QRCode($options))->render($text, $absolutePath);

        return $relativePath;
    }
}