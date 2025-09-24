{{-- resources/views/components/cookie-consent.blade.php --}}

<div id="cookie-consent-banner"
    class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 py-4 px-5 flex flex-col sm:flex-row sm:items-center justify-between z-50 shadow-lg hidden">
    <div class="flex-1 pr-4">
        <h2 class="text-sm font-bold text-purple-900">Help us improve your experience</h2>
        <p class="text-xs text-gray-600 mt-1">
            We use cookies and other technologies to personalise content, measure ads and provide an optimised
            experience. Some cookies are required for the site to function and cannot be turned off. By accepting, you
            agree to the <a href="{{ route('cookie.policy') }}" class="text-purple-900 underline">Asets cookie policy</a>.
            You can update your preferences any time.
        </p>
    </div>
    <div class="flex gap-2 mt-4 sm:mt-0">
        <button id="only-necessary-btn"
            class="text-xs border border-gray-300 rounded-md py-2 px-4 bg-white text-gray-700 hover:bg-gray-50 transition duration-150">Only
            necessary</button>
        <button id="accept-all-btn"
            class="text-xs border border-purple-900 rounded-md py-2 px-4 bg-purple-900 text-white hover:bg-purple-800 transition duration-150">Accept
            All</button>
        <button id="manage-preferences-btn"
            class="text-xs border border-gray-300 rounded-md py-2 px-4 bg-white text-gray-700 hover:bg-gray-50 transition duration-150">Manage
            preferences</button>
        <button id="close-cookie-banner-btn" class="text-gray-500 hover:text-gray-700 transition duration-150">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
        </button>
    </div>
</div>

<!-- Cookie Preferences Modal -->
<div id="cookie-preferences-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div
        class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4 absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
        <div class="flex justify-between items-center border-b border-gray-200 px-6 py-4">
            <h3 class="text-lg font-bold text-purple-900">Cookie Preferences</h3>
            <button id="close-preferences-modal-btn" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        <div class="px-6 py-4">
            <div class="mb-4">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <h4 class="font-bold text-sm">Necessary Cookies</h4>
                        <p class="text-xs text-gray-600">Required for the website to function properly. Cannot be
                            disabled.</p>
                    </div>
                    <div class="toggle-container">
                        <label class="switch">
                            <input type="checkbox" checked disabled class="toggle-input">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <h4 class="font-bold text-sm">Analytics Cookies</h4>
                        <p class="text-xs text-gray-600">Help us improve our website by collecting anonymous
                            information.</p>
                    </div>
                    <div class="toggle-container">
                        <label class="switch">
                            <input type="checkbox" id="analytics-cookies" class="toggle-input">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <h4 class="font-bold text-sm">Marketing Cookies</h4>
                        <p class="text-xs text-gray-600">Used to track visitors across websites for advertising
                            purposes.</p>
                    </div>
                    <div class="toggle-container">
                        <label class="switch">
                            <input type="checkbox" id="marketing-cookies" class="toggle-input">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-end gap-2 px-6 py-4 border-t border-gray-200">
            <button id="save-preferences-btn"
                class="text-xs border border-purple-900 rounded-md py-2 px-4 bg-purple-900 text-white hover:bg-purple-800 transition duration-150">Save
                Preferences</button>
        </div>
    </div>
</div>

<!-- CSS for toggle switches -->
<style>
    /* Toggle switch styling */
    .switch {
        position: relative;
        display: inline-block;
        width: 40px;
        height: 24px;
        cursor: pointer;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #d1d5db;
        /* gray-300 */
        transition: .4s;
        border-radius: 24px;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked+.toggle-slider {
        background-color: #d8b4fe;
        /* purple-300 */
    }

    input:disabled+.toggle-slider {
        opacity: 0.7;
        cursor: not-allowed;
    }

    input:checked+.toggle-slider:before {
        transform: translateX(16px);
        background-color: #6b21a8;
        /* purple-700 */
    }

    /* Fade animation for banner and modal */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    #cookie-consent-banner:not(.hidden),
    #cookie-preferences-modal:not(.hidden) {
        animation: fadeIn 0.3s ease-out;
    }
</style>

@push('scripts')
    <!-- Make sure jQuery is included in your layout before this script -->
    <script>
        $(document).ready(function() {
            // Check if user has already set cookie preferences
            const hasConsent = localStorage.getItem('cookie_consent');

            if (!hasConsent) {
                // Show the cookie banner if no preference has been saved
                $('#cookie-consent-banner').removeClass('hidden');
            }

            // Handle "Only necessary" button click
            $('#only-necessary-btn').click(function() {
                setConsent('necessary');
                hideBanner();
            });

            // Handle "Accept All" button click
            $('#accept-all-btn').click(function() {
                setConsent('all');
                hideBanner();
            });

            // Handle "Manage preferences" button click
            $('#manage-preferences-btn').click(function() {
                $('#cookie-preferences-modal').removeClass('hidden');
            });

            // Handle close banner button click
            $('#close-cookie-banner-btn').click(function() {
                // We'll still store a basic consent setting to prevent the banner from showing again
                if (!hasConsent) {
                    localStorage.setItem('cookie_consent', JSON.stringify({
                        necessary: true,
                        analytics: false,
                        marketing: false,
                        closed: true
                    }));
                }
                hideBanner();
            });

            // Handle close preferences modal button click
            $('#close-preferences-modal-btn').click(function() {
                $('#cookie-preferences-modal').addClass('hidden');
            });

            // Handle save preferences button click
            $('#save-preferences-btn').click(function() {
                const analyticsConsent = $('#analytics-cookies').prop('checked');
                const marketingConsent = $('#marketing-cookies').prop('checked');

                // Create consent object
                const consentOptions = {
                    necessary: true, // Always true
                    analytics: analyticsConsent,
                    marketing: marketingConsent
                };

                // Save preferences
                localStorage.setItem('cookie_consent', JSON.stringify(consentOptions));

                // Send consent to server (optional)
                $.ajax({
                    url: "{{ route('cookie.consent') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: JSON.stringify(consentOptions),
                    contentType: 'application/json',
                    success: function(response) {
                        console.log('Consent saved on server');
                    }
                });

                // Apply cookie preferences
                applyCookiePreferences(consentOptions);

                // Hide modal
                $('#cookie-preferences-modal').addClass('hidden');

                // Hide banner
                hideBanner();
            });

            // Load saved preferences if they exist
            if (hasConsent) {
                try {
                    const savedPreferences = JSON.parse(localStorage.getItem('cookie_consent'));

                    // Set toggle switches according to saved preferences
                    if (savedPreferences.analytics) {
                        $('#analytics-cookies').prop('checked', true);
                    }

                    if (savedPreferences.marketing) {
                        $('#marketing-cookies').prop('checked', true);
                    }

                    // Apply saved preferences
                    applyCookiePreferences(savedPreferences);
                } catch (e) {
                    console.error('Error loading saved cookie preferences', e);
                }
            }

            // Function to hide the cookie banner
            function hideBanner() {
                $('#cookie-consent-banner').addClass('hidden');
            }

            // Function to set consent and save to localStorage
            function setConsent(type) {
                let consentOptions = {
                    necessary: true // Always true
                };

                if (type === 'all') {
                    consentOptions.analytics = true;
                    consentOptions.marketing = true;
                } else {
                    consentOptions.analytics = false;
                    consentOptions.marketing = false;
                }

                localStorage.setItem('cookie_consent', JSON.stringify(consentOptions));

                // Send consent to server (optional)
                $.ajax({
                    url: "{{ route('cookie.consent') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: JSON.stringify(consentOptions),
                    contentType: 'application/json',
                    success: function(response) {
                        console.log('Consent saved on server');
                    }
                });

                applyCookiePreferences(consentOptions);
            }

            // Function to apply cookie preferences
            function applyCookiePreferences(preferences) {
                // Enable/disable Google Analytics based on analytics preference
                if (preferences.analytics) {
                    enableGoogleAnalytics();
                } else {
                    disableGoogleAnalytics();
                }

                // Enable/disable marketing cookies
                if (preferences.marketing) {
                    enableMarketingScripts();
                } else {
                    disableMarketingScripts();
                }
            }

            // Functions to enable/disable different types of cookies
            function enableGoogleAnalytics() {
                // Example: Load Google Analytics script
                if (typeof ga === 'undefined') {
                    (function(i, s, o, g, r, a, m) {
                        i['GoogleAnalyticsObject'] = r;
                        i[r] = i[r] || function() {
                            (i[r].q = i[r].q || []).push(arguments)
                        }, i[r].l = 1 * new Date();
                        a = s.createElement(o),
                            m = s.getElementsByTagName(o)[0];
                        a.async = 1;
                        a.src = g;
                        m.parentNode.insertBefore(a, m)
                    })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

                    ga('create', '{{ config('services.google.analytics_id', 'UA-XXXXX-Y') }}', 'auto');
                    ga('send', 'pageview');
                }
            }

            function disableGoogleAnalytics() {
                // Disable Google Analytics tracking
                window['ga-disable-{{ config('services.google.analytics_id', 'UA-XXXXX-Y') }}'] = true;
            }

            function enableMarketingScripts() {
                // Example: Load Facebook Pixel
                if (!$('#facebook-pixel').length) {
                    ! function(f, b, e, v, n, t, s) {
                        if (f.fbq) return;
                        n = f.fbq = function() {
                            n.callMethod ?
                                n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                        };
                        if (!f._fbq) f._fbq = n;
                        n.push = n;
                        n.loaded = !0;
                        n.version = '2.0';
                        n.queue = [];
                        t = b.createElement(e);
                        t.async = !0;
                        t.id = 'facebook-pixel';
                        t.src = v;
                        s = b.getElementsByTagName(e)[0];
                        s.parentNode.insertBefore(t, s)
                    }(window, document, 'script',
                        'https://connect.facebook.net/en_US/fbevents.js');
                    fbq('init', '{{ config('services.facebook.pixel_id', '000000000000000') }}');
                    fbq('track', 'PageView');
                }
            }

            function disableMarketingScripts() {
                // Disable Facebook Pixel
                window.fbq = function() {};
            }
        });
    </script>
@endpush
