<?php

if (!function_exists('persianString')) {
    function persianString($string)
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $num = range(0, 9);
        $arabicNumbers = ['۰', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $string = str_replace($arabicNumbers, $persian, $string);
        return str_replace($num, $persian, $string);
    }
}

if (!function_exists('englishString')) {
    function englishString($string)
    {
        $arabicNumbers = ['۰', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $num = range(0, 9);
        $string = str_replace($persianNumbers, $num, $string);
        return str_replace($arabicNumbers, $num, $string);
    }
}

if (!function_exists('normalizeText')) {
    function normalizeText(string $text): string
    {
        $text = str_replace('ي', 'ی', $text);
        $text = str_replace('ك', 'ک', $text);
        $text = str_replace('٤', '۴', $text);
        $text = str_replace('٦', '۶', $text);

        return trim($text);
    }
}

if (!function_exists('formatPhoneNumber')) {
    function formatPhoneNumber(string $phoneNumber): string
    {
        if (Str::startsWith($phoneNumber, '0')) {
            $phoneNumber = (string)(int)$phoneNumber;
        } elseif (Str::startsWith($phoneNumber, '+98') and strlen($phoneNumber) != 10) {
            $phoneNumber = substr($phoneNumber, 3);
        } elseif (Str::startsWith($phoneNumber, '98') and strlen($phoneNumber) != 10) {
            $phoneNumber = substr($phoneNumber, 2);
        }
        return $phoneNumber;
    }
}

if (!function_exists('reformatPhoneNumber')) {
    function reformatPhoneNumber(string $phoneNumber): string
    {
        if (!Str::startsWith($phoneNumber, '0')) {
            $phoneNumber = '0' . $phoneNumber;
        }
        return $phoneNumber;
    }
}

if (!function_exists('formatPrice')) {
    function formatPrice($number): string
    {
        if ($number < 0) {
            return '-' . number_format($number * -1);
        }
        return number_format($number);
    }
}

if (!function_exists('reformatPrice')) {
    function reformatPrice($number): int
    {
        $number = str_replace(',', '', $number);
        return intval($number);
    }
}

if (!function_exists('convertNumberToText')) {
    function getNumberTextPostfix($level): string
    {
        switch ($level) {
            case 1:
                return '';
            case 2:
                return 'هزار';
            case 3:
                return 'میلیون';
            case 4:
                return 'بیلیون';
            case 5:
                return 'بیلیار';
            case 6:
                return 'تریلیون';
        }

        return '';
    }

    function getDigitText($num, $index): string
    {
        switch ($index) {
            case 1:
                switch ($num) {
                    case 1:
                        return 'یک';
                    case 2:
                        return 'دو';
                    case 3:
                        return 'سه';
                    case 4:
                        return 'چهار';
                    case 5:
                        return 'پنج';
                    case 6:
                        return 'شش';
                    case 7:
                        return 'هفت';
                    case 8:
                        return 'هشت';
                    case 9:
                        return 'نه';
                    case 10:
                        return 'ده';
                    case 11:
                        return 'یازده';
                    case 12:
                        return 'دوازده';
                    case 13:
                        return 'سیزده';
                    case 14:
                        return 'چهارده';
                    case 15:
                        return 'پانزده';
                    case 16:
                        return 'شانزده';
                    case 17:
                        return 'هفده';
                    case 18:
                        return 'هجده';
                    case 19:
                        return 'نوزده';
                    default:
                        return '';
                }
            case 2:
                switch ($num) {
                    case 1:
                        return 'ده';
                    case 2:
                        return 'بیست';
                    case 3:
                        return 'سی';
                    case 4:
                        return 'چهل';
                    case 5:
                        return 'پنجاه';
                    case 6:
                        return 'شصت';
                    case 7:
                        return 'هفتاد';
                    case 8:
                        return 'هشتاد';
                    case 9:
                        return 'نود';
                    default:
                        return '';
                }
            case 3:
                switch ($num) {
                    case 1:
                        return 'صد';
                    case 2:
                        return 'دویست';
                    case 3:
                        return 'سیصد';
                    case 4:
                        return 'چهارصد';
                    case 5:
                        return 'پانصد';
                    case 6:
                        return 'ششصد';
                    case 7:
                        return 'هفتصد';
                    case 8:
                        return 'هشتصد';
                    case 9:
                        return 'نهصد';
                    case 0:
                        return '';
                }
        }
        return '';
    }

    function convertNumberToText(int $num): string
    {
        $text = '';
        $index = 1;
        if ($num < 1000) {
            while ($num > 0) {
                $nn = $index == 1 ? $num % 100 : 100;
                $n = $num % 10;
                if ($n != 0 or $nn != 0) {
                    $text =
                        getDigitText($nn < 20 ? $nn : $n, $index) . (($index == 1 or $text == '') ? '' :
                            ' و ') . $text;
                }
                $index += $nn < 20 ? 2 : 1;
                $num = intval($num / ($nn < 20 ? 100 : 10));
            }
        } else {
            while ($num > 0) {
                $n = $num % 1000;
                $numText = convertNumberToText($n);
                $text =
                    ($numText == '' ? '' :
                        ($numText . ' ' . getNumberTextPostfix($index))) . (($numText == '' or $index == 1 or $text == '') ?
                        '' :
                        ' و ') . $text;
                $index++;
                $num = intval($num / 1000);
            }
        }
        return trim($text);
    }
}
