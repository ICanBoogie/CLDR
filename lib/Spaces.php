<?php

namespace ICanBoogie\CLDR;

/**
 * Spaces encoded in UTF-8
 *
 * @see https://www.fileformat.info/info/unicode/category/Zs/list.htm
 */
interface Spaces
{
    const SPACE                     = "\x20";
    const NO_BREAK_SPACE            = "\xc2\xa0";
    const OGHAM_SPACE_MARK          = "\xE1\x9A\x80";
    const EN_QUAD                   = "\xE2\x80\x80";
    const EM_QUAD                   = "\xE2\x80\x81";
    const EN_SPACE                  = "\xE2\x80\x82";
    const EM_SPACE                  = "\xE2\x80\x83";
    const THREE_PER_EM_SPACE        = "\xE2\x80\x84";
    const FOUR_PER_EM_SPACE         = "\xE2\x80\x85";
    const SIX_PER_EM_SPACE          = "\xE2\x80\x86";
    const FIGURE_SPACE              = "\xE2\x80\x87";
    const PUNCTUATION_SPACE         = "\xE2\x80\x88";
    const THIN_SPACE                = "\xE2\x80\x89";
    const HAIR_SPACE                = "\xE2\x80\x8A";
    const NARROW_NO_BREAK_SPACE     = "\xE2\x80\xAF";
    const MEDIUM_MATHEMATICAL_SPACE = "\xE2\x81\x9F";
    const IDEOGRAPHIC_SPACE         = "\xE3\x80\x80";
}
