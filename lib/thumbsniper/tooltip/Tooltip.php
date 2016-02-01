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


    public function getInlineCss() {
        $out = '.qtip { max-width: none; } ';
        $out.= '.qtip-titlebar{ text-align: center; padding: 5px; } ';

        return $out;
    }

    public function getInlineCssHtmlTag() {
        $out = '<style type="text/css">' . $this->getInlineCss() . '</style>';

        return $out;
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
			$out.= 'jQuery("a").each(function() {
							var link = this;
							var blogurl = "' . TooltipSettings::getSiteUrl() . '";
							var linkurl = String(link.href).substring(0, blogurl.length);
							var linkproto = String(link.href).substring(0, 4);';

			$out.= 'if(linkproto == "http" || linkproto == "https") {';
			$out.= 'var current_link = thumbsniper_rel_to_abs(link.href);';

			if(is_array(TooltipSettings::getExcludes())) {
				foreach(TooltipSettings::getExcludes() as $exclude) {
					if($exclude == "") {
						continue;
					}

					$out.= 'if((current_link.length == 0) || String(current_link).search(/^' . str_replace("/", "\\/", addslashes($exclude)) . '$/) != -1) {
									jQuery(link).removeClass("thumbsniper");
									jQuery(link).addClass("nothumbsniper");
								}';
				}
			}else {
				$out.= 'if(current_link.length == 0) {
							jQuery(link).addClass("nothumbsniper");
						}';
			}

			$out.= 'if(!jQuery(link).hasClass("nothumbsniper")) {';

			if(TooltipSettings::getPreview() == 'external')
			{
				$out.= 'if(blogurl != linkurl) {
							jQuery(link).addClass("thumbsniper");
						}';
			}else
			{
				$out.= 'jQuery(link).addClass("thumbsniper");';
			}
			$out.= '}';
			$out.= '}else {
					jQuery(link).addClass("nothumbsniper"); }';
			$out.= '})';
		}

		$out.= ' })</script>' . "\n";

		return $out;
	}


    private function getTitleInlineScript() {
        if(TooltipSettings::isShowTitle()) {
            return 'var title = jQuery(this).attr("title");
                    if(jQuery(this).attr("title")) {
                        var api = jQuery(this).qtip("api");
                        imgTag.imagesLoaded( {api, title}, function() {
                                api.set("content.title", title);
                        });
                    }';
        }else {
            return "";
        }
    }


	private function getQtipCode() {
		$out = '<script type="application/javascript">
			jQuery(document).ready(function() {
				jQuery(document).on("mouseenter", ".thumbsniper", function(event) {
		            var thumbsniper = jQuery(this);
		            var current_link = thumbsniper_rel_to_abs(jQuery(this).attr("href"));
		            var url = encodeURIComponent(current_link);
                    var active = true;

		            thumbsniper.qtip({
		                prerender: true,
	                    content: function(event, api) {
	                            api.set("content.css", { display: "block", visibility: "false !important" });

//                                while(active) {
                                    $.ajax({
                                        url: document.location.protocol + "//' .
                                            TooltipSettings::getApiHost() . '/' .
                                            TooltipSettings::getApiVersion() . '/thumbnail/' .
                                            TooltipSettings::getWidth() . '/' .
                                            TooltipSettings::getEffect() . '/?pk_campaign=tooltip",
                                        jsonp: "callback",
                                        dataType: "jsonp",
                                        cache: true,
                                        beforeSend: function(xhr, opts){
                                             opts.url+= "&url=" + url;
                                        }
                                    })
                                    .done(function(data) {
                                        if(data.url != "wait") {
                                            var thumbnaildiv = jQuery("<div/>", {});
                                            thumbnaildiv.css("padding", "6px");
                                            thumbnaildiv.css("text-align", "center");

                                            var imgTag = jQuery("<img />", {
                                                src: data.url
                                            });

                                            jQuery(thumbnaildiv) . append(imgTag);

                                            imgTag.imagesLoaded( {api, thumbnaildiv}, function() {
                                                api.set("content.text", thumbnaildiv);
                                            });

                                            active = false;
                                            //return thumbnaildiv;
                                        }
                                    }, function(xhr, status, error) {
                                        // Upon failure... set the tooltip content to the status and error value
                                        //api.set("content.text", status + ": " + error);
                                        active = false;
                                    });
//                                }
                            return false;
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
                                jQuery(this).fadeIn(500);
                            }
			            },
			            events: {
			                render: function(event, api) {
			                    api.toggle(true);
			                }
//						},
//						hide: {
//                           fixed: true,
//                           event: "click"
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
