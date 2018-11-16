<?php



return [
    'SALT' => env('HASHIDS_SALT', 'hongtang'),
    'LENGTH' => env('HASHIDS_LENGTH', 6),
    'ALPHABET' => env('HASHIDS_ALPHABET','abcdefghijklmnopqrstuvwxyz1234567890')
];
