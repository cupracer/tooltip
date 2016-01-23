<?php

/*
 * Copyright (C) 2015  Thomas Schulte <thomas@cupracer.de>
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

namespace ThumbSniper\tooltip;

use ThumbSniper\common\TooltipSettings;

class Tooltip
{
	function __construct() {
	}


    public function getJqueryJavaScriptHtmlTag() {
        return '<script type="text/javascript" src="' . TooltipSettings::getJqueryUrl() . '"></script>';
    }


    public function getQtipJavaScriptHtmlTags() {
        return '<script type="text/javascript" src="' . TooltipSettings::getImagesLoadedUrl() . '"></script>
                <script type="text/javascript" src="' . TooltipSettings::getQtipUrl() . '"></script>';
    }


    public function getQtipCssHtmlTag() {
        return '<link rel="stylesheet" href="' . TooltipSettings::getQtipCssUrl() . '" type="text/css" />';
    }


	public function getInlineScripts() {
		$out = "\n<!-- ThumbSniper scripts - start -->\n";

		$out.= $this->getFunctionsCode();
		$out.= $this->getLinkPreparationCode();
		$out.= $this->getQtipCode();

		$out.= "\n<!-- ThumbSniper scripts - end -->\n";

		return $out;
	}


	private function getFunctionsCode() {

		/* Only accept commonly trusted protocols:
		*  Only data-image URLs are accepted, Exotic flavours (escaped slash,
		*  html-entitied characters) are not supported to keep the function fast.
		*  Found here: http://stackoverflow.com/a/7544757
		*/

		$out = '<script type="text/javascript">
			function thumbsniper_rel_to_abs(url){
				if(/^.*\#$/.test(url)) {
					return ""; //only anchor = return nothing
				}

				var qMark = String(url).search(/\?/);
				if(qMark != -1) {
					url = String(url).substring(0, qMark);
				}

				if(/^(https?|file|ftps?|mailto|javascript|data:image\/[^;]{2,9};):/i.test(url)) {
					return url; //Url is already absolute
				}

				var base_url = location.href.match(/^(.+)\/?(?:#.+)?$/)[0]+"/";
				if(url.substring(0,2) == "//") {
					return location.protocol + url;
				}else if(url.charAt(0) == "/") {
					return location.protocol + "//" + location.host + url;
				}else if(url.substring(0,2) == "./") {
					url = "." + url;
				}else if(/^\s*$/.test(url)) {
					return ""; //Empty = Return nothing
				}else {
					url = "../" + url;
				}

				url = base_url + url;
				//var i=0;
				while(/\/\.\.\//.test(url = url.replace(/[^\/]+\/+\.\.\//g,"")));

				/* Escape certain characters to prevent XSS */
				url = url.replace(/\.$/,"").replace(/\/\./g,"").replace(/"/g,"%22").replace(/\'/g,"%27").replace(/</g,"%3C").replace(/>/g,"%3E");

				return url;
			}</script>' . "\n";

//		$out.= '<script type="text/javascript">
//			function hasClass(element, cls) {
//				return (" " + element.className + " ").indexOf(" " + cls + " ") > -1;
//			}</script>' . "\n";

		return $out;
	}


	private function getLinkPreparationCode() {
        // using --> (function() { ' . ''; <-- is a workaround for PHPStorm code validation
		$out = '<script type="application/javascript">
            jQuery(document).ready(function() { ' . '';

		if(TooltipSettings::getPreview() == "all" || TooltipSettings::getPreview() == "external") {
			$out.= 'for (var i = 0; i < document.links.length; ++i) {
							var link = document.links[i];
							var blogurl = "' . TooltipSettings::getSiteUrl() . '";
							var linkurl = String(link.href).substring(0, blogurl.length);
							var linkproto = String(link.href).substring(0, 4);';

			$out.= 'if(linkproto == "http" || linkproto == "https") {';
			$out.= 'var current_link = thumbsniper_rel_to_abs(link.href);';

			if(is_array(TooltipSettings::getExcludes()) && sizeof(TooltipSettings::getExcludes()) > 0) {
				foreach(TooltipSettings::getExcludes() as $exclude) {
					if($exclude == "") {
						continue;
					}

					$out.= 'if((current_link.length == 0) || String(current_link).search(/^' . str_replace("/", "\\/", addslashes($exclude)) . '$/) != -1) {
									link.className=link.className + " nothumbsniper ";
								}';
				}
			}else {
				$out.= 'if(current_link.length == 0) {
							link.className=link.className + " nothumbsniper ";
						}';
			}

			$out.= 'if(link.className.indexOf("nothumbsniper") == -1) {';

			if(TooltipSettings::getPreview() == 'external')
			{
				$out.= 'if(blogurl != linkurl) {
							link.className=link.className + " thumbsniper ";
						}';
			}else
			{
				$out.= 'link.className=link.className + " thumbsniper ";';
			}
			$out.= '}';
			$out.= '}else {
					link.className=link.className + " nothumbsniper "; }';
			$out.= '};';
		}

		$out.= '});</script>' . "\n";

		return $out;
	}


	private function getQtipCode() {
		$out = '<script type="application/javascript">
			jQuery(document).ready(function() {
				jQuery(document).on("mouseenter", ".thumbsniper", function(event) {
		            var thumbsniper = jQuery(this);
		            var url = encodeURIComponent(jQuery(this).attr("href"));

		            thumbsniper.qtip({
		                prerender: true,
		                content: function() {
		                    var thumbnaildiv = jQuery("<div/>", {}).css("padding", "6px");
		                    jQuery(thumbnaildiv).append(
		                        jQuery("<img />", {
		                            src: document.location.protocol + "//' .
                                        TooltipSettings::getApiHost() . '/' .
                                        TooltipSettings::getApiVersion() . '/thumbnail/' .
                                        TooltipSettings::getWidth() . '/' .
                                        TooltipSettings::getEffect() . '/?pk_campaign=tooltip&url=" + url
	                                }));
                            return thumbnaildiv;
	                    },
		                position:
                        {
			                ' . $this->getQtipPosition() . ',
			                adjust: {
			                    resize: true
			                },
			                viewport: jQuery(window),
			                effect: false
			            },
			            style: {
			                classes: "' . $this->getQtipStyle() . ' qtip-shadow qtip-rounded"
			            },
			            show: {
			                event: event.type,
							solo: true,
							ready: false,
							effect: function() {
                                $(this).fadeIn(500);
                            }
			            },
			            events: {
			                render: function(event, api) {
			                    api.toggle(true);
			                },
							hide: function(event, api) {
								active = false;
							}
						}
			        });
		        });
	        });
			</script>' . "\n";

		return $out;
	}


    private function getQtipPosition() {
        $result = null;

        switch(TooltipSettings::getPosition()) {
            case 'top':
                $result = 'at: "top center", my: "bottom center"';
                break;

            case 'bottom':
                $result = 'at: "bottom center", my: "top center"';
                break;

            case 'left':
                $result = 'at: "center left", my: "center right"';
                break;

            case 'right':
                $result = 'at: "center right", my: "center left"';
                break;

            default:
                $result = 'at: "top center", my: "bottom center"';
        }

        return $result;
    }


    private function getQtipStyle() {
        $result = null;

        switch(TooltipSettings::getStyle()) {
            case 'light':
                $result = 'qtip-light';
                break;

            case 'dark':
                $result = 'qtip-dark';
                break;

            case 'red':
                $result = 'qtip-red';
                break;

            case 'blue':
                $result = 'qtip-blue';
                break;

            case 'green':
                $result = 'qtip-green';
                break;

            case 'youtube':
                $result = 'qtip-youtube';
                break;

            case 'tipsy':
                $result = 'qtip-tipsy';
                break;

            case 'bootstrap':
                $result = 'qtip-bootstrap';
                break;

            case 'tipped':
                $result = 'qtip-tipped';
                break;

            case 'jtools':
                $result = 'qtip-jtools';
                break;

            default:
                $result = 'qtip-dark';
        }

        return $result;
    }
}
