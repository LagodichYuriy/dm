<?php

var_dump(canCreateNote('Have a nice day', 'a dance'));      # true
var_dump(canCreateNote('Have a nice day', 'dicey advice')); # false


/**
 * @param string      $string
 * @param string      $search
 * @param bool        $case_sensitive
 * @param null|string $charlist You can use this parameter to extend the language support of this function.
 *                              By default, it perfectly works with English, however you are still able to
 *                              use it with any other language as well. What you need is to pass a string
 *                              of characters, which make words in this languge. It's necessary to separate
 *                              word chars from punctuation chars (such as Spanish inverted exclamation mark
 *                              or Chiniese big dot).
 *
 *                              Greek value sample: '1234567890-ΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩαάβγδεζηθικλμνξοπρστυφχψω'
 *
 * @return bool
 */
function canCreateNote(string $string, string $search, $case_sensitive = true, $charlist = null): bool
{
	$string_chars = parseChars($string, $case_sensitive, $charlist);
	$search_chars = parseChars($search, $case_sensitive, $charlist);

	foreach ($search_chars as $search_char => $quantity)
	{
		if (!isset($string_chars[$search_char]) or $string_chars[$search_char] < $quantity)
		{
			return false;
		}
	}

	return true;
}


/**
 * A bonus: the same task, but now we can use words instead of letters
 *
 * @param string      $string
 * @param string      $search
 * @param bool        $case_sensitive
 * @param null|string $charlist
 *
 * @return bool
 */
function canCreateNoteWithWords(string $string, string $search, $case_sensitive = true, $charlist = null): bool
{
	$string_words = parseWords($string, $case_sensitive, $charlist);
	$search_words = parseWords($search, $case_sensitive, $charlist);

	foreach ($search_words as $search_word => $quantity)
	{
		if (!isset($string_words[$search_word]) or $string_words[$search_word] < $quantity)
		{
			return false;
		}
	}

	return true;
}

function parseChars($string, $case_sensitive = true, $charlist = null): array
{
	if (!$case_sensitive)
	{
		$string = mb_strtolower($string);
	}

	$words = str_word_count($string, 1, $charlist);
	$words = implode('', $words);
	$chars = toChars($words);
	$chars = array_count_values($chars);

	return $chars;
}

function parseWords($string, $case_sensitive = true, $charlist = null): array
{
	if (!$case_sensitive)
	{
		$string = mb_strtolower($string);
	}

	$words = str_word_count($string, 1, $charlist);
	$words = array_count_values($words);

	return $words;
}

function toChars(string $string): array
{
	# "u (PCRE8) This modifier turns on additional functionality of PCRE that is incompatible with Perl.
	# Pattern strings are treated as UTF-8. This modifier is available from PHP 4.1.0 or greater on Unix
	# and from PHP 4.2.3 on win32. UTF-8 validity of the pattern is checked since PHP 4.3.5."
	#
	# ...also, it works much faster than built-in PHP functions

	return preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);
}