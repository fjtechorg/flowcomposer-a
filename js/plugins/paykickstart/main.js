var eventMethod  = window.addEventListener ? "addEventListener" : "attachEvent";
var eventer      = window[ eventMethod ];
var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";

eventer( messageEvent, function( e ) {
    var result = e.data;

    if( result.domain && result.domain.toLowerCase() == 'pk' && result.type && result.type.toLowerCase() == 'widget-form' ) {
        var iframe = document.querySelector( '[src*="checkout-widget-v2/' + result.funnel_plan_id + '/' + result.hash + '"]' );

        pkSetLandingPage( iframe );

        switch( result.action.toLowerCase() ) {
            case "redirect":
                var url = result.url;

                window.location.href = url;
                break;

            case "close":
                iframe.parentNode.style.display = 'none';
                document.getElementsByTagName('html')[0].classList.remove("popup-active");
                break;
        }
    }
}, false );

function pkSetLandingPage( iframe ) {
    try {
        iframe.contentWindow.postMessage( {
            type: 'get_landing_page',
            url : window.location.href
        }, iframe.src );
    } catch(e) {
    }
}

function extractHostname(url) {
    var hostname;
    //find & remove protocol (http, ftp, etc.) and get hostname

    if (url.indexOf("//") > -1) {
        hostname = url.split('/')[2];
    }
    else {
        hostname = url.split('/')[0];
    }

    //find & remove port number
    hostname = hostname.split(':')[0];
    //find & remove "?"
    hostname = hostname.split('?')[0];

    return hostname;
}

(function() {
    window.PKWIDGET = window.PKWIDGET || {
        CSS_FILES: {
            iframe: '/widgets/checkout/iframe.css'
        },

        init: function() {
            var links = window.PKWIDGET.getLinks();

            if( !links.length ) {
                return;
            }

            for( var i in links ) {
                if( !links.hasOwnProperty( i ) ) continue;

                var link = links[ i ];
                var hash = link.getAttribute('href').split( '#pkmodal' )[ 1 ].replace( '#pkmodal', '' );



                if( window.PKWidgetsData[ hash ] === undefined ) {
                    // in case if page contains links without related js code
                    continue;
                }

                link.setAttribute( 'data-pk-is-inited', 1 );
                link.setAttribute( 'data-pk-hash', hash );
                link.setAttribute( 'href', "javascript:void(0)" );
                link.setAttribute( 'onclick', "return window.PKWIDGET.openModal(this);" );

                window.PKWIDGET.initCss( hash );

                window.PKWIDGET.createIFrame( link, hash, false );
            }
        },

        initCss: function( hash ) {
            var CSS_FILES = window.PKWIDGET.CSS_FILES;

            for( var i in Object.keys( CSS_FILES ) ) {
                if( !Object.keys( CSS_FILES ).hasOwnProperty( i ) ) {
                    continue;
                }

                var widgetData = window.PKWidgetsData[ hash ];
                var host       = widgetData.host;

                var css_key  = Object.keys( CSS_FILES )[ i ];
                var css_file = host.replace( /\/$/, "" ) + CSS_FILES[ css_key ];
                var css_id   = 'pkWidgetStyle_' + css_key;

                if( document.getElementById( css_key ) ) {
                    continue;
                }

                var head = document.getElementsByTagName( 'head' )[ 0 ];
                var link = document.createElement( 'link' );

                link.type  = 'text/css';
                link.href  = css_file;
                link.id    = css_id;
                link.rel   = 'stylesheet';
                link.media = 'all';

                head.appendChild( link );
            }
        },

        getLinks: function() {
            return document.querySelectorAll( '[href^="#pkmodal"]' );
        },

        openModal: function( el ) {
            var hash     = el.dataset.pkHash;
            var isInited = el.dataset.pkIsInited;

            if( !isInited ) {
                return;
            }

            var widget          = document.getElementById( 'pk-widget-' + hash );
            var widgetContainer = document.getElementById( 'pk-widget-container-' + hash );

            if( !widget ) {
                window.PKWIDGET.createIFrame( el, hash, true );
            }

            if( !widget.src ) {
                if (el.dataset.getParams) {
                    widget.dataset.originalSrc += '?' + el.dataset.getParams;
                }

                widget.src = widget.dataset.originalSrc;
            }

            widgetContainer.style.display = 'block';
            document.getElementsByTagName('html')[0].classList.add("popup-active");
        },

        createIFrame: function( el, hash, show ) {
            var host           = window.PKWidgetsData[ hash ].host;
            var funnel_plan_id = window.PKWidgetsData[ hash ].fpd;
            var buttonElement = document.querySelector('[data-pk-hash="'+hash+'"]');
            var qty = buttonElement.getAttribute("data-qty");
            var agencyId = buttonElement.getAttribute('data-agency-id');
            $(".pk-widget-iframe-container").remove();


            if (!qty)
                var url = host + '/checkout-widget-v2/' + funnel_plan_id + '/' + hash+"?cm_agency_id="+agencyId;
            else
                var url = host + '/checkout-widget-v2/' + funnel_plan_id + '/' + hash+ "?cm_agency_id="+agencyId+"&qt="+qty;


            var iframe                 = document.createElement( 'iframe' );
            iframe.className           = 'pk-widget';
            iframe.id                  = 'pk-widget-' + el.dataset.pkHash;
            iframe.dataset.originalSrc = url;
            iframe.onload = function() {
                pkSetLandingPage( this ); // we need to know a landing page when widget iframe is created.
            };

            var iframeContainer           = document.createElement( 'div' );
            iframeContainer.style.display = show ? 'block' : 'none';
            iframeContainer.className     = 'pk-widget-iframe-container';
            iframeContainer.id            = 'pk-widget-container-' + el.dataset.pkHash;
            iframeContainer.appendChild( iframe );

            document.body.appendChild( iframeContainer );

            var is_safari = navigator.userAgent.indexOf( "Safari" ) > -1;
            var is_chrome = navigator.userAgent.indexOf( 'Chrome' ) > -1;

            if( (is_chrome) && (is_safari) ) {
                is_safari = false;
            }

            if( is_safari ) {
                if( !document.cookie.match( /^(.*;)?\s*pksafari\s*=\s*[^;]+(.*)?$/ ) ) {
                    document.cookie = 'pksafari=pksafari; expires=Tue, 19 Jan 2038 03:14:07 UTC; path=/';

                    location.href = 'https://' + extractHostname( url ) + '/safari-redirect';
                }
            }
        }
    };

    window.addEventListener( "load", function() {
        window.PKWIDGET.init();
    } );
})();