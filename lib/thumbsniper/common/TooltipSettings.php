<?php

/*
 * Copyright (C) 2016  Thomas Schulte <thomas@cupracer.de>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

namespace ThumbSniper\common;


abstract class TooltipSettings
{
    /** @var string */
    static private $jqueryUrl = "https://code.jquery.com/jquery-1.12.0.min.js";
    /** @var string */
    static private $qtipCssUrl = "https://cdn.jsdelivr.net/qtip2/2.2.1/jquery.qtip.min.css";
    /** @var string */
    static private $imagesLoadedUrl = "https://unpkg.com/imagesloaded@4.1/imagesloaded.pkgd.min.js";
    /** @var string */
    static private $qtipUrl = "https://cdn.jsdelivr.net/qtip2/2.2.1/jquery.qtip.js";


    /** @var string */
    static private $apiHost = "api.thumbsniper.com";
    /** @var string */
    static private $apiVersion = "v3";
    /** @var string */
    static private $preview = "all";
    /** @var integer */
    static private $width = "182";
    /** @var string */
    static private $effect = "plain";
    /** @var string */
    static private $siteUrl;
    /** @var array */
    static private $excludes = array();
    /** @var string */
    static private $position = 'top';
    /** @var string */
    static private $style = 'jtools';
    // @var boolean */
    static private $showTitle = false;


    /**
     * @return string
     */
    public static function getApiHost()
    {
        return self::$apiHost;
    }

    /**
     * @param string $apiHost
     */
    public static function setApiHost($apiHost)
    {
        self::$apiHost = $apiHost;
    }

    /**
     * @return string
     */
    public static function getApiVersion()
    {
        return self::$apiVersion;
    }

    /**
     * @param string $apiVersion
     */
    public static function setApiVersion($apiVersion)
    {
        self::$apiVersion = $apiVersion;
    }

    /**
     * @return string
     */
    public static function getPreview()
    {
        return self::$preview;
    }

    /**
     * @param string $preview
     */
    public static function setPreview($preview)
    {
        self::$preview = $preview;
    }

    /**
     * @return int
     */
    public static function getWidth()
    {
        return self::$width;
    }

    /**
     * @param int $width
     */
    public static function setWidth($width)
    {
        self::$width = $width;
    }

    /**
     * @return string
     */
    public static function getEffect()
    {
        return self::$effect;
    }

    /**
     * @param string $effect
     */
    public static function setEffect($effect)
    {
        self::$effect = $effect;
    }

    /**
     * @return string
     */
    public static function getSiteUrl()
    {
        return self::$siteUrl;
    }

    /**
     * @param string $siteUrl
     */
    public static function setSiteUrl($siteUrl)
    {
        self::$siteUrl = $siteUrl;
    }

    /**
     * @return array
     */
    public static function getExcludes()
    {
        return self::$excludes;
    }

    /**
     * @param array $excludes
     */
    public static function setExcludes($excludes)
    {
        self::$excludes = $excludes;
    }

    /**
     * @return string
     */
    public static function getQtipCssUrl()
    {
        return self::$qtipCssUrl;
    }

    /**
     * @param string $qtipCssUrl
     */
    public static function setQtipCssUrl($qtipCssUrl)
    {
        self::$qtipCssUrl = $qtipCssUrl;
    }

    /**
     * @return string
     */
    public static function getImagesLoadedUrl()
    {
        return self::$imagesLoadedUrl;
    }

    /**
     * @param string $imagesLoadedUrl
     */
    public static function setImagesLoadedUrl($imagesLoadedUrl)
    {
        self::$imagesLoadedUrl = $imagesLoadedUrl;
    }

    /**
     * @return string
     */
    public static function getQtipUrl()
    {
        return self::$qtipUrl;
    }

    /**
     * @param string $qtipUrl
     */
    public static function setQtipUrl($qtipUrl)
    {
        self::$qtipUrl = $qtipUrl;
    }

    /**
     * @return mixed
     */
    public static function getJqueryUrl()
    {
        return self::$jqueryUrl;
    }

    /**
     * @param mixed $jqueryUrl
     */
    public static function setJqueryUrl($jqueryUrl)
    {
        self::$jqueryUrl = $jqueryUrl;
    }

    /**
     * @return string
     */
    public static function getPosition()
    {
        return self::$position;
    }

    /**
     * @param string $position
     */
    public static function setPosition($position)
    {
        self::$position = $position;
    }

    /**
     * @return string
     */
    public static function getStyle()
    {
        return self::$style;
    }

    /**
     * @param string $style
     */
    public static function setStyle($style)
    {
        self::$style = $style;
    }

    /**
     * @return boolean
     */
    public static function isShowTitle()
    {
        return self::$showTitle;
    }

    /**
     * @param boolean $showTitle
     */
    public static function setShowTitle($showTitle)
    {
        self::$showTitle = $showTitle;
    }
}