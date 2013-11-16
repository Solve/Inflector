Solve inflector
===============

This class allows you to solve different word-related tasks.

## Methods available at the current version
<pre>
Inflector::transliterate($text, $direction = "en");
Inflector::slugify($text, $slugChar = '-');
Inflector::underscore($text);
Inflector::camelize($text);
Inflector::pluralize($text);
Inflector::singularize($text);
Inflector::priceToStringLong($price, $keepFloat = true, $currency = "uah", $language = null);
Inflector::priceToStringShort($price, $keepFloat = true, $currencyShortLabel = 'грн.', $language = null);
</pre>
For the last two methods you can use a method to define default language:
<pre>
Inflector::setDefaultLanguage($language)
</pre>
