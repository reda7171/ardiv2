/*
 * Show properties for header map and half map
 */
(function ($) {
    'use strict';

    // Main Houzez object that will contain all modules
    var houzez = window.houzez || {};

    /**
     * Maps Module
     * Handles Google Maps functionality for property maps
     */
    houzez.Maps = (function () {
        // Map variables
        let houzezMap;
        let mapBounds;
        let hideInfoWindows = new Array();
        let markerClusterer = null;
        let markers = new Array();
        let checkOpenedWindows = new Array();
        let clusterIcon = '';
        let map_cluster_enable = 1;
        let clusterer_zoom = 12;
        let closeIcon = '';
        let infoWindowPlac = '';
        let marker_spiderfier = 0;
        let current_marker = 0;
        let markerPricePins = 'no';
        let googlemap_style = '';
        let mapType = 'roadmap';
        let ajaxurl = '';
        let userID = '';
        let not_found = '';
        let thousands_separator = '';
        let is_halfmap = 0;
        let default_lat = 0;
        let default_lng = 0;
        let default_zoom = 12;
        let max_zoom = 18;
        let houzez_default_radius = 0;
        let isSearchInProgress = false; // Flag to prevent recursive map searches
        let pagination_only = false;
        let viewport_search = false;
        let auto_load_map_listings = 0;
        let map_message_timeout = null;

        // Ajax and other variables
        if (typeof houzez_vars !== 'undefined') {
            ajaxurl = houzez_vars.admin_url + 'admin-ajax.php';
            userID = houzez_vars.user_id;
            not_found = houzez_vars.not_found;
            thousands_separator = houzez_vars.thousands_separator;
            is_halfmap = parseInt(houzez_vars.is_halfmap);
            houzez_default_radius = parseInt(houzez_vars.houzez_default_radius);
            auto_load_map_listings = parseInt(
                houzez_vars.auto_load_map_listings
            );
        }

        const is_mobile = () => {
            // 1. matchMedia (layout)
            if (window.matchMedia('(max-width: 991px)').matches) {
                return true;
            }

            // 2. modern UAData
            if (navigator.userAgentData?.mobile === true) {
                return true;
            }

            // 3. UA regex fallback
            return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
                navigator.userAgent
            );
        };

        /**
         * Format numbers with commas
         */
        const addCommas = function (nStr) {
            nStr += '';
            var x = nStr.split('.');
            var x1 = x[0];
            var x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
        };

        /**
         * Format numbers with thousand separators
         */
        const thousandSeparator = (n) => {
            if (typeof n === 'number') {
                n += '';
                var x = n.split('.');
                var x1 = x[0];
                var x2 = x.length > 1 ? '.' + x[1] : '';
                var rgx = /(\d+)(\d{3})/;
                while (rgx.test(x1)) {
                    x1 = x1.replace(rgx, '$1' + thousands_separator + '$2');
                }
                return x1 + x2;
            } else {
                return n;
            }
        };

        /**
         * Handle special characters in map data
         */
        const processSpecialChars = function () {
            return {
                '&amp;': '&',
                '&quot;': '"',
                '&#039;': "'",
                '&#8217;': '\u2019',
                '&#038;': '&',
                '&lt;': '<',
                '&gt;': '>',
                '&#8216;': '\u2018',
                '&#8230;': '\u2026',
                '&#8221;': '\u201D',
                '&#8211;': 'â€“',
                '&#8212;': 'â€”',
            };
        };

        /**
         * Set up the map type based on user selection
         */
        const setupMapType = function (mapOptions) {
            switch (mapType) {
                case 'hybrid':
                    mapOptions.mapTypeId = google.maps.MapTypeId.HYBRID;
                    break;
                case 'terrain':
                    mapOptions.mapTypeId = google.maps.MapTypeId.TERRAIN;
                    break;
                case 'satellite':
                    mapOptions.mapTypeId = google.maps.MapTypeId.SATELLITE;
                    break;
                default:
                    mapOptions.mapTypeId = google.maps.MapTypeId.ROADMAP;
            }
            return mapOptions;
        };

        /**
         * Navigate to next map marker
         */
        const houzez_map_next = function (hMap) {
            current_marker++;
            if (current_marker > markers.length) {
                current_marker = 1;
            }

            if (markers[current_marker - 1]) {
                const marker = markers[current_marker - 1];
                const propertyId = marker.propertyId;

                if (propertyId) {
                    openInfoWindowById(propertyId);
                }
            }
        };

        /**
         * Navigate to previous map marker
         */
        const houzez_map_prev = function (hMap) {
            current_marker--;
            if (current_marker < 1) {
                current_marker = markers.length;
            }

            if (markers[current_marker - 1]) {
                const marker = markers[current_marker - 1];
                const propertyId = marker.propertyId;

                if (propertyId) {
                    openInfoWindowById(propertyId);
                }
            }
        };

        /**
         * Zoom in on the map
         */
        const houzez_map_zoomin = function (hMap) {
            const zoomInButton = document.getElementById('listing-mapzoomin');
            if (zoomInButton) {
                zoomInButton.addEventListener('click', function () {
                    var current = parseInt(hMap.getZoom(), 10);
                    current++;
                    if (current > 20) {
                        current = 20;
                    }
                    hMap.setZoom(current);

                    console.log(current);

                    updateViewportCoordinates();
                });
            }
        };

        /**
         * Zoom out on the map
         */
        const houzez_map_zoomout = function (hMap) {
            const zoomOutButton = document.getElementById('listing-mapzoomout');
            if (zoomOutButton) {
                zoomOutButton.addEventListener('click', function () {
                    var current = parseInt(hMap.getZoom(), 10);
                    current--;
                    if (current < 0) {
                        current = 0;
                    }
                    hMap.setZoom(current);

                    updateViewportCoordinates();
                });
            }
        };

        /**
         * Change map type (roadmap, satellite, etc)
         */
        const houzez_change_map_type = function (map_type) {
            if (map_type === 'roadmap') {
                houzezMap.setMapTypeId(google.maps.MapTypeId.ROADMAP);
            } else if (map_type === 'satellite') {
                houzezMap.setMapTypeId(google.maps.MapTypeId.SATELLITE);
            } else if (map_type === 'hybrid') {
                houzezMap.setMapTypeId(google.maps.MapTypeId.HYBRID);
            } else if (map_type === 'terrain') {
                houzezMap.setMapTypeId(google.maps.MapTypeId.TERRAIN);
            }
            return false;
        };

        /**
         * Remove map loader after map loads
         */
        const removeMapLoader = function () {
            google.maps.event.addListener(
                houzezMap,
                'tilesloaded',
                function () {
                    jQuery('.houzez-map-loading').hide();
                }
            );
        };

        /**
         * Clear marker clusterer
         */
        const clearClusterer = function () {
            if (map_cluster_enable != 0 && markerClusterer != null) {
                markerClusterer.clearMarkers();
            }
        };

        /**
         * Reload map markers
         */
        const reloadMarkers = function () {
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(null);
            }
            // Reset the markers array
            markers = [];
        };

        /**
         * Fit map bounds to markers
         */
        const houzez_map_bounds = function () {
            const bounds = new google.maps.LatLngBounds();

            // Add each marker position to bounds
            for (let i = 0; i < markers.length; i++) {
                // For advanced markers, we use the position property directly
                if (markers[i].position) {
                    bounds.extend(markers[i].position);
                }
            }

            houzezMap.fitBounds(bounds);

            // Add a listener to maintain minimum zoom level
            google.maps.event.addListenerOnce(
                houzezMap,
                'bounds_changed',
                function () {
                    const minZoomLevel = default_zoom; // You can adjust this minimum zoom level
                    if (houzezMap.getZoom() > minZoomLevel) {
                        houzezMap.setZoom(minZoomLevel);
                    }
                }
            );
        };

        /**
         * Create circle overlay on map
         */
        const createMapCircle = (
            map,
            centerLatLng,
            {
                strokeColor = '#4f5962',
                strokeOpacity = 0.8,
                strokeWeight = 2,
                fillColor = '#4f5962',
                fillOpacity = 0.35,
                radius = 0.3 * 1000,
                ...restOptions
            } = {}
        ) => {
            const circleOptions = {
                strokeColor,
                strokeOpacity,
                strokeWeight,
                fillColor,
                fillOpacity,
                radius,
                ...restOptions,
                map,
                center: centerLatLng,
            };
            return new google.maps.Circle(circleOptions);
        };

        /**
         * Create a standard marker
         */
        const createStandardMarker = async (
            map,
            propertyData,
            position,
            {
                propertyId = propertyData && propertyData.property_id
                    ? propertyData.property_id.toString()
                    : null,
                ...restOptions
            } = {}
        ) => {
            // Return early if propertyData is not available
            if (!propertyData) {
                return null;
            }

            let marker_url = propertyData.marker;

            if (window.devicePixelRatio > 1.5) {
                if (propertyData.retinaMarker) {
                    marker_url = propertyData.retinaMarker;
                }
            }

            // Process special characters for title
            let title = '';
            if (propertyData.title) {
                const specialChars = processSpecialChars();
                title = propertyData.title.replace(
                    /\&[\w\d\#]{2,5}\;/g,
                    (s) => specialChars[s]
                );
            }

            // Create custom marker content with the property's icon
            const markerContent = document.createElement('div');
            markerContent.style.position = 'relative';

            // Create the image element
            const markerImage = document.createElement('img');
            markerImage.src = marker_url;
            markerImage.style.width = '44px';
            markerImage.style.height = '56px';
            markerContent.appendChild(markerImage);

            // Create the advanced marker
            const advancedMarker = new google.maps.marker.AdvancedMarkerElement(
                {
                    map,
                    position,
                    content: markerContent,
                    title: title,
                    ...restOptions,
                }
            );

            // Add property ID as a custom property
            advancedMarker.propertyId = propertyId;

            return advancedMarker;
        };

        /**
         * Display map message showing visible properties count
         */
        const showMapMessage = function (visibleCount, totalCount) {
            if (auto_load_map_listings !== 1) {
                return;
            }

            // Clear any existing timeout
            if (map_message_timeout) {
                clearTimeout(map_message_timeout);
                map_message_timeout = null;
            }

            const $messageContainer = $('#houzez-map-message');
            const $messageContent = $messageContainer.find('.map-info-message');

            if (visibleCount < totalCount) {
                // Use translatable strings with placeholders
                const countMessage = houzez_vars.map_show_some
                    .replace('%s', visibleCount)
                    .replace('%s', totalCount);

                const zoomMessage = houzez_vars.zoom_in_show_more;
                $messageContent.html(countMessage + '<br>' + zoomMessage);
                $messageContainer.addClass('show');
            } else {
                // Use translatable string with placeholder
                const message = houzez_vars.map_show_all.replace(
                    '%s',
                    totalCount
                );
                $messageContent.html(message);
                $messageContainer.addClass('show');
            }

            // Hide message after 5 seconds
            map_message_timeout = setTimeout(function () {
                $messageContainer.removeClass('show');
            }, 3000);
        };

        /**
         * Initialize map event listeners for map controls
         */
        const initMapControls = function () {
            $('.houzezMapType').on('click', function (e) {
                e.preventDefault();
                var maptype = $(this).data('maptype');
                houzez_change_map_type(maptype);
            });

            if (document.getElementById('listing-mapzoomin')) {
                houzez_map_zoomin(houzezMap);
            }
            if (document.getElementById('listing-mapzoomout')) {
                houzez_map_zoomout(houzezMap);
            }

            $('#houzez-gmap-next').on('click', function () {
                houzez_map_next(houzezMap);
            });

            $('#houzez-gmap-prev').on('click', function () {
                houzez_map_prev(houzezMap);
            });
        };

        /**
         * Add markers to the map
         */
        const houzezAddMarkers = async function (
            map_properties,
            houzezMap,
            preservePosition = false
        ) {
            // If we need to preserve position, store current map state
            let currentCenter = null;
            let currentZoom = null;

            currentCenter = houzezMap.getCenter();
            currentZoom = houzezMap.getZoom();

            hideInfoWindows = function () {
                while (checkOpenedWindows.length > 0) {
                    var closeWindow = checkOpenedWindows.pop();
                    closeWindow.close();
                }
            };

            var houzezMarkerInfoWindow = function (map, marker, infowindow) {
                marker.addListener('gmp-click', function () {
                    hideInfoWindows();
                    infowindow.open({
                        anchor: marker,
                        map: map,
                    });
                    checkOpenedWindows.push(infowindow);

                    // Add lazy load for info window
                    var infoWindowImage = infowindow
                        .getContent()
                        .getElementsByClassName('listing-thumbnail');
                    if (infoWindowImage.length) {
                        if (infoWindowImage[0].dataset.src) {
                            infoWindowImage[0].src =
                                infoWindowImage[0].dataset.src;
                        }
                    }
                });
            };

            const special_chars = processSpecialChars();

            for (var i = 0; i < map_properties.length; i++) {
                // Validate latitude and longitude are valid numbers
                const lat = parseFloat(map_properties[i].latitude);
                const lng = parseFloat(map_properties[i].longitude);

                // Skip properties with invalid coordinates
                if (
                    !map_properties[i].latitude ||
                    !map_properties[i].longitude ||
                    isNaN(lat) ||
                    isNaN(lng) ||
                    lat < -90 ||
                    lat > 90 ||
                    lng < -180 ||
                    lng > 180
                ) {
                    console.warn(
                        'Skipping property with invalid coordinates:',
                        {
                            property_id: map_properties[i].property_id,
                            latitude: map_properties[i].latitude,
                            longitude: map_properties[i].longitude,
                        }
                    );
                    continue;
                }

                // Make sure we store property ID as a string to ensure consistent comparisons
                const propertyId = map_properties[i].property_id.toString();
                let marker_color = map_properties[i].marker_color;
                const position = new google.maps.LatLng(lat, lng);

                let marker;

                if (markerPricePins == 'yes') {
                    // Create a custom HTML element for the price pin
                    const pricePin = document.createElement('div');
                    pricePin.className = `gm-marker map-marker-label`;
                    pricePin.dataset.id = map_properties[i].property_id;

                    // Add background and border color if marker_color exists
                    if (marker_color) {
                        pricePin.style.backgroundColor = marker_color;
                        pricePin.style.borderColor = marker_color;
                        pricePin.style.color = '#ffffff';
                    }

                    // Create the price container
                    const pricePinInner = document.createElement('div');
                    pricePinInner.className = 'gm-marker-price';
                    pricePinInner.innerHTML = map_properties[i].pricePin;
                    pricePin.appendChild(pricePinInner);

                    // Create the advanced marker with the custom element
                    marker = new google.maps.marker.AdvancedMarkerElement({
                        map: houzezMap,
                        position: position,
                        content: pricePin,
                    });

                    // Add property ID as a custom property
                    marker.propertyId = propertyId;
                } else {
                    // Use advanced marker
                    let marker_url = map_properties[i].marker;

                    if (
                        window.devicePixelRatio > 1.5 &&
                        map_properties[i].retinaMarker
                    ) {
                        marker_url = map_properties[i].retinaMarker;
                    }

                    // Process title for special characters
                    let title = '';
                    if (map_properties[i].title) {
                        title = map_properties[i].title.replace(
                            /\&[\w\d\#]{2,5}\;/g,
                            function (s) {
                                return special_chars[s];
                            }
                        );
                    }

                    // Create custom marker content with the property's icon
                    const markerContent = document.createElement('div');
                    markerContent.style.position = 'relative';

                    // Create the image element
                    const markerImage = document.createElement('img');
                    markerImage.src = marker_url;
                    markerImage.style.width = '44px';
                    markerImage.style.height = '56px';
                    markerContent.appendChild(markerImage);

                    // Create the advanced marker
                    marker = new google.maps.marker.AdvancedMarkerElement({
                        map: houzezMap,
                        position: position,
                        content: markerContent,
                        title: title,
                    });

                    // Add property ID as a custom property
                    marker.propertyId = propertyId;
                }

                //Bounds
                mapBounds.extend(position);

                var mainContent = document.createElement('div');
                mainContent.className = 'property-info-window';
                var innerHTML = '';

                if (map_properties[i].thumbnail) {
                    innerHTML +=
                        '<div class="info-window-image">' +
                        map_properties[i].featured_label +
                        '<a target="' +
                        map_properties[i].link_target +
                        '" href="' +
                        map_properties[i].url +
                        '">' +
                        '<img class="img-fluid listing-thumbnail" src="' +
                        infoWindowPlac +
                        '" data-src="' +
                        map_properties[i].thumbnail +
                        '" alt="' +
                        map_properties[i].title +
                        '"/>' +
                        '</a>' +
                        '</div>';
                } else {
                    innerHTML +=
                        '<div class="info-window-image">' +
                        map_properties[i].featured_label +
                        '<a target="' +
                        map_properties[i].link_target +
                        '" href="' +
                        map_properties[i].url +
                        '">' +
                        '<img class="img-fluid listing-thumbnail" src="' +
                        infoWindowPlac +
                        '" alt="' +
                        map_properties[i].title +
                        '"/>' +
                        '</a>' +
                        '</div>';
                }

                innerHTML += '<div class="info-content" style="padding:10px;">';

                if (map_properties[i].price) {
                    innerHTML +=
                        '<div class="info-window-price">' +
                        map_properties[i].price +
                        '</div>';
                }

                innerHTML += map_properties[i].meta;

                if (map_properties[i].property_type) {
                    innerHTML +=
                        '<div class="info-window-property-type">' +
                        map_properties[i].property_type +
                        '</div>';
                }

                innerHTML += map_properties[i].address;
                innerHTML += '</div>';

                mainContent.innerHTML = innerHTML;

                var infowindow = new google.maps.InfoWindow({
                    content: mainContent,
                });

                // set infowindow for marker
                houzezMarkerInfoWindow(houzezMap, marker, infowindow);

                markers.push(marker);
            } // end for loop

            // If we need to preserve position, restore the map state
            if (!preservePosition) {
                houzez_map_bounds();
            }

            // Implement marker clustering for advanced markers
            if (map_cluster_enable != 0) {
                try {
                    // Clear any existing clusterer
                    if (markerClusterer) {
                        if (
                            typeof markerClusterer.clearMarkers === 'function'
                        ) {
                            markerClusterer.clearMarkers();
                        } else if (
                            typeof markerClusterer.clear === 'function'
                        ) {
                            markerClusterer.clear();
                        }
                    }

                    // Check for the global markerClusterer object from the downloaded library
                    if (typeof window.markerClusterer !== 'undefined') {
                        // Create a new MarkerClusterer instance
                        markerClusterer =
                            new window.markerClusterer.MarkerClusterer({
                                map: houzezMap,
                                markers: markers,
                                algorithm:
                                    new window.markerClusterer.GridAlgorithm({
                                        maxZoom: parseInt(clusterer_zoom),
                                        gridSize: 60,
                                    }),
                                renderer: {
                                    render: ({ count, position }) => {
                                        // Create a custom cluster marker
                                        const clusterDiv =
                                            document.createElement('div');
                                        clusterDiv.className =
                                            'houzez-cluster-marker';
                                        clusterDiv.style.backgroundImage = `url(${clusterIcon})`;
                                        clusterDiv.style.width = '48px';
                                        clusterDiv.style.height = '48px';
                                        clusterDiv.style.backgroundSize =
                                            'contain';
                                        clusterDiv.style.display = 'flex';
                                        clusterDiv.style.justifyContent =
                                            'center';
                                        clusterDiv.style.alignItems = 'center';
                                        clusterDiv.style.color = '#ffffff';
                                        clusterDiv.style.fontWeight = 'bold';
                                        clusterDiv.textContent = count;

                                        return new google.maps.marker.AdvancedMarkerElement(
                                            {
                                                position,
                                                content: clusterDiv,
                                            }
                                        );
                                    },
                                },
                                // Custom cluster click handler for more controlled zoom
                                onClusterClick: (event, cluster, map) => {
                                    // Get the bounds of all markers in this cluster
                                    const bounds =
                                        new google.maps.LatLngBounds();
                                    cluster.markers.forEach((marker) => {
                                        bounds.extend(marker.position);
                                    });

                                    // Get the current zoom level
                                    const currentZoom = map.getZoom();

                                    // Calculate the zoom level needed to fit the bounds
                                    map.fitBounds(bounds);

                                    // If the cluster has many markers, limit how much we zoom in
                                    if (cluster.markers.length > 1) {
                                        // After fitBounds runs, get the new zoom level and limit it if needed
                                        google.maps.event.addListenerOnce(
                                            map,
                                            'bounds_changed',
                                            () => {
                                                // Don't zoom in more than 2 levels at once
                                                const maxZoom = Math.min(
                                                    currentZoom + 1,
                                                    16
                                                );
                                                if (map.getZoom() > maxZoom) {
                                                    map.setZoom(maxZoom);
                                                }
                                            }
                                        );
                                    }
                                },
                            });
                    } else {
                        console.error(
                            'MarkerClusterer library not found. Make sure it is properly loaded.'
                        );
                    }
                } catch (error) {
                    console.error(
                        'Error initializing marker clustering:',
                        error
                    );
                }
            }
        };

        /**
         * Find a marker by property ID
         */
        const findMarkerByPropertyId = (markers, propertyId) => {
            if (!propertyId || !markers || markers.length === 0) return null;

            // Ensure we're comparing strings to avoid type mismatches
            const propIdStr = propertyId.toString();

            for (let i = 0; i < markers.length; i++) {
                // For advanced markers, we access the propertyId property directly
                const markerPropId = markers[i].propertyId
                    ? markers[i].propertyId.toString()
                    : null;

                if (markerPropId === propIdStr) {
                    return { marker: markers[i], index: i };
                }
            }
            return null;
        };

        /**
         * Open info window for a specific property by ID
         */
        const openInfoWindowById = function (propertyId) {
            if (!propertyId) {
                console.log('No property ID provided to openInfoWindowById');
                return;
            }

            // Clean up the property ID if needed
            let propIdToUse = propertyId;

            // If it's a string and has 'hz-' prefix, remove it
            if (
                typeof propIdToUse === 'string' &&
                propIdToUse.indexOf('hz-') === 0
            ) {
                propIdToUse = propIdToUse.replace('hz-', '');
            }

            // First close any open info windows
            hideInfoWindows();

            // Find the marker with this property ID
            const markerResult = findMarkerByPropertyId(markers, propIdToUse);

            if (markerResult) {
                const marker = markerResult.marker;

                // Trigger the click on the marker content to show info window
                if (marker.content) {
                    marker.content.click();
                } else {
                    // Direct click on the marker if content is not available
                    marker.click();
                }
                return true;
            } else {
                console.log('No marker found for property ID:', propIdToUse);
                return false;
            }
        };

        /**
         * Initialize InfoboxTrigger - Mouse over property to show marker info
         */
        const initInfoboxTrigger = function () {
            if (is_mobile()) return;

            $('#half-map-listing-area .hz-map-trigger').each(function () {
                // Get the Property ID - first try to get the raw value, then clean it
                var propertyID = $(this).data('hz-id');
                if (typeof propertyID === 'string') {
                    // If it has 'hz-' prefix, remove it
                    if (propertyID.indexOf('hz-') === 0) {
                        propertyID = propertyID.replace('hz-', '');
                    }
                } else {
                    // Just get the numeric value
                    propertyID = $(this)
                        .data('hz-id')
                        .toString()
                        .replace(/[^\d.]/g, '');
                }

                // Store the ID to make sure it's available in the event handler
                const propID = propertyID;

                $(this).on('mouseenter', function () {
                    if (houzezMap) {
                        openInfoWindowById(propID);
                    }
                });

                $(this).on('mouseleave', function () {
                    hideInfoWindows();
                });
            });
        };

        /**
         * AJAX search for half map view
         */
        const houzez_half_map_listings = function (current_page, current_form) {
            var ajax_container = $('#houzez_ajax_container');
            var total_results = $('#half-map-listing-area .page-title span');
            var sortby = $('#ajax_sort_properties').val();
            var item_layout = $('.listing-view').data('layout');
            var layout_css = $('.listing-view').data('css');
            var layout_view = $('.listing-view').data('view');

            // Create data object from form serialization
            const formData = current_form.serialize();

            // Prepare AJAX data
            const ajaxData = {
                action: 'houzez_half_map_listings',
                paged: current_page,
                sortby: sortby,
                item_layout: item_layout,
                layout_css: layout_css,
                layout_view: layout_view,
            };

            ajaxData.is_pagination_request = pagination_only;

            // Merge form data with our ajax parameters
            const serializedData = formData + '&' + $.param(ajaxData);

            // Add viewport data if this is a viewport-based search
            let finalData = serializedData;

            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: ajaxurl,
                data: finalData,
                beforeSend: function () {
                    $('.houzez-map-loading').show();
                    ajax_container
                        .empty()
                        .append(
                            '' +
                                '<div id="houzez-map-loading" class="houzez-map-loading">' +
                                '<div class="mapPlaceholder">' +
                                '<div class="loader-ripple spinner">' +
                                '<div class="bounce1"></div>' +
                                '<div class="bounce2"></div>' +
                                '<div class="bounce3"></div>' +
                                '</div>' +
                                '</div>' +
                                '</div>'
                        );
                },
                success: function (data) {
                    if (data.query != '') {
                        $('input[name="search_args"]').val(data.query);
                    }
                    if (data.search_uri != '') {
                        $('input[name="search_URI"]').val(data.search_uri);
                    }
                    $('.map-notfound').remove();
                    $('.search-no-results-found').remove();

                    $('.houzez-map-loading').hide();

                    if (data.total_results > 0) {
                        if (data.getProperties === true) {
                            // Store current map position and zoom if this is a viewport search
                            let currentCenter = houzezMap.getCenter();
                            let currentZoom = houzezMap.getZoom();

                            clearClusterer();
                            reloadMarkers();
                            houzezAddMarkers(
                                data.properties,
                                houzezMap,
                                viewport_search
                            );

                            if (viewport_search) {
                                if (currentCenter && currentZoom) {
                                    houzezMap.setCenter(currentCenter);
                                    houzezMap.setZoom(currentZoom);
                                }
                            }

                            // Show map message with property counts
                            showMapMessage(
                                data.properties.length,
                                data.total_results
                            );
                        } // End if getProperties

                        ajax_container.empty().html(data.propHtml);
                        total_results.empty().html(data.total_results);
                        map_ajax_pagination();

                        houzez_listing_lightbox();
                        houzez_grid_image_gallery();
                        houzez_grid_call_to_action();
                        compare_for_ajax();

                        if (!is_mobile()) {
                            initInfoboxTrigger();
                        }

                        $('[data-bs-toggle="tooltip"]').tooltip();
                    } else {
                        clearClusterer();
                        reloadMarkers();

                        let currentCenter = houzezMap.getCenter();
                        let currentZoom = houzezMap.getZoom();

                        if (viewport_search) {
                            if (currentCenter && currentZoom) {
                                houzezMap.setCenter(currentCenter);
                                houzezMap.setZoom(currentZoom);
                            }
                        }

                        houzez_map_zoomin(houzezMap);
                        houzez_map_zoomout(houzezMap);

                        $('#houzez-properties-map').append(
                            '<div class="map-notfound">' + not_found + '</div>'
                        );
                        ajax_container.empty().html(data.propHtml);
                        total_results.empty().html(data.total_results);
                    }
                    return false;
                },
                complete: function () {
                    pagination_only = false;
                    viewport_search = false;
                    isSearchInProgress = false;
                    // Remove icon-spin class from location trigger when AJAX completes
                    $('.location-trigger')
                        .find('.icon-location-target')
                        .removeClass('icon-spin');
                },
                error: function (xhr, status, error) {
                    console.log(xhr.status);
                    console.log(xhr.responseText);
                    console.log(thrownError);
                },
            });
            return false;
        };

        // Trigger map search when slider interaction ends
        const triggerDistanceRangeSearch = function () {
            if ($('#houzez-properties-map').length > 0) {
                let current_page = 0;
                let distanceRange = $('#radius-range-slider');
                let $form = $('#desktop-search-form');
                distanceRange.on('change', (e) => {
                    $('input[name="use_radius"]').prop('checked', true);
                    $('input[name="ne_lat"]').val('');
                    $('input[name="ne_lng"]').val('');
                    $('input[name="sw_lat"]').val('');
                    $('input[name="sw_lng"]').val('');
                    $('input[name="zoom"]').val('');
                    houzez_search_on_change(null, $form);
                });
            }
        };

        const triggerPriceRangeSearch = function () {
            if ($('#houzez-properties-map').length > 0) {
                let current_page = 0;
                let fromPrice = $('#fromSlider_price_range_halfmap');
                let toPrice = $('#toSlider_price_range_halfmap');
                let $form = $('#desktop-search-form');
                fromPrice.on('change', (e) => {
                    houzez_search_on_change(null, $form);
                });

                toPrice.on('change', (e) => {
                    houzez_search_on_change(null, $form);
                });
            }
        };

        const triggerPriceRangeSearchMobile = function () {
            if ($('#houzez-properties-map').length > 0) {
                let current_page = 0;
                let fromPrice = $('#fromSlider_price_range_mobile');
                let toPrice = $('#toSlider_price_range_mobile');
                let $form = $('#mobile-search-form');
                fromPrice.on('change', (e) => {
                    houzez_search_on_change(null, $form);
                });

                toPrice.on('change', (e) => {
                    houzez_search_on_change(null, $form);
                });
            }
        };

        /**
         * Set URL state for browser history
         */
        const houzezSetPushState = (pageUrl) => {
            window.history.pushState({ houzezTheme: true }, '', pageUrl);
        };

        /**
         * Set URL based on search form parameters
         */
        const houzez_set_url = function (currentForm) {
            var $form =
                currentForm || $('form.houzez-search-filters-js').first();

            if ($form.length) {
                $form.addClass('loading');
            }

            var url = $form.attr('action');

            if (url == undefined) {
                return true;
            }

            var formData = $form
                .find(':input')
                .filter(function (index, element) {
                    if (
                        !$(element).prop('disabled') &&
                        $(element).val() != '' &&
                        $(element).attr('name') != 'search_geolocation' &&
                        $(element).attr('name') != 'search_URI' &&
                        $(element).attr('name') != 'search_args' &&
                        $(element).attr('name') != 'houzez_save_search_ajax'
                    ) {
                        return true;
                    }
                })
                .serialize();

            if (url == undefined) {
                url = '';
            } else if (url.indexOf('?') != -1) {
                url = url + '?' + formData;
            } else {
                url = url + '?' + formData;
            }

            houzezSetPushState(url);
        };

        /**
         * Initialize toggle for fullscreen map view
         */
        const initializeFullScreenToggle = function () {
            $('#houzez-gmap-full').on('click', function () {
                var $this = $(this);
                if ($this.hasClass('active')) {
                    $this.removeClass('active');
                    $this
                        .parents('.map-wrap')
                        .removeClass('houzez-fullscreen-map');
                } else {
                    $this
                        .parents('.map-wrap')
                        .addClass('houzez-fullscreen-map');
                    $this.addClass('active');
                }
            });
        };

        /**
         * Handle search changes and updating results
         */
        const houzez_search_on_change = function (
            $this = null,
            currentForm = null,
            current_page = 0
        ) {
            // If a search is already in progress, don't start another one
            if (isSearchInProgress) {
                return;
            }

            let $form = currentForm;

            // If currentForm is null, try to get the form from $this
            if (!$form && $this) {
                $form = $this.parents('form');
            }

            // Safety check to ensure we have a form
            if (!$form || !$form.length) {
                console.warn('No valid form found for search');
                return;
            }

            isSearchInProgress = true;
            $('.hz-halfmap-paged').val(current_page);
            houzez_set_url($form);
            houzez_half_map_listings(current_page, $form);
        };

        /**
         * Update viewport coordinates
         */
        const updateViewportCoordinates = () => {
            if (!auto_load_map_listings) {
                return;
            }

            viewport_search = true;
            let $form = $('#desktop-search-form');

            let overlayform = $(
                '#mobile-search-form.hz-mobile-overlay-search-js'
            );

            if (overlayform.length > 0) {
                $form = $('#mobile-search-form');
            }

            const bounds = houzezMap.getBounds();

            if (bounds) {
                const ne = bounds.getNorthEast();
                const sw = bounds.getSouthWest();
                const zoom = houzezMap.getZoom();
                $('input[name="ne_lat"]').val(ne.lat());
                $('input[name="ne_lng"]').val(ne.lng());
                $('input[name="sw_lat"]').val(sw.lat());
                $('input[name="sw_lng"]').val(sw.lng());
                $('input[name="zoom"]').val(zoom);
                $('input[name="use_radius"]').prop('checked', false);

                houzez_search_on_change(null, $form);
            }
        };

        const initPropertiesInViewport = () => {
            // Add a flag to track initial load and prevent duplicate events
            let initialLoad = true;
            let isDragging = false;
            let lastDragTime = 0;

            // Clear any existing listeners to prevent duplicates
            google.maps.event.clearListeners(houzezMap, 'idle');
            google.maps.event.clearListeners(houzezMap, 'dragend');
            google.maps.event.clearListeners(houzezMap, 'dragstart');
            google.maps.event.clearListeners(houzezMap, 'zoom_changed');

            // Track when drag starts
            google.maps.event.addListener(houzezMap, 'dragstart', function () {
                isDragging = true;
            });

            // Add event listener for map drag end
            google.maps.event.addListener(houzezMap, 'dragend', function () {
                // Skip if a search is already in progress
                if (!isSearchInProgress && isDragging) {
                    updateViewportCoordinates();
                    // Set the last drag time to prevent zoom events immediately after drag
                    lastDragTime = Date.now();
                    isDragging = false;
                } else {
                    isDragging = false;
                }
            });
        };

        /**
         * Setup Ajax pagination for map results
         */
        const map_ajax_pagination = function () {
            $('.houzez_ajax_pagination a').on('click', function (e) {
                e.preventDefault();

                if (auto_load_map_listings) {
                    pagination_only = true;
                }
                let current_page = $(this).data('houzepagi');

                let $form = $('#desktop-search-form');

                if (
                    $('#mobile-search-form.apply-mobile-pagination').length > 0
                ) {
                    $form = $('#mobile-search-form');
                }

                houzez_search_on_change(null, $form, current_page);
            });
            return false;
        };

        /**
         * Initialize search and map handlers
         */
        const initMapSearchHandlers = function () {
            map_ajax_pagination();

            // Sorting
            $('#ajax_sort_properties').on('change', function () {
                let $form = $('#desktop-search-form');
                let mobile_sortby = $(this).hasClass('mobile-sortby');
                if (mobile_sortby) {
                    $form = $('#mobile-search-form');
                }
                houzez_search_on_change(null, $form);
            });

            // Search fields change
            $('select.houzez_search_ajax, input.houzez_search_ajax').on(
                'change',
                function () {
                    var $form = $(this).closest('form');
                    houzez_search_on_change(null, $form);
                }
            );

            // Search button click
            if ($('.half-map-wrap').length > 0) {
                $(
                    '.btn-apply, .half-map-search-js-btn, #auto_complete_ajax'
                ).on('click', function (e) {
                    e.preventDefault();
                    let $form = $(this).closest('form');
                    houzez_search_on_change(null, $form);
                });
            }
        };

        /**
         * Initialize map view toggle for mobile
         */
        const initMapViewToggle = function () {
            $('#houzez-btn-map-view').on('click', function (e) {
                e.preventDefault();
                $('#half-map-listing-area, .listing-wrap').hide();
                $('#map-view-wrap').show();
                $('#mobile-search-form').addClass(
                    'hz-mobile-overlay-search-js'
                );
                google.maps.event.trigger(houzezMap, 'resize');
                houzez_map_bounds();
            });

            $('#houzez-btn-listing-view').on('click', function (e) {
                e.preventDefault();
                $('#map-view-wrap').hide();
                $('#half-map-listing-area, .listing-wrap').show();
                $('#mobile-search-form').removeClass(
                    'hz-mobile-overlay-search-js'
                );
            });
        };

        /**
         * Setup autocomplete for location field
         */
        const initAutocomplete = function () {
            if ($('.hz-map-field-js').length > 0) {
                var geo_country_limit = houzez_vars.geo_country_limit;
                var geocomplete_country = houzez_vars.geocomplete_country;
                // Use function construction to store map & DOM elements separately for each instance
                var MapField = function ($container) {
                    this.$container = $container;
                };

                // Use prototype for better performance
                MapField.prototype = {
                    // Initialize everything
                    init: function () {
                        this.initDomElements();
                        this.autocomplete();
                    },
                    // Initialize DOM elements
                    initDomElements: function () {
                        this.addressField =
                            this.$container.data('address-field');
                    },

                    // Autocomplete address
                    autocomplete: function () {
                        var that = this,
                            $address = this.addressField;

                        if (null === $address) {
                            return;
                        }

                        var options = {
                            types: ['geocode', 'establishment'],
                        };

                        var inputField = document.getElementById($address);
                        var autocomplete = new google.maps.places.Autocomplete(
                            inputField,
                            options
                        );

                        if (
                            geo_country_limit != 0 &&
                            geocomplete_country != ''
                        ) {
                            if (geocomplete_country == 'UAE') {
                                geocomplete_country = 'AE';
                            }
                            autocomplete.setComponentRestrictions({
                                country: [geocomplete_country],
                            });
                        }

                        google.maps.event.addListener(
                            autocomplete,
                            'place_changed',
                            function () {
                                var place = autocomplete.getPlace();
                                var latLng = new google.maps.LatLng(
                                    place.geometry.location.lat(),
                                    place.geometry.location.lng()
                                );
                                that.updateCoordinate(latLng);

                                if (is_halfmap) {
                                    inputField = $(inputField);
                                    houzez_search_on_change(inputField);
                                }
                            }
                        );
                    },

                    // Update coordinate to input field
                    updateCoordinate: function (latLng) {
                        $('input[name="lat"]').val(latLng.lat());
                        $('input[name="lng"]').val(latLng.lng());
                        $('input[name="ne_lat"]').val('');
                        $('input[name="ne_lng"]').val('');
                        $('input[name="sw_lat"]').val('');
                        $('input[name="sw_lng"]').val('');
                        $('input[name="zoom"]').val('');
                        $('input[name="use_radius"]').prop('checked', true);
                    },
                };

                var initMap = function () {
                    var $this = $(this);
                    var controller;

                    controller = new MapField($this);
                    controller.init();
                };

                var init = function (e) {
                    $('.hz-map-field-js').each(initMap);
                };
                init();

                // Add location trigger functionality
                $('.location-trigger').on('click', function (e) {
                    e.preventDefault();

                    let $this = $(this);
                    let $parent = $this.parents('.location-search');
                    let $input = $parent.find('input.search_location_js');
                    $this.find('.icon-location-target').addClass('icon-spin');

                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function (
                            position
                        ) {
                            // Get location name from coordinates using Google Geocoder
                            let geocoder = new google.maps.Geocoder();
                            let latLng = new google.maps.LatLng(
                                position.coords.latitude,
                                position.coords.longitude
                            );

                            geocoder.geocode(
                                { location: latLng },
                                function (results, status) {
                                    if (
                                        status === google.maps.GeocoderStatus.OK
                                    ) {
                                        if (results[0]) {
                                            $input.val(
                                                results[0].formatted_address
                                            );
                                        } else {
                                            $input.val(
                                                houzez_vars.current_location
                                            );
                                        }
                                    } else {
                                        $input.val(
                                            houzez_vars.current_location
                                        );
                                    }

                                    // Update coordinates
                                    $('input[name="lat"]').val(
                                        position.coords.latitude
                                    );
                                    $('input[name="lng"]').val(
                                        position.coords.longitude
                                    );
                                    $('input[name="ne_lat"]').val('');
                                    $('input[name="ne_lng"]').val('');
                                    $('input[name="sw_lat"]').val('');
                                    $('input[name="sw_lng"]').val('');
                                    $('input[name="zoom"]').val('');
                                    $('input[name="use_radius"]').prop(
                                        'checked',
                                        true
                                    );

                                    // Trigger search if in half map
                                    if (is_halfmap) {
                                        houzez_search_on_change($this);
                                    } else {
                                        $this
                                            .find('.icon-location-target')
                                            .removeClass('icon-spin');
                                    }
                                }
                            );
                        });
                    }
                });
            }
        };

        /**
         * Initialize the map
         */
        const initializeMap = async function () {
            if ($('#houzez-properties-map').length > 0) {
                // Get map data from data attributes
                let mapElement = $('#houzez-properties-map');
                let mapDataJSON = mapElement.data('map');
                let mapOptionsJSON = mapElement.data('options');
                let mapData, mapOptions;

                // Get search parameters from URL or form
                const searchParams = new URLSearchParams(
                    window.location.search
                );
                let searchLat = searchParams.get('lat');
                let searchLng = searchParams.get('lng');

                // Also check hidden form fields which might have coordinates from previous search
                if (!searchLat || !searchLng) {
                    searchLat = $('input[name="lat"]').val();
                    searchLng = $('input[name="lng"]').val();
                }

                if (!mapDataJSON) {
                    return;
                }

                try {
                    // If it's already an object (jQuery may have parsed it)
                    if (typeof mapDataJSON === 'object') {
                        mapData = mapDataJSON;
                    } else {
                        // Otherwise parse the JSON string
                        mapData = JSON.parse(mapDataJSON);
                    }

                    // Get options from data-options attribute
                    if (typeof mapOptionsJSON === 'object') {
                        mapOptions = mapOptionsJSON;
                    } else if (mapOptionsJSON) {
                        mapOptions = JSON.parse(mapOptionsJSON);
                    }
                } catch (e) {
                    console.error('Error parsing map data:', e);
                    return;
                }

                // Initialize map options from the data
                if (mapOptions) {
                    clusterIcon = mapOptions.clusterIcon;
                    map_cluster_enable = mapOptions.map_cluster_enable;
                    clusterer_zoom = mapOptions.clusterer_zoom;
                    closeIcon = mapOptions.closeIcon;
                    infoWindowPlac = mapOptions.infoWindowPlac;
                    markerPricePins = mapOptions.markerPricePins;
                    mapType = mapOptions.map_type;
                    googlemap_style = mapOptions.googlemap_style;
                    default_lat = parseFloat(mapOptions.default_lat);
                    default_lng = parseFloat(mapOptions.default_lng);
                    default_zoom = parseFloat(mapOptions.default_zoom);
                    max_zoom = parseFloat(mapOptions.max_zoom);

                    if (
                        searchLat &&
                        searchLng &&
                        !isNaN(parseFloat(searchLat)) &&
                        !isNaN(parseFloat(searchLng))
                    ) {
                        default_lat = parseFloat(searchLat);
                        default_lng = parseFloat(searchLng);
                    }

                    // Override default coordinates with taxonomy coordinates if available
                    if (mapOptions.center_lat && mapOptions.center_lng) {
                        default_lat = parseFloat(mapOptions.center_lat);
                        default_lng = parseFloat(mapOptions.center_lng);
                    }

                    var mapConfig = {
                        zoom: default_zoom,
                        maxZoom: max_zoom,
                        disableDefaultUI: true,
                        scrollwheel: false,
                        center: new google.maps.LatLng(
                            default_lat,
                            default_lng
                        ),
                        mapId: mapOptions.mapId,
                    };
                }

                mapConfig = setupMapType(mapConfig);

                houzezMap = new google.maps.Map(
                    document.getElementById('houzez-properties-map'),
                    mapConfig
                );
                mapBounds = new google.maps.LatLngBounds();

                removeMapLoader();

                if (mapData.properties && mapData.properties.length > 0) {
                    await houzezAddMarkers(mapData.properties, houzezMap);

                    // Only initialize infobox trigger if we have properties and not on mobile
                    if (!is_mobile()) {
                        initInfoboxTrigger();
                    }

                    let total_results =
                        $('#total-results').data('total-results');

                    // Show initial map message
                    if (total_results > 0) {
                        showMapMessage(
                            mapData.properties.length,
                            total_results
                        );
                    }
                } else {
                    var defaultLocation = new google.maps.LatLng(
                        default_lat,
                        default_lng
                    );

                    var mapConfig = {
                        center: defaultLocation,
                        zoom: default_zoom,
                        maxZoom: max_zoom,
                        disableDefaultUI: true,
                        scrollwheel: false,
                        mapId: mapOptions.mapId,
                    };

                    houzezMap = new google.maps.Map(
                        document.getElementById('houzez-properties-map'),
                        mapConfig
                    );
                    jQuery('.houzez-map-loading').hide();
                }

                initMapControls();

                if (auto_load_map_listings && is_halfmap) {
                    initPropertiesInViewport();
                }
            }
        };

        /**
         * Initialize the maps module
         */
        const init = async function () {
            if (typeof google === 'object' && typeof google.maps === 'object') {
                initializeMap();
                initMapSearchHandlers();
                initMapViewToggle();
                initAutocomplete();
            }

            triggerPriceRangeSearch();
            triggerPriceRangeSearchMobile();
            triggerDistanceRangeSearch();
            initializeFullScreenToggle();
        };

        /**
         * Create a marker with price pin
         */
        const createPricePin = async (map, propertyData, position) => {
            // Return early if propertyData is not available
            if (!propertyData) {
                return null;
            }

            // Create a custom HTML element for the price pin
            const pricePin = document.createElement('div');
            pricePin.className = `gm-marker map-marker-label`;
            pricePin.dataset.id = propertyData.property_id;

            // Create the price container
            const pricePinInner = document.createElement('div');
            pricePinInner.className = 'gm-marker-price';
            pricePinInner.innerHTML = propertyData.pricePin;
            pricePin.appendChild(pricePinInner);

            // Create the advanced marker with the custom element
            const advancedMarker = new google.maps.marker.AdvancedMarkerElement(
                {
                    map,
                    position,
                    content: pricePin,
                }
            );

            // Add property ID as a custom property
            advancedMarker.propertyId = propertyData.property_id.toString();

            return advancedMarker;
        };

        // Public API
        return {
            init: init,
            addMarkers: houzezAddMarkers,
            clearClusterer: clearClusterer,
            reloadMarkers: reloadMarkers,
            mapBounds: houzez_map_bounds,
            halfMapAjax: houzez_half_map_listings,
            setUrl: houzez_set_url,
            searchOnChange: houzez_search_on_change,
            changeMapType: houzez_change_map_type,
            initInfoboxTrigger: initInfoboxTrigger,
            removeMapLoader: removeMapLoader,
            createMapCircle: createMapCircle,
            createStandardMarker: createStandardMarker,
            createPricePin: createPricePin,
            triggerPriceRangeSearch: triggerPriceRangeSearch,
            triggerPriceRangeSearchMobile: triggerPriceRangeSearchMobile,
            triggerDistanceRangeSearch: triggerDistanceRangeSearch,
        };
    })();

    /**
     * SingleAgentMap Module
     * Handles Google Maps functionality for single agent page
     */
    houzez.SingleAgentMap = (function () {
        let agentMap = null;

        /**
         * Initialize the agent sidebar map
         */
        const init = async () => {
            if ($('#houzez-agent-sidebar-map').length <= 0) {
                return;
            }

            // Get configuration values
            let mapZoom = 15;
            const mapType = 'roadmap';
            const mapId = 'HOUZEZ_MAP_ID';

            const agent_lat = $('#houzez-agent-sidebar-map').data('lat');
            const agent_lng = $('#houzez-agent-sidebar-map').data('lng');

            if (agent_lat != '' && agent_lng != '') {
                const agentLatLng = new google.maps.LatLng(
                    agent_lat,
                    agent_lng
                );
                const mapOptions = {
                    center: agentLatLng,
                    zoom: mapZoom,
                    disableDefaultUI: true,
                    scrollwheel: false,
                    mapId: mapId,
                };

                // Set map type
                agentMap = new google.maps.Map(
                    document.getElementById('houzez-agent-sidebar-map'),
                    mapOptions
                );

                // Create a pin element
                const pinElement = new google.maps.marker.PinElement({
                    background: '#1DABE3',
                    borderColor: '#FFFFFF',
                    glyphColor: '#FFFFFF',
                });

                // Create the advanced marker
                const marker = new google.maps.marker.AdvancedMarkerElement({
                    map: agentMap,
                    position: agentLatLng,
                    content: pinElement.element,
                });
            }
        };

        return {
            init: init,
        };
    })();

    /**
     * SinglePropertyOverviewMap Module
     * Handles Google Maps functionality for single property overview map
     */
    houzez.SinglePropertyOverviewMap = (function () {
        let houzezMap;
        let marker = null;

        /**
         * Initialize the property overview map
         */
        const init = async () => {
            if ($('#houzez-overview-listing-map').length <= 0) {
                return;
            }

            let options;
            let infoWindowPlac;
            let closeIcon;
            const mapElement = $('#houzez-overview-listing-map');
            let mapDataJSON = mapElement.data('map');
            let mapOptionsJSON = mapElement.data('options');

            if (!mapDataJSON) {
                return;
            }

            let mapZoom = 15;
            let google_map_style = '';
            let showCircle = false;
            let map_pin_type = 'marker';
            let markerPricePins = 'no';
            let mapId = 'HOUZEZ_MAP_ID';

            // Apply any specific map options
            if (mapOptionsJSON) {
                try {
                    if (typeof mapOptionsJSON === 'object') {
                        options = mapOptionsJSON;
                    } else {
                        options = JSON.parse(mapOptionsJSON);
                    }
                } catch (e) {
                    console.error('Error parsing map options:', e);
                }
            }

            try {
                if (typeof mapDataJSON === 'object') {
                    mapDataJSON = mapDataJSON;
                } else {
                    mapDataJSON = JSON.parse(mapDataJSON);
                }
            } catch (e) {
                console.error('Error parsing map data:', e);
                return;
            }

            if (!mapDataJSON.latitude || !mapDataJSON.longitude) {
                console.error('Property coordinates not provided');
                return;
            }

            // Initialize map options from the data
            if (options) {
                closeIcon = options.closeIcon;
                infoWindowPlac = options.infoWindowPlac;
                markerPricePins = options.markerPricePins;
                map_pin_type = options.map_pin_type;
                google_map_style = options.googlemap_style;
                mapId = options.mapId || 'HOUZEZ_MAP_ID';

                if (map_pin_type == 'circle') {
                    showCircle = true;
                }

                if (options.single_map_zoom > 0) {
                    mapZoom = parseInt(options.single_map_zoom);
                }

                if (google_map_style) {
                    try {
                        if (typeof google_map_style === 'string') {
                            google_map_style = JSON.parse(google_map_style);
                        }
                    } catch (e) {
                        console.error('Error parsing Google Maps style:', e);
                        google_map_style = '';
                    }
                }
            }

            const propertyLatLng = new google.maps.LatLng(
                mapDataJSON.latitude,
                mapDataJSON.longitude
            );

            const mapOptions = {
                center: propertyLatLng,
                zoom: parseInt(mapZoom),
                disableDefaultUI: false,
                scrollwheel: false,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                mapId: mapId,
            };

            houzezMap = new google.maps.Map(
                document.getElementById('houzez-overview-listing-map'),
                mapOptions
            );

            if (showCircle) {
                // Use the shared utility to create map circle
                houzez.Maps.createMapCircle(houzezMap, propertyLatLng);
            } else {
                // Marker library already imported via URL
                const { AdvancedMarkerElement } = google.maps.marker;

                if (markerPricePins === 'yes' && mapDataJSON.pricePin) {
                    // Create a custom HTML element for the price pin
                    const pricePin = document.createElement('div');
                    pricePin.className = 'gm-marker map-marker-label';
                    pricePin.dataset.id =
                        mapDataJSON.property_id || mapDataJSON.post_id;

                    // Add background and border color if marker_color exists
                    if (mapDataJSON.marker_color) {
                        pricePin.style.backgroundColor =
                            mapDataJSON.marker_color;
                        pricePin.style.borderColor = mapDataJSON.marker_color;
                        pricePin.style.color = '#ffffff';
                    }

                    // Create the price container
                    const pricePinInner = document.createElement('div');
                    pricePinInner.className = 'gm-marker-price';
                    pricePinInner.innerHTML = mapDataJSON.pricePin;
                    pricePin.appendChild(pricePinInner);

                    // Create the advanced marker with the custom element
                    marker = new AdvancedMarkerElement({
                        map: houzezMap,
                        position: propertyLatLng,
                        content: pricePin,
                        title: mapDataJSON.title || '',
                    });
                } else {
                    // Create custom marker content with the property's icon
                    let marker_url = mapDataJSON.marker;
                    if (
                        window.devicePixelRatio > 1.5 &&
                        mapDataJSON.retinaMarker
                    ) {
                        marker_url = mapDataJSON.retinaMarker;
                    }

                    const markerContent = document.createElement('div');
                    markerContent.style.position = 'relative';

                    const markerImage = document.createElement('img');
                    markerImage.src = marker_url;
                    markerImage.style.width = '44px';
                    markerImage.style.height = '56px';
                    markerContent.appendChild(markerImage);

                    // Create the advanced marker
                    marker = new AdvancedMarkerElement({
                        map: houzezMap,
                        position: propertyLatLng,
                        content: markerContent,
                        title: mapDataJSON.title || '',
                    });
                }
            }
        };

        return {
            init: init,
        };
    })();

    /**
     * SinglePropertyMap Module
     * Handles Google Maps functionality for single property pages
     */
    houzez.SinglePropertyMap = (function () {
        // Private variables
        let propertyMap = null;
        let panorama = null;
        let streetViewCount = 0;
        let propertyLatLng = null;

        /**
         * Initialize the property map with options
         *
         * @param {Object} options - Configuration options
         * @param {string} options.mapContainerId - ID of map container element
         * @param {string} options.streetViewContainerId - ID of street view container
         * @param {string} options.zoomInBtnId - ID of zoom in button
         * @param {string} options.zoomOutBtnId - ID of zoom out button
         * @param {string} options.mapTypeSelector - Selector for map type buttons
         * @param {string} options.streetViewTabSelector - Selector for street view tab
         */
        const init = async ({
            mapContainerId = 'houzez-single-listing-map',
            streetViewContainerId = 'pills-street-view',
            zoomInBtnId = 'listing-mapzoomin',
            zoomOutBtnId = 'listing-mapzoomout',
            mapTypeSelector = '.houzezMapType',
            streetViewTabSelector = 'a[href="#pills-street-view"]',
            propertyData = null,
            mapOptions = {},
        } = {}) => {
            // Check if map container exists
            const mapContainer = document.getElementById(mapContainerId);
            if (!mapContainer) {
                console.log('Map container not found:', mapContainerId);
                return;
            }

            // Check if we have property data
            if (!propertyData) {
                try {
                    const dataAttr = mapContainer.getAttribute('data-map');
                    if (dataAttr) {
                        propertyData =
                            typeof dataAttr === 'object'
                                ? dataAttr
                                : JSON.parse(dataAttr);
                    }
                } catch (e) {
                    console.error('Error parsing property data:', e);
                    return;
                }
            }

            // Check if we have map options
            if (Object.keys(mapOptions).length === 0) {
                try {
                    const optionsAttr =
                        mapContainer.getAttribute('data-options');
                    if (optionsAttr) {
                        mapOptions =
                            typeof optionsAttr === 'object'
                                ? optionsAttr
                                : JSON.parse(optionsAttr);
                    }
                } catch (e) {
                    console.error('Error parsing map options:', e);
                }
            }

            // Validate property coordinates
            if (
                !propertyData ||
                !propertyData.latitude ||
                !propertyData.longitude
            ) {
                console.error('Property coordinates not provided');
                return;
            }

            // Create property coordinates
            propertyLatLng = new google.maps.LatLng(
                propertyData.latitude,
                propertyData.longitude
            );

            // Create map options
            const mapSettings = {
                center: propertyLatLng,
                zoom: parseInt(mapOptions.single_map_zoom) || 15,
                disableDefaultUI: false,
                scrollwheel: false,
                mapId: mapOptions.mapId, // Required for advanced markers
            };

            // Apply map type
            switch (mapOptions.mapType) {
                case 'hybrid':
                    mapSettings.mapTypeId = google.maps.MapTypeId.HYBRID;
                    break;
                case 'terrain':
                    mapSettings.mapTypeId = google.maps.MapTypeId.TERRAIN;
                    break;
                case 'satellite':
                    mapSettings.mapTypeId = google.maps.MapTypeId.SATELLITE;
                    break;
                default:
                    mapSettings.mapTypeId = google.maps.MapTypeId.ROADMAP;
            }

            // Create the map
            propertyMap = new google.maps.Map(mapContainer, mapSettings);

            // Setup map controls
            setupMapControls({
                map: propertyMap,
                zoomInBtnId,
                zoomOutBtnId,
                mapTypeSelector,
            });

            // Add marker or circle
            await addPropertyMarker({
                map: propertyMap,
                propertyData,
                position: propertyLatLng,
                showCircle: mapOptions.map_pin_type === 'circle',
                markerPricePins: mapOptions.markerPricePins,
            });

            // Setup street view if tab exists
            setupStreetView({
                map: propertyMap,
                position: propertyLatLng,
                streetViewTabSelector,
                streetViewContainerId,
            });
        };

        /**
         * Set up map controls (zoom, map type)
         */
        const setupMapControls = ({
            map,
            zoomInBtnId,
            zoomOutBtnId,
            mapTypeSelector,
        }) => {
            if (!map) return;
        };

        /**
         * Add marker or circle to the map
         */
        const addPropertyMarker = async ({
            map,
            propertyData,
            position,
            showCircle = false,
            markerPricePins = 'no',
        }) => {
            if (!map || !position) return;

            if (showCircle) {
                // Create a circle to indicate property location
                houzez.Maps.createMapCircle(map, position);
            } else {
                // Marker library already imported via URL
                const { AdvancedMarkerElement } = google.maps.marker;

                let marker;

                if (markerPricePins === 'yes' && propertyData.pricePin) {
                    // Create a custom HTML element for the price pin
                    const pricePin = document.createElement('div');
                    pricePin.className = 'gm-marker map-marker-label';
                    pricePin.dataset.id = propertyData.property_id;

                    // Add background and border color if marker_color exists
                    if (propertyData.marker_color) {
                        pricePin.style.backgroundColor =
                            propertyData.marker_color;
                        pricePin.style.borderColor = propertyData.marker_color;
                        pricePin.style.color = '#ffffff';
                    }

                    // Create the price container
                    const pricePinInner = document.createElement('div');
                    pricePinInner.className = 'gm-marker-price';
                    pricePinInner.innerHTML = propertyData.pricePin;
                    pricePin.appendChild(pricePinInner);

                    // Create the advanced marker with the custom element
                    marker = new AdvancedMarkerElement({
                        map,
                        position,
                        content: pricePin,
                        title: propertyData.title,
                    });
                } else {
                    // Create a standard pin with the property's marker icon
                    let marker_url = propertyData.marker;

                    if (
                        window.devicePixelRatio > 1.5 &&
                        propertyData.retinaMarker
                    ) {
                        marker_url = propertyData.retinaMarker;
                    }

                    // Create custom marker content with the property's icon
                    const markerContent = document.createElement('div');
                    markerContent.style.position = 'relative';

                    // Create the image element
                    const markerImage = document.createElement('img');
                    markerImage.src = marker_url;
                    markerImage.style.width = '44px';
                    markerImage.style.height = '56px';
                    markerContent.appendChild(markerImage);

                    // Create the advanced marker
                    marker = new AdvancedMarkerElement({
                        map,
                        position,
                        content: markerContent,
                        title: propertyData.title,
                    });
                }

                // Add click event if needed
                if (marker) {
                    marker.addListener('gmp-click', () => {
                        // Handle marker click if needed
                    });
                }
            }
        };

        /**
         * Set up street view panorama
         */
        const setupStreetView = ({
            map,
            position,
            streetViewTabSelector,
            streetViewContainerId,
        }) => {
            if (!map || !position) return;

            const streetViewTab = $(streetViewTabSelector);
            if (streetViewTab.length === 0) return;

            streetViewTab.on('shown.bs.tab', () => {
                streetViewCount++;
                const streetViewContainer = document.getElementById(
                    streetViewContainerId
                );

                if (!streetViewContainer) return;

                const panoramaOptions = {
                    position: position,
                    pov: {
                        heading: 34,
                        pitch: 10,
                    },
                };

                if (streetViewCount <= 1) {
                    panorama = new google.maps.StreetViewPanorama(
                        streetViewContainer,
                        panoramaOptions
                    );
                } else if (panorama) {
                    panorama.setPosition(position);
                }
            });
        };

        /**
         * Load map from container data attributes
         */
        const loadMapFromDOM = () => {
            // Check for multiple possible map container IDs
            const mapIds = [
                'houzez-single-listing-map',
                'houzez-single-listing-map-address',
                'houzez-single-listing-map-elementor',
            ];

            mapIds.forEach((mapId) => {
                const mapContainer = document.getElementById(mapId);
                if (!mapContainer) return;

                try {
                    // Get data attributes
                    const mapDataAttr = mapContainer.getAttribute('data-map');
                    const mapOptionsAttr =
                        mapContainer.getAttribute('data-options');

                    if (!mapDataAttr) return;

                    // Parse data
                    const propertyData =
                        typeof mapDataAttr === 'object'
                            ? mapDataAttr
                            : JSON.parse(mapDataAttr);

                    const mapOptions = mapOptionsAttr
                        ? typeof mapOptionsAttr === 'object'
                            ? mapOptionsAttr
                            : JSON.parse(mapOptionsAttr)
                        : {};

                    // Initialize map with specific container ID
                    init({
                        mapContainerId: mapId,
                        propertyData,
                        mapOptions,
                    }).catch((error) => {
                        console.error(
                            `Error initializing single property map (${mapId}):`,
                            error
                        );
                    });
                } catch (e) {
                    console.error(
                        `Error initializing property map (${mapId}):`,
                        e
                    );
                    $('.houzez-map-loading').hide();
                }
            });
        };

        // Return public API
        return {
            init: loadMapFromDOM, // Use loadMapFromDOM as the main init function
            loadMapFromDOM,
        };
    })();

    // Make houzez object available globally
    window.houzez = houzez;
})(jQuery);
