<?php

namespace App;

use Carbon\Carbon;

/**
 * Class Helper
 * @package App
 */
class AppHelper
{
    /**
     * @param $string
     * @return mixed
     */
    public static function removeSpecialCharacters($string) {
        return preg_replace('/[^A-Za-z0-9]/', '', $string);
    }

    /**
     * @param $string
     * @return mixed
     */
    public static function removeAccentuation($string) {

        return preg_replace([
            "/(á|à|ã|â|ä)/",
            "/(Á|À|Ã|Â|Ä)/",
            "/(é|è|ê|ë)/",
            "/(É|È|Ê|Ë)/",
            "/(í|ì|î|ï)/",
            "/(Í|Ì|Î|Ï)/",
            "/(ó|ò|õ|ô|ö)/",
            "/(Ó|Ò|Õ|Ô|Ö)/",
            "/(ú|ù|û|ü)/",
            "/(Ú|Ù|Û|Ü)/",
            "/(ñ)/","/(Ñ)/"
        ], explode(" ","a A e E i I o O u U n N"), $string);
    }

    /**
     * Price formatter
     *
     * @param $value
     * @return string
     */
    public static function formatPrice($value)
    {
        if(strstr($value, '.'))
        {
            $exp = explode('.', $value);

            if(mb_strlen($exp[1]) == 1)
            {
                $decimal = $exp[1] . '0';

            } else {

                $decimal = $exp[1];
            }

            $price = $exp[0] . $decimal;

        } else {
            $price = $value . '00';
        }

        return $price;
    }

    /**
     * Insert blank spaces into string
     *
     * @param $quantity
     * @return string
     */
    public static function insertSpace($quantity)
    {
        $spaces = '';

        for ($i = 0; $i < $quantity; $i++)
        {
            $spaces .= ' ';
        }

        return $spaces;
    }

    /**
     * Remove blank spaces into string
     *
     * @param $value
     * @return string
     */
    public static function removeSpaces($value)
    {
        return trim(str_replace(" ", "", $value));
    }

    /**
     * Insert characters to the left side of string
     *
     * @param $value
     * @param $qtd
     * @param $char
     * @param bool $custom
     * @return string
     */
    public static function insertChar($value, $qtd, $char, $custom = false)
    {
        if (mb_strlen($value) > $qtd)
        {
            return substr($value, 0, $qtd);
        }

        $quantity = $qtd - mb_strlen($value);
        $return = '';

        for ($i = 0; $i < $quantity; $i++)
        {
            $return .= $char;
        }

        if ($custom)
        {
            return $value . $return;
        }

        return $return . $value;
    }

    /**
     * Read array and return this values
     *
     * @param $values
     * @return string $result
     */
    public static function getValues($values)
    {
        return implode('', $values);
    }

    /**
     * Check if is a valid date
     *
     * @param $date
     * @return bool
     */
    public static function isValidDate($date)
    {
        return preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $date);
    }

    /**
     * @param $date
     * @return false|string
     */
    public static function formatDateDB($date)
    {
        return (self::isValidDate($date)) ? $date : date("Y-m-d", strtotime($date));
    }

    /**
     * Return age by date of birth
     *
     * @param $dateBirth
     * @return int|null
     */
    public static function getAgeByDateBirth($dateBirth)
    {
        return (empty($dateBirth)) ? null : date_diff(date_create($dateBirth), date_create('now'))->y;
    }

    /**
     * @param $revenue
     * @return string
     */
    public static function getRevenueCompany($revenue): string
    {
        $revenueString = '';
        switch ($revenue) {
            case ($revenue < 40000):
                $revenueString = 'menos40k';
                break;
            case ($revenue <= 180000):
                $revenueString = '40ka180k';
                break;
            case ($revenue <= 500000):
                $revenueString = '180ka500k';
                break;
            case ($revenue <= 1000000):
                $revenueString = '500ka1m';
                break;
            case ($revenue <= 5000000):
                $revenueString = '1ma5m';
                break;
            case ($revenue <= 10000000):
                $revenueString = '5ma10m';
                break;
            case ($revenue <= 50000000):
                $revenueString = '10ma50m';
                break;
            case ($revenue <= 100000000):
                $revenueString = '50ma100m';
                break;
            case ($revenue > 100000000):
                $revenueString = 'mais100m';
                break;
        }
        return $revenueString;
    }


    /**
     * @param $establishmentDate
     * @return string
     */
    public static function getLifetimeCompany($establishmentDate)
    {
        $lifetime = Carbon::parse($establishmentDate)->diffInMonths(Carbon::now());
        $lifetimeString = '';

        switch ($lifetime) {
            case ($lifetime < 6):
                $lifetimeString = 'menos6m';
                break;
            case ($lifetime <= 12):
                $lifetimeString = '6a12meses';
                break;
            case ($lifetime <= 24):
                $lifetimeString = '1a2anos';
                break;
            case ($lifetime <= 60):
                $lifetimeString = '2a5anos';
                break;
            case ($lifetime <= 120):
                $lifetimeString = '5a10anos';
                break;
            case ($lifetime > 120):
                $lifetimeString = 'mais10anos';
                break;
        }
        return $lifetimeString;
    }
}
