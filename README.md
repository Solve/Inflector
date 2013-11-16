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
Inflector::priceToStringShort($price, $keepFloat = true, $currencySign = 'грн.', $language = null);
</pre>