<?php
namespace App\Support;
use InvalidArgumentException;
final class Money
{
    // Perhitungan memakai integer sen supaya tidak terkena pembulatan float.
    public const MAX = 999999999999; // DECIMAL(12,2)
    public static function cents(string $decimal): int
    {
        if (!preg_match('/^\d{1,10}(?:\.\d{1,2})?$/D',$decimal)) {
            throw new InvalidArgumentException('Format harga tidak valid.');
        }
        $parts=explode('.',$decimal);
        return ((int)$parts[0])*100+(int)str_pad($parts[1]??'',2,'0');
    }
    public static function decimal(int $cents): string
    {
        if ($cents<0 || $cents>self::MAX) throw new InvalidArgumentException('Total di luar batas.');
        return intdiv($cents,100).'.'.str_pad((string)($cents%100),2,'0',STR_PAD_LEFT);
    }
    public static function rupiah(int $cents): string
    {
        $whole=preg_replace('/\B(?=(\d{3})+(?!\d))/', '.', (string)intdiv($cents,100));
        return 'Rp '.$whole.($cents%100 ? ','.str_pad((string)($cents%100),2,'0',STR_PAD_LEFT) : '');
    }
}
