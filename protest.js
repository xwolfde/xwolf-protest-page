/**
 * jQuery implementation of xwolf's wordpress plugin. This can be used
 * on any page or content management system. Just put the following lines
 * into the head section of your page or template:
 *
 *   <!-- ONLY if jquery is not included otherwise -->
 *   <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
 *
 *   <!-- Update the path's -->
 *   <script src="protest/protest.js"></script>
 *   <link rel="stylesheet" type="text/css" href="protest/protest.css">
 *
 *   <!-- Initializion code -->
 *   <script type="text/javascript">
 *       $(document).ready(function() {
 *           $('body').protestlayer({
 *               // Adjust the following line if you installed this in another subdirectory
 *               image : 'protest/protest.png',
 *
 *               // Set the following line to false for production environments
 *               debug : true
 *           });
 *       });
 *   </script>
 *
 * Author: Roland Tapken <rt@tasmiro.de>
 */

(function( $ ) {
    var defaultOptions = {
        base : '',
        url : 'http://www.piratenpartei.de/2013/03/07/welttag-gegen-internetzensur-2013/',
        image : 'protest.png',
        title : "Welttag gegen Internetzensur\n12.03.2013",
        longtext : 'Wir zeigen uns solidarisch mit allen durch Überwachung und Zensur eingeschränkten Journalisten und Aktivisten weltweit. Die Organisationen Reporter ohne Grenzen und die Piratenpartei rufen am Welttag gegen Internetzensur zu Protesten auf.',
        more : 'Weitere Informationen bei <a href="http://www.reporter-ohne-grenzen.de/">Reporter ohne Grenzen</a>. <a href="http://wiki.piratenpartei.de/Welttag-gegen-Internetzensur-2013">Informationen, sowie Plugins und Banner</a> zur Teilnahme finden sich auf dem Wiki der Piratenpartei Deutschland.',

        cookiename : 'seen_worldday2013',

        timestart : new Date(2013, 03, 12, 8, 0, 0), // year, month, day, hour, minute, second
        timeend : new Date(2013, 03, 12, 20, 0, 0),   // year, month, day, hour, minute, second

        debug : false
    };

    /**
     * Check if the defined cookie has been set.
     */
    var isCookieSet = function(options) {
        if (options.cookiename) {
            var ca = document.cookie.split(';');
            for(var i=0;i < ca.length;i++) {
                var c = ca[i];
                while (c.charAt(0)==' ') c = c.substring(1, c.length);
                if (c.indexOf(options.cookiename) == 0) {
                    if (options.debug) {
                        console.log('debug: cookie ' + options.cookiename + ' is set');
                        return false;
                    }
                    return true;
                }
            }
        }
        return false;
    };

    /**
     * Check if the current date is within the defined range.
     */
    var checkTimeRange = function(options) {
        if (options.timestart) {
            if (new Date().getTime() < options.timestart.getTime()) {
                if (options.debug) {
                    console.log('debug: current time is before ' + options.timestart.toLocaleString());
                } else {
                    return false;
                }
            }
        }

        if (options.timeend) {
            if (new Date().getTime() >= options.timeend.getTime()) {
                if (options.debug) {
                    console.log('debug: current time is after ' + options.timeend.toLocaleString());
                } else {
                    return false;
                }
            }
        }

        return true;
    };

    $.fn.protestlayer = function(myoptions) {
        return this.each(function() {
            var options = jQuery.extend(true, {}, defaultOptions, myoptions);

            if (isCookieSet(options) || !checkTimeRange(options)) {
                return;
            }

            var $protest = $('<div id="protest" />')
            var $protestBody = $('<div />').appendTo($protest);

            $('<a href="#" class="close" tabindex="1">X</a>')
                .click(function() {
                    // Set cookie
                    if (options.cookiename) {
                        // FIXME funktioniert nicht
                        document.cookie = options.cookiename + "=1";
                    }
                    $protest.fadeOut();
                    return false;
                })
                .appendTo($protestBody);

            if (options.image) {
                var $image = $('<img />').attr('src', options.image);
                var $link = $('<a class="link" />').attr('href', options.url).append($image);

                if (options.title) {
                    $image.attr('alt', options.title);
                    $link.attr('title', options.title);
                }
                if (options.longtext) {
                    $image.attr('longdesc', options.longtext);
                }


                $('<p>').append($link).appendTo($protestBody);
            } else {
                if (options.title) {
                    $('<h1 />').append(
                        options.url ? $('<a class="link" />').attr('href', options.url).text(options.title) : options.title
                    ).appendTo($protestBody);
                }
                if (options.longtext) {
                    $('<p>').html(options.longtext).appendTo($protestBody);
                }
            }

            if (options.more) {
                $('<p class="more">').html(options.more).appendTo($protestBody);
            }

            $protest.appendTo($(this));

            $protest.css('height', $(window).height());
            $(window).bind('resize', function(){
                $protest.css('height', $(window).height());
            });

            if ($(window).width() >= 600) {
                $protest.fadeIn();
            }
        });
    };
})(jQuery);
