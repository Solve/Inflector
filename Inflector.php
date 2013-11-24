<?php
/*
 * This file is a part of Solve framework.
 *
 * @author Alexandr Viniychuk <alexandr.viniychuk@icloud.com>
 * @copyright 2009-2014, Alexandr Viniychuk
 * created: 16.11.13 13:39
 */


namespace Solve\Utils;

/**
 * Class Inflector
 * @package Solve\Inflector
 *
 * Class Inflector is used to operate with
 * It also supports russian and ukrainian language
 *
 * @version 1.1
 * @author Alexandr Viniychuk <alexandr.viniychuk@icloud.com>
 */
class Inflector {

    /**
     * Cyrillic letters
     */
    private static $_cyrillicTranslit   = array(
        "Щ",  "Ш", "Ч", "Ц","Ю", "Я", "Ж", "А","Б","В","Г","Д","Е", "Ё", "Ж", "З","И","Й","I","Ї", "Є", "Ґ", "К","Л","М","Н","О","П","Р","С","Т","У","Ф","Х", "Ъ","Ы","Ь","Э","Ю" ,"Я",
        "щ",  "ш", "ч", "ц","ю", "я", "ж", "а","б","в","г","д","е", "ё", "ж", "з","и","й","i","ї", "є", "ґ", "к","л","м","н","о","п","р","с","т","у","ф","х", "ъ","ы","ь","э","ю" ,"я"
    );

    /**
     * Latin letters for transliteration
     */
    private static $_latinTranslit      = array(
        "Sch","Sh","Ch","C","Ju","Ja","Zh","A","B","V","G","D","Je","Jo","Zh","Z","I","J","I","Ji","Je","G","K","L","M","N","O","P","R","S","T","U","F","Kh","'","Y","`","E","Je","Ji",
        "sch","sh","ch","c","ju","ja","zh","a","b","v","g","d","je","jo","zh","z","i","j","i","ji","je","g","k","l","m","n","o","p","r","s","t","u","f","kh","'","y","`","e","je","ji"
    );

    private static $_numbersScript     = array(
        'ua'    => array(
            'forms' => array(
                '0'     => 'нуль',
                '100'   => array('','сто','двісті','триста','чотириста','п\'ятсот','шістсот', 'сімсот', 'вісімсот','дев\'ятьсот'),
                '11'    => array('','десять','одинадцять','дванадцять','тринадцять', 'чотирнадцять','п\'ятнадцять','шістнадцять','сімнадцять', 'вісімнадцять','дев\'ятнадцять','двадцять'),
                '10'    => array('','десять','двадцять','тридцять','сорок','п\'ятьдесят', 'шістдесят','сімдесят','вісімдесят','дев\'яносто')
            ),
            'segments'  => array(
                array('тисяча', 'тисячи', 'тисяч', 1),
                array('мильйон', 'мильйона', 'мильйонів',  0),
                array('мильйард', 'мильйарда', 'мильйардів',  0),
                array('триліон', 'триліона', 'триліона',  0)
            ),
            'sex'       => array(
                array('','один','два','три','чотири','п\'ять','шість','сім', 'вісем','дев\'ять'),// m
                array('','одна','дві','три','чотири','п\'ять','шість','сім', 'вісем','дев\'ять') // f
            ),
            'sign'      => array(
                "uah"   => array(
                    array('копійка', 'копійки', 'копійок', 1),
                    array('гривна', 'гривні', 'гривень', 0),
                ),
                "rur"   => array(
                    array('копійка', 'копійки', 'копійок', 1),
                    array('рубль', 'рубля', 'рублів', 0),
                )
            )
        ),
        'ru'    => array(
            'forms' => array(
                '0'     => 'ноль',
                '100'   => array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот', 'восемьсот','девятьсот'),
                '11'    => array('','десять','одиннадцать','двенадцать','тринадцать', 'четырнадцать','пятнадцать','шестнадцать','семнадцать', 'восемнадцать','девятнадцать','двадцать'),
                '10'    => array('','десять','двадцать','тридцать','сорок','пятьдесят', 'шестьдесят','семьдесят','восемьдесят','девяносто')
            ),
            'segments'  => array(
                array('тысяча', 'тысячи', 'тысяч', 1),
                array('миллион', 'миллиона', 'миллионов',  0),
                array('миллиард', 'миллиарда', 'миллиардов',  0),
                array('триллион', 'триллиона', 'триллионов',  0)
            ),
            'sex'       => array(
                array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),// m
                array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять') // f
            ),
            'sign'      => array(
                "uah"   => array(
                    array('копейка', 'копейки', 'копеек', 1),
                    array('гривна', 'гривни', 'гривен', 0),
                ),
                "rur"   => array(
                    array('копейка', 'копейки', 'копеек', 1),
                    array('рубль', 'рубля', 'рублей', 0),
                )
            )

        )
    );

    private static $_defaultLanguage    = "ru";

    /**
     * @param string $defaultLanguage
     */
    public static function setDefaultLanguage($defaultLanguage) {
        self::$_defaultLanguage = $defaultLanguage;
    }

    /**
     * Do text transliteration
     *
     * @static
     * @param string $text Text to transliteration
     * @param string $direction "en" or "ru"
     * @return string transliterated text
     */
    public static function transliterate($text, $direction = "en") {
        if ($direction == "en") {
            $text = str_replace(self::$_cyrillicTranslit, self::$_latinTranslit, $text);
        } else {
            $text = str_replace(self::$_latinTranslit, self::$_cyrillicTranslit, $text);
        }

        $text = preg_replace("#([qwrtpsdfghklzxcvbnmQWRTPSDFGHKLZXCVBNM]+)[jJ]e#", "\${1}e", $text);
        $text = preg_replace("#([qwrtpsdfghklzxcvbnmQWRTPSDFGHKLZXCVBNM]+)[jJ]#", "\${1}'", $text);
        $text = preg_replace("#([ eyuioaEYUIOA-]+)[Kk]h#", "\${1}h", $text);
        $text = preg_replace("#^kh#", "h", $text);
        $text = preg_replace("#^Kh#", "H", $text);

        return $text;
    }

    /**
     * Convert $text to string acceptable for URL using
     * @static
     * @param string $text
     * @param string $slugChar identify the slugify character
     * @return string
     */
    public static function slugify($text, $slugChar = '-') {
        $text = self::transliterate($text);
        $text = preg_replace('~[^\\pL\d\s]+~u', $slugChar, $text);

        $text = trim($text, $slugChar);
        $text = strtolower($text);

        $text = preg_replace('~[^A-z0-9]+~', $slugChar, $text);
        $text = preg_replace('#' . $slugChar . '+#',$slugChar,$text);
        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }

    /**
     * Convert to underscored text
     *
     * @param $text
     * @return string
     */
    public static function underscore($text) {
        return self::slugify($text, '_');
    }

    /**
     * Camelize the text
     * @param $text
     * @return string
     */
    public static function camelize($text) {
        $text = explode(' ', str_replace(array('_', '/', '-'), ' ', $text));

        for ($i = 0; $i < count($text); $i++) {
            $text[$i] = ucfirst($text[$i]);
        }

        $text = implode('', $text);
        return $text;
    }

    /**
     * Pluralize word
     *
     * @static
     * @param string $word
     * @return string pluralize type of word
     */
    public static function pluralize($word) {
        $corePluralRules = array(
            '/(s)tatus$/i' => '\1\2tatuses',
            '/(quiz)$/i' => '\1zes',
            '/^(ox)$/i' => '\1\2en',
            '/([m|l])ouse$/i' => '\1ice',
            '/(matr|vert|ind)(ix|ex)$/i'  => '\1ices',
            '/(x|ch|ss|sh)$/i' => '\1es',
            '/([^aeiouy]|qu)y$/i' => '\1ies',
            '/(hive)$/i' => '\1s',
            '/(?:([^f])fe|([lr])f)$/i' => '\1\2ves',
            '/sis$/i' => 'ses',
            '/([ti])um$/i' => '\1a',
            '/(p)erson$/i' => '\1eople',
            '/(m)an$/i' => '\1en',
            '/(c)hild$/i' => '\1hildren',
            '/(buffal|tomat)o$/i' => '\1\2oes',
            '/(alumn|bacill|cact|foc|fung|nucle|radi|stimul|syllab|termin|vir)us$/i' => '\1i',
            '/us$/' => 'uses',
            '/(alias)$/i' => '\1es',
            '/(ax|cri|test)is$/i' => '\1es',
            '/s$/' => 's',
            '/^$/' => '',
            '/$/' => 's');

        $coreUninflectedPlural = array(
            '.*[nrlm]ese', '.*deer', '.*fish', '.*measles', '.*ois', '.*pox', '.*sheep', 'Amoyese',
            'bison', 'Borghese', 'bream', 'breeches', 'britches', 'buffalo', 'cantus', 'carp', 'chassis', 'clippers',
            'cod', 'coitus', 'Congoese', 'contretemps', 'corps', 'debris', 'diabetes', 'djinn', 'eland', 'elk',
            'equipment', 'Faroese', 'flounder', 'Foochowese', 'gallows', 'Genevese', 'Genoese', 'Gilbertese', 'graffiti',
            'headquarters', 'herpes', 'hijinks', 'Hottentotese', 'information', 'innings', 'jackanapes', 'Kiplingese',
            'Kongoese', 'Lucchese', 'mackerel', 'Maltese', 'media', 'mews', 'moose', 'mumps', 'Nankingese', 'news',
            'nexus', 'Niasese', 'Pekingese', 'People', 'Piedmontese', 'pincers', 'Pistoiese', 'pliers', 'Portuguese', 'proceedings',
            'rabies', 'rice', 'rhinoceros', 'salmon', 'Sarawakese', 'scissors', 'sea[- ]bass', 'series', 'Shavese', 'shears',
            'siemens', 'species', 'swine', 'testes', 'trousers', 'trout', 'tuna', 'Vermontese', 'Wenchowese',
            'whiting', 'wildebeest', 'Yengeese');

        $coreIrregularPlural = array(
            'atlas' => 'atlases',
            'beef' => 'beefs',
            'brother' => 'brothers',
            'child' => 'children',
            'corpus' => 'corpuses',
            'cow' => 'cows',
            'ganglion' => 'ganglions',
            'genie' => 'genies',
            'genus' => 'genera',
            'graffito' => 'graffiti',
            'hoof' => 'hoofs',
            'loaf' => 'loaves',
            'man' => 'men',
            'money' => 'monies',
            'mongoose' => 'mongooses',
            'move' => 'moves',
            'mythos' => 'mythoi',
            'numen' => 'numina',
            'occiput' => 'occiputs',
            'octopus' => 'octopuses',
            'opus' => 'opuses',
            'ox' => 'oxen',
            'penis' => 'penises',
            'person' => 'people',
            'sex' => 'sexes',
            'soliloquy' => 'soliloquies',
            'testis' => 'testes',
            'trilby' => 'trilbys',
            'turf' => 'turfs');

        $regexUninflected = '(?:' . (join( '|', $coreUninflectedPlural)) . ')';
        $regexIrregular = '(?:' . (join( '|', array_keys($coreIrregularPlural))) . ')';
        $pluralRules['regexUninflected'] = $regexUninflected;
        $pluralRules['regexIrregular'] = $regexIrregular;

        $regs = array();

        if (preg_match('/(.*)\\b(' . $regexIrregular . ')$/i', $word, $regs)) {
            return $regs[1] . substr($word, 0, 1) . substr($coreIrregularPlural[strtolower($regs[2])], 1);
        }
        if (preg_match('/^(' . $regexUninflected . ')$/i', $word, $regs)) {
            return $word;
        }
        foreach ($corePluralRules as $rule => $replacement) {
            if (preg_match($rule, $word)) {
                return preg_replace($rule, $replacement, $word);
            }
        }
        return $word;
    }

    /**
     * Singularize word
     *
     * @static
     * @param string $word
     * @return string singular type of word
     */
    public static function singularize($word) {
        $coreSingularRules =
            array(
                '/(s)tatuses$/i' => '\1\2tatus',
                '/(quiz)zes$/i' => '\\1',
                '/(matr)ices$/i' => '\1ix',
                '/(vert|ind)ices$/i' => '\1ex',
                '/^(ox)en/i' => '\1',
                '/(alias)(es)*$/i' => '\1',
                '/([octop|vir])i$/i' => '\1us',
                '/(cris|ax|test)es$/i' => '\1is',
                '/(shoe)s$/i' => '\1',
                '/(o)es$/i' => '\1',
                '/ouses$/' => 'ouse',
                '/uses$/' => 'us',
                '/([m|l])ice$/i' => '\1ouse',
                '/(x|ch|ss|sh)es$/i' => '\1',
                '/(m)ovies$/i' => '\1\2ovie',
                '/(s)eries$/i' => '\1\2eries',
                '/([^aeiouy]|qu)ies$/i' => '\1y',
                '/([lr])ves$/i' => '\1f',
                '/(tive)s$/i' => '\1',
                '/(hive)s$/i' => '\1',
                '/(drive)s$/i' => '\1',
                '/([^f])ves$/i' => '\1fe',
                '/(^analy)ses$/i' => '\1sis',
                '/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i' => '\1\2sis',
                '/([ti])a$/i' => '\1um',
                '/(p)eople$/i' => '\1\2erson',
                '/(m)en$/i' => '\1an',
                '/(c)hildren$/i' => '\1\2hild',
                '/(n)ews$/i' => '\1\2ews',
                '/s$/i' => '');

        $coreUninflectedSingular = array(
            '.*[nrlm]ese', '.*deer', '.*fish', '.*measles', '.*ois', '.*pox', '.*sheep', '.*us', '.*ss', 'Amoyese',
            'bison', 'Borghese', 'bream', 'breeches', 'britches', 'buffalo', 'cantus', 'carp', 'chassis', 'clippers',
            'cod', 'coitus', 'Congoese', 'contretemps', 'corps', 'debris', 'diabetes', 'djinn', 'eland', 'elk',
            'equipment', 'Faroese', 'flounder', 'Foochowese', 'gallows', 'Genevese', 'Genoese', 'Gilbertese', 'graffiti',
            'headquarters', 'herpes', 'hijinks', 'Hottentotese', 'information', 'innings', 'jackanapes', 'Kiplingese',
            'Kongoese', 'Lucchese', 'mackerel', 'Maltese', 'media', 'mews', 'moose', 'mumps', 'Nankingese', 'news',
            'nexus', 'Niasese', 'Pekingese', 'Piedmontese', 'pincers', 'Pistoiese', 'pliers', 'Portuguese', 'proceedings',
            'rabies', 'rice', 'rhinoceros', 'salmon', 'Sarawakese', 'scissors', 'sea[- ]bass', 'series', 'Shavese', 'shears',
            'siemens', 'species', 'swine', 'testes', 'trousers', 'trout', 'tuna', 'Vermontese', 'Wenchowese',
            'whiting', 'wildebeest', 'Yengeese',);

        $coreIrregularSingular = array(
            'atlases' => 'atlas',
            'beefs' => 'beef',
            'brothers' => 'brother',
            'children' => 'child',
            'corpuses' => 'corpus',
            'cows' => 'cow',
            'ganglions' => 'ganglion',
            'genies' => 'genie',
            'genera' => 'genus',
            'graffiti' => 'graffito',
            'hoofs' => 'hoof',
            'loaves' => 'loaf',
            'men' => 'man',
            'menus' => 'menu',
            'monies' => 'money',
            'mongooses' => 'mongoose',
            'moves' => 'move',
            'mythoi' => 'mythos',
            'numina' => 'numen',
            'occiputs' => 'occiput',
            'octopuses' => 'octopus',
            'opuses' => 'opus',
            'oxen' => 'ox',
            'penises' => 'penis',
            'people' => 'person',
            'sexes' => 'sex',
            'soliloquies' => 'soliloquy',
            'testes' => 'testis',
            'trilbys' => 'trilby',
            'turfs' => 'turf',);

        $regexUninflected =  '(?:' . (join( '|', $coreUninflectedSingular)) . ')';
        $regexIrregular =  '(?:' . (join( '|', array_keys($coreIrregularSingular))) . ')';
        $SingularRules['regexUninflected'] = $regexUninflected;
        $SingularRules['regexIrregular'] = $regexIrregular;

        $regs = array();
        if (preg_match('/(.*)\\b(' . $regexIrregular . ')$/i', $word, $regs)) {
            return $regs[1] . substr($word, 0, 1) . substr($coreIrregularSingular[strtolower($regs[2])], 1);
        }
        if (preg_match('/^(' . $regexUninflected . ')$/i', $word, $regs)) {
            return $word;
        }
        foreach ($coreSingularRules as $rule => $replacement) {
            if (preg_match($rule, $word)) {
                return preg_replace($rule, $replacement, $word);
            }
        }
    }

    /**
     * Convert price to string and add localized money label
     * @param $price
     * @param bool $keepFloat
     * @param string $currency
     * @param string $language by default overwritten by Inflector's default language
     * @return mixed
     */
    public static function priceToStringLong($price, $keepFloat = true, $currency = "uah", $language = null) {
        if ($language == null) $language = self::$_defaultLanguage;
        if (empty(self::$_numbersScript[$language]) || empty(self::$_numbersScript[$language]['sign'][$currency])) {
            trigger_error('You do not have a "'.$language.'/'.$currency.'" version for price labels. Add it via Inflector::addNumberLocalization');
        }

        $segments = array_merge(self::$_numbersScript[$language]['sign'][$currency], self::$_numbersScript[$language]['segments']);

        return self::_numberToString($price, self::$_numbersScript[$language]['forms'], self::$_numbersScript[$language]['sex'], $segments, $keepFloat);
    }

    /**
     * Provide a short version of price converted to string
     * @param $price
     * @param bool $keepFloat
     * @param string $currencyShortLabel
     * @param string $language
     * @return mixed
     */
    public static function priceToStringShort($price, $keepFloat = true, $currencyShortLabel = 'грн.', $language = null) {
        if ($language == null) $language = self::$_defaultLanguage;

        $segments = array_merge(array(
                array('коп.', 'коп.', 'коп.', 1),
                array($currencyShortLabel, $currencyShortLabel, $currencyShortLabel, 0)
            ),
            self::$_numbersScript[$language]['segments']
        );

        return self::_numberToString($price, self::$_numbersScript[$language]['forms'], self::$_numbersScript[$language]['sex'], $segments, $keepFloat);
    }

    /**
     * Return PHP-style variable dump
     * @param $variable
     * @param int $indentLevel
     * @return string
     */
    public static function dumpAsString($variable, $indentLevel = 0) {
        if (is_bool($variable)) {
            $result = $variable ? "true" : "false";
        } elseif (is_null($variable)) {
            $result = "null";
        } elseif (is_array($variable)) {
            $result = 'array (';

            foreach($variable as $key=>$item) {
                $result .= "\n". str_repeat(" ", ($indentLevel+1)*4);
                $result .= self::dumpAsString($key, $indentLevel+1);
                $result .= ' => ';
                $result .= self::dumpAsString($item, $indentLevel+1).',';
            }

            $result .= "\n".str_repeat(" ", ($indentLevel)*4).')';
        } elseif(is_string($variable) && (isset($variable[0]) && $variable[0] != '$')) {
            $result = '"'. (strpos($variable, '$__lv') === false ? str_replace('"', '\"', $variable) : $variable) .'"';
        } else {
            $result = $variable;
        }

        return $result;
    }

    /**
     * Using for beautify output
     * @param $variable
     * @param string $spacing
     * @return string
     */
    public static function dumperGet(&$variable, $spacing = "") {
        if (is_array($variable)) {
            $type = "Array[" . count( $variable ) . "]";
        } elseif( is_object( $variable ) ) {
            ob_start();
            print_r($variable);
            return ob_get_clean();

        } elseif( gettype( $variable ) == "boolean" ) {
            return $variable ? "true" : "false";
        } elseif( is_null( $variable ) ) {
            return "NULL";
        } else {
            ob_start();
            var_dump($variable);
            return ob_get_clean();
        }
        $buf = $type;
        $spacing .= "    ";
        for (reset( $variable ); list ( $k, $v ) = each( $variable );) {
            if ($k === "GLOBALS" )
                continue;
            $buf .= "\n".$spacing.'['.$k.'] => ' . self::dumperGet( $v, $spacing);
        }

        return $buf;
    }

    /**
     * For internal use.
     * @param $price
     * @param $numbersScript
     * @param $sex
     * @param $forms
     * @param $saveFloat
     * @return mixed
     */
    private static function _numberToString($price, $numbersScript, $sex, $forms, $saveFloat) {
        $result             = array();
        $tmp                = explode('.', str_replace(',','.', $price));

        $integerPart        = number_format($tmp[0], 0, '','-');
        if ($integerPart == 0) {
            $result[] = $numbersScript['0'];
        }

        $fractionalPart     = isset($tmp[1]) ? substr(str_pad($tmp[1], 2, '0', STR_PAD_RIGHT), 0, 2) : '00';
        $segments           = explode('-', $integerPart);
        $offset             = sizeof($segments);

        if ((int)$integerPart == 0) {
            $result[] = $numbersScript['0'];
            $result[] = self::_getMorphed(0, $forms[1][0], $forms[1][1], $forms[1][2]);
        } else {
            foreach ($segments as $segment) {
                $currentSex = (int) $forms[$offset][3];
                $ri         = (int) $segment;
                if ($ri== 0 && $offset>1) {
                    $offset--;
                    continue;
                }

                $ri         = str_pad($ri, 3, '0', STR_PAD_LEFT);

                $r1         = (int)substr($ri, 0, 1);
                $r2         = (int)substr($ri, 1, 1);
                $r3         = (int)substr($ri, 2, 1);
                $r22        = (int)$r2.$r3;

                if ($ri > 99) {
                    $result[] = $numbersScript[100][$r1];
                }
                if ($r22 > 20) {
                    $result[] = $numbersScript[10][$r2];
                    $result[] = $sex[$currentSex][$r3];
                } elseif ($r22 > 9) {
                    $result[] = $numbersScript[11][$r22-9];
                } elseif ($r22 > 0) {
                    $result[] = $sex[$currentSex][$r3];
                }

                $result[]   = self::_getMorphed($ri, $forms[$offset][0], $forms[$offset][1], $forms[$offset][2]);
                $offset--;
            }
        }

        if ($saveFloat) {
            $result[] = $fractionalPart;
            $result[] = self::_getMorphed($fractionalPart, $forms[0][0], $forms[0][1], $forms[0][2]);
        }
        return preg_replace("#\s{2,}#", ' ', implode(' ',$result));
    }

    /**
     * For internal use.
     * Morphing words.
     * @static
     * @param $n
     * @param $f1
     * @param $f2
     * @param $f5
     * @return mixed
     */
    private static function _getMorphed($n, $f1, $f2, $f5) {
        $n  = abs($n) % 100;
        $n1 = $n % 10;
        if ($n > 10 && $n < 20) return $f5;
        if ($n1 > 1 && $n1 < 5) return $f2;
        if ($n1 == 1) return $f1;

        return $f5;
    }



} 