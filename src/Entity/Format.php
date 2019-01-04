<?php
/**
 * Created by PhpStorm.
 * User: samue
 * Date: 09.05.2017
 * Time: 15:08
 */

namespace Dibukeu\Entity;

class Format
{
    const ACS_EPUB = 1;
    const ACS_PDF = 2;
    const EPUB = 3;
    const PDF = 4;
    const MOBI = 5;
    const SDRM_EPUB = 6;
    const SDRM_PDF = 7;
    const SDRM_MOBI = 8;
    const MP3 = 9;

    private $formats = array(
        self::EPUB => array(
            'title' => 'EPUB',
            'code' => 'epub',
            'mimetype' => 'application/epub+zip',
        ), //without protection
        self::PDF => array(
            'title' => 'PDF',
            'code' => 'pdf',
            'mimetype' => 'application/pdf',
        ), //without protection
        self::MOBI => array(
            'title' => 'MOBI',
            'code' => 'mobi',
            'mimetype' => 'application/x-mobipocket-ebook',
        ),  //without protection
        self::ACS_EPUB => array(
            'title' => 'EPUB (Adobe DRM)',
            'code' => 'acs_epub',
            'mimetype' => 'application/epub+zip',
        ), //adobe DRM
        self::ACS_PDF => array(
            'title' => 'PDF (Adobe DRM)',
            'code' => 'acs_pdf',
            'mimetype' => 'application/pdf',
        ), //adobe DRM
        self::SDRM_EPUB => array('title' => 'EPUB', 'code' => 'social_epub', 'mimetype' => 'application/epub+zip'),
        self::SDRM_PDF => array('title' => 'PDF', 'code' => 'social_pdf', 'mimetype' => 'application/pdf'),
        self::SDRM_MOBI => array('title' => 'MOBI', 'code' => 'social_mobi', 'mimetype' => 'application/x-mobipocket-ebook'),
        self::MP3 => array('title' => 'MP3', 'code' => 'mp3', 'mimetype' => 'audio/mpeg')
    );

    public function __construct()
    {

    }

    /**
     * Vracia zoznam format by "code", ID pouzivame len interne
     *
     * @return array
     */
    public function getAllFormats()
    {
        $formats = array_combine(array_column($this->formats, 'code'), $this->formats);
        if (is_bool($formats)) {
            throw new \InvalidArgumentException('Invalid formats');
        }
        return $formats;
    }

    /**
     * Internal
     */
    public function getFormatCode($format_id)
    {
        if (isset($this->formats[$format_id])) {
            return $this->formats[$format_id]['code'];
        }

        throw new \Exception('Format ' . $format_id . ' not found');
    }


}
