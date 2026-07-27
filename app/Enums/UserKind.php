<?php

namespace App\Enums;

enum UserKind: string
{
    case Pembina = 'pembina';
    case PesertaDidik = 'peserta_didik';
    case Purna = 'purna';
    case Tamu = 'tamu';
}