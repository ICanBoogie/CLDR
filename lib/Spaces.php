<?php

namespace ICanBoogie\CLDR;

/**
 * Spaces encoded in UTF-8
 *
 * @see https://www.fileformat.info/info/unicode/category/Zs/list.htm
 */
interface Spaces
{
    public const SPACE                     = "\x20";
    public const NO_BREAK_SPACE            = "\xc2\xa0";
    public const OGHAM_SPACE_MARK          = "\xE1\x9A\x80";
    public const EN_QUAD                   = "\xE2\x80\x80";
    public const EM_QUAD                   = "\xE2\x80\x81";
    public const EN_SPACE                  = "\xE2\x80\x82";
    public const EM_SPACE                  = "\xE2\x80\x83";
    public const THREE_PER_EM_SPACE        = "\xE2\x80\x84";
    public const FOUR_PER_EM_SPACE         = "\xE2\x80\x85";
    public const SIX_PER_EM_SPACE          = "\xE2\x80\x86";
    public const FIGURE_SPACE              = "\xE2\x80\x87";
    public const PUNCTUATION_SPACE         = "\xE2\x80\x88";
    public const THIN_SPACE                = "\xE2\x80\x89";
    public const HAIR_SPACE                = "\xE2\x80\x8A";
    public const NARROW_NO_BREAK_SPACE     = "\xE2\x80\xAF";
    public const MEDIUM_MATHEMATICAL_SPACE = "\xE2\x81\x9F";
    public const IDEOGRAPHIC_SPACE         = "\xE3\x80\x80";
}
