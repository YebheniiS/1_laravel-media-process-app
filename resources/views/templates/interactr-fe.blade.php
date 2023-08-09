<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.interactr-head')
    @include('partials.facebook-pixel')
    @include('partials.custom-scripts')

  <!-- Provely Notification Display Script -->
<script>(function(w,n) {
    if (typeof(w[n]) == 'undefined'){ob=n+'Obj';w[ob]=[];w[n]=function(){w[ob].push(arguments);};
    d=document.createElement('script');d.type = 'text/javascript';d.async=1;
    d.src='https://app.provely.io/js/provely-widget.js';x=document.getElementsByTagName('script')[0];x.parentNode.insertBefore(d,x);}
    })(window, 'provelys', '');
    provelys('config', 'baseUrl', 'https://app.provely.io');
    provelys('config', 'uuid', 'cec60e1e-e258-42e2-a1fd-eb3157a2db6c');
    provelys('config', 'showWidget', 1);
    </script>
    <!-- Provely Notification Display Script -->

        <script>
            setTimeout(()=>{
                fbq('trackCustom', 'fe_page_view_5_secs')
            }, 5000)
            setTimeout(()=>{
                fbq('trackCustom', 'fe_page_view_7_secs')
            }, 7000)
            setTimeout(()=>{
                fbq('trackCustom', 'fe_page_view_10_secs')
            }, 10000)
            setTimeout(()=>{
                fbq('trackCustom', 'fe_page_view_15_secs')
            }, 15000)
            setTimeout(()=>{
                fbq('trackCustom', 'fe_page_view_20_secs')
            }, 20000)
            setTimeout(()=>{
                fbq('trackCustom', 'fe_page_view_30_secs')
            }, 30000);

            let lastKnownScrollPosition = 0;
            let ticking = false;
            let scrolledPastViewport = false;
            let seenPricingTable = false;


            function doSomething(scrollPos) {
                if(scrollPos > 900 && ! scrolledPastViewport) {
                    fbq('trackCustom', 'fe_scrolled_past_viewport')
                    scrolledPastViewport = true;
                }

                var element = document.querySelector('#pricing');
                var position = element.getBoundingClientRect();

                // checking whether fully visible
                if(position.top >= 0 && position.bottom <= window.innerHeight) {
                    if(! seenPricingTable) {
                        fbq('trackCustom', 'fe_pricing_table_seen')
                        seenPricingTable = true;
                    }
                }
            }

            document.addEventListener('scroll', function(e) {
                lastKnownScrollPosition = window.scrollY;

                if (!ticking) {
                    window.requestAnimationFrame(function() {
                        doSomething(lastKnownScrollPosition);
                        ticking = false;
                    });

                    ticking = true;
                }
            });

            @if($page->has_timer)
            var countDownDate = new Date("{!! $page->timer_timestamp !!}.000Z").getTime();
            var now = new Date().getTime();
                
                console.log(countDownDate)
            var myfunc = setInterval(function() {
                var now = new Date().getTime();
                
                var timeleft = countDownDate - now;

                if(timeleft <= 0){
                    document.getElementById("days").innerHTML = "00" 
                    document.getElementById("hours").innerHTML = "00" 
                    document.getElementById("mins").innerHTML = "00" 
                    document.getElementById("secs").innerHTML = "00" 
                    clearInterval(myFunc);
                }
                    
                var days = Math.floor(timeleft / (1000 * 60 * 60 * 24));
                var hours = Math.floor((timeleft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((timeleft % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((timeleft % (1000 * 60)) / 1000);
                if(days < 9 ) days = "0" + days 
                if(hours < 9 ) hours = "0" + hours 
                if(minutes < 9 ) minutes = "0" + minutes 
                if(seconds < 9 ) seconds = "0" + seconds 

                document.getElementById("days").innerHTML = days 
                document.getElementById("hours").innerHTML = hours
                document.getElementById("mins").innerHTML = minutes
                document.getElementById("secs").innerHTML = seconds
                }, 1000);
                myfunc();
            @endif
        </script>


        @if($page->banner_text)
            <style>
                .banner-wrapper {
                    display: flex;
                    justify-content: center;
                    margin-top: 60px;
                }
                .banner-inner {
                    background: #F90504; 
                    border-radius: 25px;
                    color: white;
                    padding: 15px 30px;
                }
                .banner-row {
                    margin-top: 60px;
                }
                .banner-text {
                    font-size: 18px;
                }

                @media only screen and (max-width: 991px) {
                    .banner-wrapper {
                    
                    margin-top: 20px;
                }
                }
            </style>
        @endif
        @if($page->has_timer)
            <style>
                .timer-number {
                    width: calc(100% / 11 * 2); 
                    text-align: center;
                    float: left;
                    font-size: 16px;
                }
                .timer-divider {
                    width: calc(100% / 11);
                    text-align: center;
                    float: left;
                    font-size: 24px;
                    font-weight: bold;
                }
                .timer-number p {
                    line-height: 1;
                    text-align: center;
                }
                .timer-number p.number {
                    font-size: 21px;
                    font-weight: bold;
                }
                .banner-timer {
                    float: left; 
                    width: 100%;
                    margin-top: 5px;
                    /* margin-bottom: 10px; */
                }

                .timer-wrapper-inner {
                    margin-left: auto;
                    margin-right: auto;
                    width: 310px;
                }

        

            </style>
        @endif

</head>
<body>
<!-- Page content wrapper-->
<div id="wrapper">
    <div class="main-body">
        <div class="top-menu">
            <div class="container">

                <!-- Top navigation-->
                <nav class="navbar navbar-expand-lg navbar-light">
                    <a class="navbar-brand" href="#" style="max-width: 171px;"><img alt="INTERACTR" src="{!! config('logos.interactr') !!}" class="img-fluid"></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a href="#reviews">Reviews</a>
                            </li>
                            <li class="nav-item">
                                <a href="#features">Features</a>
                            </li>
                            <li class="nav-item">
                                <a href="#cases">Use Cases</a>
                            </li>
                            <li class="nav-item">
                                <a href="#testimonials">Testimonials</a>
                            </li>
                            <li class="nav-item">
                                <a href="#faq">FAQ</a>
                            </li>
                        </ul>
                        <a href="https://interactrapp.com/login" class="btn btn-primary topbtn">Log In</a>
                        <a href="#pricing" class="btn btn-success topbtn">Sign Up</a>

                    </div>
                    <div class="btn-sec-row">
                        <a href="#pricing" class="btn btn-success topbtn">Get Now</a>
                    </div>
                </nav>
                <!-- Top navigation-->

            </div>
        </div>


        <header class="bannerbg">
            <div class="container">
                @if($page->banner_text)
                <div class="banner-wrapper">
                    <div class="banner-inner">
                        <div class="banner-text">
                            <p >
                                {!! $page->banner_text !!}
                            </p>
                        </div>
                        @if($page->has_timer)
                            <div class="banner-timer">
                                <div class="timer-wrapper-inner" >
                                <div class="timer-number">
                                    <p id="days" class="number">00</p>
                                    <p>days</p>
                                </div>
                                <div class="timer-divider">
                                    <p>:</p>
                                </div>
                                <div class="timer-number">
                                    <p id="hours" class="number">00</p>
                                    <p>hours</p>
                                </div>
                                <div class="timer-divider">
                                    <p>:</p>
                                </div>
                                <div class="timer-number">
                                    <p id="mins" class="number">00</p>
                                    <p>mins</p>
                                </div>
                                <div class="timer-divider">
                                    <p>:</p>
                                </div>
                                <div class="timer-number">
                                    <p id="secs" class="number">00</p>
                                    <p>secs</p>
                                </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @endif
            <!-- Top Banner-->
                <div class="banner-row">
                    <div class="ban-left">
                        <h1>Create Enterprise Level Interactive Videos In 3 Simple Steps</h1>
                        <p>Put your viewer in control of their experience! Let them interact with your video
                            content - making decisions, choosing their own adventure, answering questions and more</p>
                        <div class="get-btn web-display"><a class="btn" href="#pricing">Get Interactr Now</a></div>
                    </div>
                    <div class="ban-right">
                        <div class="banner-video-sec">
                            <iframe class="_vs_ictr_player" src="https://swiftcdn6.global.ssl.fastly.net/projects/5e6710c012913/index.html?cb=1xdxmxoq08f953p7rg0ajm" width=720 height=405 frameborder="0" allow="autoplay *" scrolling="no" ></iframe>
                            <script src="https://vsplayer.global.ssl.fastly.net/player-wrapper-v4.js"></script>
                        </div>
                        <div class="mob-display m-t-40">
                            <div class="get-btn"><a class="btn" href="#pricing">Get Interactr Now</a></div>
                        </div>

                    </div>
                </div>
                <!-- Top Banner-->
            </div>
        </header>

        <div class="block-sec" id="reviews"></div>

        <div  class="interactive-main">
            <div class="linebg"><img alt="img" src="https://i-fast.b-cdn.net/interactr.io/intro-bg.png"></div>
            <div class="container text-center">
                <h2  class="title">The <span>#1 Interactive</span> Video Platform</h2>
                <div class="interactive-sec">
                    <div class="interactive-col">
                        <div class="interactive-user-sec">
                            <div class="interactive-user-roundcol"><img alt="user" src="https://i-fast.b-cdn.net/interactr.io/user.png"></div>
                            <div class="interactive-user-col">
                                <h3 class="pretitle">Fabio Manente</h3>
                                <ul>
                                    <li><img alt="star" src="https://i-fast.b-cdn.net/interactr.io/star-icon.png"></li>
                                    <li><img alt="star" src="https://i-fast.b-cdn.net/interactr.io/star-icon.png"></li>
                                    <li><img alt="star" src="https://i-fast.b-cdn.net/interactr.io/star-icon.png"></li>
                                    <li><img alt="star" src="https://i-fast.b-cdn.net/interactr.io/star-icon.png"></li>
                                    <li><img alt="star" src="https://i-fast.b-cdn.net/interactr.io/star-icon.png"></li>
                                </ul>
                            </div>
                        </div>
                        <span class="border-30"></span>
                        <p class="simpletext pt-20">I've created a Tarot reading interactive video where the viewer gets
                            sent to a sales page at the end.  We had 11,000 views this month with
                            a 2X ROI on the product.  We're spending $1,500/day on average and
                            making around $2,800-$3,200 in sales!</p>
                    </div>
                    <div class="interactive-col">
                        <div class="interactive-user-sec">
                            <div class="interactive-user-roundcol"><img alt="user" src="https://i-fast.b-cdn.net/interactr.io/user-2.png"></div>
                            <div class="interactive-user-col">
                                <h3 class="pretitle">Murice Damion Miller</h3>
                                <ul>
                                    <li><img alt="star" src="https://i-fast.b-cdn.net/interactr.io/star-icon.png"></li>
                                    <li><img alt="star" src="https://i-fast.b-cdn.net/interactr.io/star-icon.png"></li>
                                    <li><img alt="star" src="https://i-fast.b-cdn.net/interactr.io/star-icon.png"></li>
                                    <li><img alt="star" src="https://i-fast.b-cdn.net/interactr.io/star-icon.png"></li>
                                    <li><img alt="star" src="https://i-fast.b-cdn.net/interactr.io/star-icon.png"></li>
                                </ul>
                            </div>
                        </div>
                        <span class="border-30"></span>
                        <p class="simpletext pt-20">I bought this software a few days ago and I absolutely love it.
                            I used it yesterday to create an interactive health and PE lesson for my students and it
                            turned out great on the first project! I will be using this for many more.</p>
                    </div>
                    <div class="interactive-col">
                        <div class="interactive-user-sec">
                            <div class="interactive-user-roundcol"><img alt="user" src="https://i-fast.b-cdn.net/interactr.io/user-3.png"></div>
                            <div class="interactive-user-col">
                                <h3 class="pretitle">Mem London</h3>
                                <ul>
                                    <li><img alt="star" src="https://i-fast.b-cdn.net/interactr.io/star-icon.png"></li>
                                    <li><img alt="star" src="https://i-fast.b-cdn.net/interactr.io/star-icon.png"></li>
                                    <li><img alt="star" src="https://i-fast.b-cdn.net/interactr.io/star-icon.png"></li>
                                    <li><img alt="star" src="https://i-fast.b-cdn.net/interactr.io/star-icon.png"></li>
                                    <li><img alt="star" src="https://i-fast.b-cdn.net/interactr.io/star-icon.png"></li>
                                </ul>
                            </div>
                        </div>
                        <span class="border-30"></span>
                        <p class="simpletext pt-20">Interactr has helped me win over £30,000 of business this year
                            so far.  It's basically my sales page - all obstacles are overcome using
                            the interactive video and because the viewer selects the options themselves,
                            the need for jumping on calls with prospects/time wasters is greatly reduced.
                            If they like what I sell, they can buy there and then inside the video.</p>
                    </div>
                </div>
            </div>

        </div>

        <div class="block-sec" id="features"></div>

        <div class="branching-sec mob-banch">
            <div class="container">
                <div class="branching-secin">
                    <div class="mob-display"><div class="brn-icon-sec"><span><img alt="icon" src="https://i-fast.b-cdn.net/interactr.io/brunching-icon.png"></span>Branching</div></div>
                    <h2 class="mob-title">Put Your Viewer In Control Of  Their Experience</h2>
                    <div class="branching-leftcol">
                        <img alt="branching" class="img-block" src="https://i-fast.b-cdn.net/interactr.io/branching-img1.png">
                    </div>
                    <div class="branching-rightcol">
                        <div class="brn-icon-sec web-display"><span><img alt="icon" src="https://i-fast.b-cdn.net/interactr.io/brunching-icon.png"></span>Branching</div>
                        <h2 class="title pr-50 mob-head">Put Your Viewer In Control Of  Their Experience</h2>
                        <p class="simpletext pr-90">Let your viewer interact with your video - choosing their own adventure,
                            making decisions, answering questions and more</p>
                        <div class="get-btn"><a class="btn" href="#pricing">Get Interactr Now</a></div>
                    </div>

                </div>
            </div>
        </div>

        <div class="branching-sec mob-sec-m">
            <div class="container">
                <div class="branching-secin">
                    <div class="mob-display"><div class="brn-icon-sec mob-sec"><span><img alt="mobile" src="https://i-fast.b-cdn.net/interactr.io/mobile-icon.png"></span>Mobile</div></div>
                    <h2 class="mob-title">Play Anywhere On All Devices</h2>
                    <div class="branching-leftcol builder-2">
                        <div class="brn-icon-sec mob-sec web-display"><span><img alt="mobile" src="https://i-fast.b-cdn.net/interactr.io/mobile-icon.png"></span>Mobile</div>
                        <h2 class="title pr-50 mob-head">Play Anywhere On All Devices</h2>
                        <p class="simpletext pr-90">Our interactive videos play on all browsers and on all devices - including tablets and mobile phones</p>
                        <div class="get-btn"><a class="btn" href="#pricing">Get Interactr Now</a></div>
                    </div>
                    <div class="branching-rightcol">
                        <img class="mobilesec-img" alt="mobile" src="https://i-fast.b-cdn.net/interactr.io/mobile.png">
                    </div>

                </div>
            </div>
        </div>


        <div class="branching-sec automation-sec">
            <div class="container">
                <div class="branching-secin">
                    <div class="mob-display">
                        <div class="brn-icon-sec auto-sec"><span><img alt="icon" src="https://i-fast.b-cdn.net/interactr.io/automation-icon.png"></span>Automation</div>
                    </div>
                    <h2 class="mob-title">Create Personalized Custom Audiences</h2>
                    <div class="branching-leftcol auto-l">
                        <img class="autosec-img img-block" alt="automation" src="https://i-fast.b-cdn.net/interactr.io/automation.png">
                    </div>
                    <div class="branching-rightcol auto-r">
                        <div class="brn-icon-sec auto-sec web-display"><span><img alt="icon" src="https://i-fast.b-cdn.net/interactr.io/automation-icon.png"></span>Automation</div>
                        <h2 class="title mob-head">Create Personalized Custom Audiences</h2>
                        <p class="simpletext">Automatically add viewers to personalized custom audiences inside of
                            Facebook depending on how they interact with your video</p>
                        <div class="get-btn"><a class="btn" href="#pricing">Get Interactr Now</a></div>
                    </div>

                </div>
            </div>
        </div>

        <div class="branching-sec mob-sec-m">
            <div class="container">
                <div class="branching-secin">
                    <div class="mob-display">
                        <div class="brn-icon-sec analytics-sec"><span><img alt="analytics" src="https://i-fast.b-cdn.net/interactr.io/analytics-icon.png"></span>Analytics</div>
                    </div>
                    <h2 class="mob-title">Smarter Business Moves With Better Data</h2>
                    <div class="branching-leftcol builder-2">
                        <div class="brn-icon-sec analytics-sec web-display"><span><img alt="analytics" src="https://i-fast.b-cdn.net/interactr.io/analytics-icon.png"></span>Analytics</div>
                        <h2 class="title pr-50 mob-head">Smarter Business Moves With Better Data</h2>
                        <p class="simpletext pr-90">Optimize your interactive videos for even greater results with our detailed analytics -
                            you can see video drop off, interactions, conversions, revenues, and much more</p>
                        <div class="get-btn"><a class="btn" href="#pricing">Get Interactr Now</a></div>
                    </div>
                    <div class="branching-rightcol">
                        <img class="mobilesec-img" alt="analytics" src="https://i-fast.b-cdn.net/interactr.io/analytics.png">
                    </div>

                </div>
            </div>
        </div>

        <div class="block-sec" id="cases"></div>

        <div class="video-slide-sec">
            <div class="container">
                <h2 class="title">Interactive Video Outperforms<span></span> Normal Video At Every Turn</h2>
                <div id="owl-demo" class="owl-carousel owl-theme">
                    <div class="item">
                        <div class="video-sec">
                            <div class="video-sec-col-l">
                                <img alt="logo" src="https://i-fast.b-cdn.net/interactr.io/video-sec-logo.png">
                                <h2 class="title">Interactive Landing Page Conversion Video</h2>
                                <p class="simpletext">Gaiam TV used interactive video to double their subscription revenue in
                                    4 weeks whilst reducing shopping cart abandonment by 3X.</p>
                            </div>
                            <div class="video-sec-col-r"><img alt="img" src="https://i-fast.b-cdn.net/interactr.io/video-img.jpg"></div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="video-sec">
                            <div class="video-sec-col-l">
                                <img alt="video" class="videoimg2 newtn-logo" src="https://i-fast.b-cdn.net/interactr.io/video-sec-logo2.png">
                                <h2 class="title">Interactive Product Selection Video</h2>
                                <p class="simpletext">Newton Running achieved a 90 percent completion
                                    rate whilst reducing the time spent by call
                                    center reps created by shopper product confusion - because shoppers
                                    were able to better understand what shoes were best for them.</p>
                            </div>
                            <div class="video-sec-col-r"><img alt="video" src="https://i-fast.b-cdn.net/interactr.io/video-img2.jpg"></div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="video-sec">
                            <div class="video-sec-col-l">
                                <img alt="logo" src="https://i-fast.b-cdn.net/interactr.io/video-sec-logo3.png">
                                <h2 class="title"> Interactive Product Tutorial Video</h2>
                                <p class="simpletext">Maybelline New York used interactive video to achieve a purchase rate 14X
                                    HIGHER than the industry average.</p>
                            </div>
                            <div class="video-sec-col-r"><img alt="video" src="https://i-fast.b-cdn.net/interactr.io/video-img3.jpg"></div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="video-sec">
                            <div class="video-sec-col-l">
                                <img alt="video" src="https://i-fast.b-cdn.net/interactr.io/video-sec-logo4.png">
                                <h2 class="title">Interactive Sales Page Video</h2>
                                <p class="simpletext">The first time we used interactive video it gave us an 83% increase
                                    in conversions which drove an EXTRA $58,743 in sales in a single month.</p>
                            </div>
                            <div class="video-sec-col-r"><img alt="video" src="https://i-fast.b-cdn.net/interactr.io/video-img4.jpg"></div>
                        </div>
                    </div>

                    <div class="item">
                        <div class="video-sec">
                            <div class="video-sec-col-l">
                                <img alt="logo" class="videoimg2" src="https://i-fast.b-cdn.net/interactr.io/video-sec-logo5.png">
                                <h2 class="title">Interactive Non-Profit Volunteer Video</h2>
                                <p class="simpletext">This non-profit video beat application targets by 972%
                                    using interactive video whilst simultaneously decreasing the cost
                                    per application by 900% lower than the usual target cost per volunteer.</p>
                            </div>
                            <div class="video-sec-col-r"><img alt="video" src="https://i-fast.b-cdn.net/interactr.io/video-img5.jpg"></div>
                        </div>
                    </div>

                    <div class="item">
                        <div class="video-sec">
                            <div class="video-sec-col-l">
                                <img alt="video" class="videoimg2" src="https://i-fast.b-cdn.net/interactr.io/video-sec-logo6.png">
                                <h2 class="title">Interactive Recruitment Video</h2>
                                <p class="simpletext">An interactive video created by recruitment
                                    advertising agency Havas People, "A Taste of AB InBev" won a 2015 RAD award - a huge honor
                                    in the recruitment marketing industry.</p>
                            </div>
                            <div class="video-sec-col-r"><img alt="video" src="https://i-fast.b-cdn.net/interactr.io/video-img6.jpg"></div>
                        </div>
                    </div>


                </div>
            </div>
        </div>


        <div class="build-sec">
            <div class="container">
                <h2 class="title">Ready To <span>Build Your</span> Own Interactive Experience?</h2>

                <div class="get-btn-sec"><div class="get-btn"><a class="btn" href="#pricing">Let's Do This</a></div></div>
            </div>
        </div>

        <div class="block-sec" id="testimonials"></div>

        <div class="testimoni-sec">
            <h2 class="title">What Our Clients Say</h2>
            <div class="container">
                <div class="testi-top-sec">
                    <div class="testi-top-col">
                        <div class="test-box">
                            <div style="padding:56.25% 0 0 0;position:relative;">
                                <iframe src="https://player.vimeo.com/video/604064991?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479&amp;h=96111b5b56" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;" title="Nick Papps - Interactr Testimonial.mp4"></iframe>
                            </div>
                        </div>
                        <h3 class="pretitle">Nick Papps</h3>
                        <p class="simpletext">Videographer</p>
                    </div>

                    <div class="testi-top-col">
                        <div class="test-box">
                            <div style="padding:56.25% 0 0 0;position:relative;"><iframe src="https://player.vimeo.com/video/604075902?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479&amp;h=9183b80ca0" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;" title="Adam Regener - Interactr Testimonial.mp4"></iframe></div>
                        </div>
                        <h3 class="pretitle">Adam Regener</h3>
                        <p class="simpletext">Teacher</p>
                    </div>

                    <div class="testi-top-col">
                        <div class="test-box">
                            <div style="padding:56.25% 0 0 0;position:relative;"><iframe src="https://player.vimeo.com/video/604072779?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479&amp;h=250ca2df0d" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;" title="Rebekah Richards - Interactr Testimonial"></iframe></div>
                        </div>
                        <h3 class="pretitle">Rebekah Richards</h3>
                        <p class="simpletext">Marketer</p>
                    </div>

                </div>

                <div class="testi-bot-sec">
                    <div><img alt="img" src="https://i-fast.b-cdn.net/interactr.io/testi-quite.png"></div>
                    <p class="simpletext">Video conversion is all about engaging your audience, and Interactr is an absolute
                        beast at that. Not sure what objections you need to knock down? Use Interactr and it doesn't matter,
                        because your viewer can choose what's most interesting to them. Interactr is an app I want to keep coming back to.
                        I don't think I've ever had quite as much fun putting videos together. Using Interactr makes making videos stop being a
                        job to avoid and becomes something you look forward to doing, it's brilliant.</p>
                    <div class="testi-img-round"><img alt="img" src="https://i-fast.b-cdn.net/interactr.io/testi-round-img.png"></div>
                    <h3 class="pretitle">Neil Murton</h3>
                    <p class="simpletext p-0">Co-founder Convertri</p>
                </div>


            </div>
        </div>



        <div class="recap-section">
            <div class="container">
                <h2 class="title">A Quick Recap Of Everything You Get
                    When You Buy Interactr Today</h2>
                <ul>
                    <li>
                        <div class="round-icon"><img alt="icon" src="https://i-fast.b-cdn.net/interactr.io/recap-icon1.png"></div>
                        <h3 class="pretitle">Interactr</h3>
                        <p class="simpletext">Get lifetime access to Interactr, with no recurring fees to pay, ever.</p>
                    </li>
                    <li>
                        <div class="round-icon light-green"><img alt="icon" src="https://i-fast.b-cdn.net/interactr.io/recap-icon2.png"></div>
                        <h3 class="pretitle">Unlimited Projects</h3>
                        <p class="simpletext">Create unlimited interactive video projects with no limitations on upload limit or number of videos created.</p>
                    </li>
                    <li>
                        <div class="round-icon light-orange"><img alt="icon" src="https://i-fast.b-cdn.net/interactr.io/recap-icon3.png"></div>
                        <h3 class="pretitle">Commercial Use</h3>
                        <p class="simpletext">You can sell your interactive videos to clients as you get a full commercial license included.</p>
                    </li>
                    <li>
                        <div class="round-icon light-perple"><img alt="icon" src="https://i-fast.b-cdn.net/interactr.io/recap-icon4.png"></div>
                        <h3 class="pretitle">{!! $page->custom_content_one !!}</h3>
                        <p class="simpletext">{!! $page->custom_content_two !!}</p>
                    </li>
                    <li>
                        <div class="round-icon light-pink"><img alt="icon" src="https://i-fast.b-cdn.net/interactr.io/recap-icon5.png"></div>
                        <h3 class="pretitle">Fast Customer Service</h3>
                        <p class="simpletext">We have a dedicated support team that guarantees to get back to you within one business day.</p>
                    </li>
                    <li>
                        <div class="round-icon light-blue"><img alt="icon" src="https://i-fast.b-cdn.net/interactr.io/recap-icon6.png"></div>
                        <h3 class="pretitle">Money Back Guarantee</h3>
                        <p class="simpletext">Try out Interactr risk free for 30 days and if you don't like it you will get a full and prompt refund.</p>
                    </li>
                    <li>
                        <div class="round-icon light-perp"><img alt="icon" src="https://i-fast.b-cdn.net/interactr.io/recap-icon7.png"></div>
                        <h3 class="pretitle">Full Support & Updates</h3>
                        <p class="simpletext">Interactr is supported by a full-time team of designers and developers who have been constantly updating and supporting Interactr since 2016.</p>
                    </li>
                    <li>
                        <div class="round-icon light-sky"><img alt="icon" src="https://i-fast.b-cdn.net/interactr.io/recap-icon8.png"></div>
                        <h3 class="pretitle">Play On Any Device</h3>
                        <p class="simpletext">Our interactive videos play on all browsers and all devices - including tablets and mobile phones. </p>
                    </li>

                </ul>
            </div>
        </div>

        <div class="block-sec" id="pricing"></div>

        <div class="save-bog-sec">
            <h2 class="title">Save Big With Interactr</h2>
            <div class="container">
                <div class="save-bog-sec-in">
                    <div class="save-bog-sec-in-col border-col">
                        <img alt="logo" src="{!! config('logos.interactr') !!}" class="img-fluid" style="padding-left: 100px;padding-right: 100px">
                        <h4><span>${!! $page->price !!}</span> one time payment</h4>
                        <div class="get-btn m-t-0"><a class="btn" href="{!! $page->buy_button_one !!}">Get Interactr Now</a></div>
                        <ul class="paymentlist">
                            <li>Special offer 65% off</li>
                            <li>Unlimited video projects</li>
                            <li>Commercial license included</li>
                            <li>Superstar support</li>
                            <li>30-day money back guarantee</li>
                        </ul>
                        @if($page->price_two)
                            <p>Normally <del>${!! $page->price_two !!}</del></p>
                        @endif
                        <p>Today <span>${!! $page->price !!}</span> One Time Payment</p>
                        <div class="get-btn"><a class="btn" href="{!! $page->buy_button_one !!}">Get Interactr Now</a></div>
                    </div>
                    <div class="save-bog-sec-in-col-right">
                        <div class="vs">VS</div>
                        <div class="logo-list">
                            <ul>
                                <li>
                                    <div class="logo-sec">
                                        <img alt="logo" src="https://i-fast.b-cdn.net/interactr.io/save-big-logo1.png">
                                    </div>
                                    <div class="text-sec">
                                        <p>Rapt: <strong>$7,200/year</strong></p>
                                    </div>
                                </li>
                                <li>
                                    <div class="logo-sec">
                                        <img alt="logo" src="https://i-fast.b-cdn.net/interactr.io/save-big-logo2.png">
                                    </div>
                                    <div class="text-sec">
                                        <p>Wirewax: <strong>$1,100/month</strong></p>
                                    </div>
                                </li>
                                <li>
                                    <div class="logo-sec">
                                        <img alt="logo" src="https://i-fast.b-cdn.net/interactr.io/save-big-logo3.png">
                                    </div>
                                    <div class="text-sec">
                                        <p>Hapyak: <strong>$1,500/month</strong></p>
                                    </div>
                                </li>
                                <li>
                                    <div class="logo-sec">
                                        <img alt="logo" src="https://i-fast.b-cdn.net/interactr.io/save-big-logo4.png">
                                    </div>
                                    <div class="text-sec">
                                        <p>Dot: <strong>$802/month</strong></p>
                                    </div>
                                </li>
                                <li>
                                    <div class="logo-sec">
                                        <img alt="logo" src="https://i-fast.b-cdn.net/interactr.io/save-big-logo5.png">
                                    </div>
                                    <div class="text-sec">
                                        <p>Near-life: <strong>$5,426/year</strong></p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="money-sec">
            <div class="container">
                <div class="blue-sec">
                    <div class="batch-img"><img alt="img" src="https://i-fast.b-cdn.net/interactr.io/batch-img.png"></div>
                    <h2 class="title">30 Day Money Back Guarantee</h2>
                    <p>Test Interactr yourself for 30 full days, and enjoy the <strong>power of today's</strong>
                        most advanced <strong>video technology</strong>. See how easy it is to drastically
                        increase <strong>viewer engagement</strong> with your videos.</p>
                    <p>If you're not completely <strong>satisfied</strong> with your investment let us know within 30 days
                        and we will refund your <strong>purchase</strong> in full.</p>
                </div>
            </div>
        </div>


        <div class="block-sec" id="faq"></div>

        <div class="faq-sec">
            <div class="container">
                <h2 class="title">Frequently Asked Questions</h2>
                <div class="accordion accordion-flush" id="faqlist">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-content-1">
                                1. How long is this deal available?
                            </button>
                        </h2>
                        <div id="faq-content-1" class="accordion-collapse collapse show" data-bs-parent="#faqlist">
                            <div class="accordion-body">
                                This discount is only available for a limited time.  In the future we will be increasing the current pricing to a higher monthly fee.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-content-2">
                                2. Does this work on mobile?
                            </button>
                        </h2>
                        <div id="faq-content-2" class="accordion-collapse collapse" data-bs-parent="#faqlist">
                            <div class="accordion-body">
                                Yes, your interactive videos play flawlessly across all browsers and all devices, including mobile phones.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-content-3">
                                3. What about support, training, and updates?
                            </button>
                        </h2>
                        <div id="faq-content-3" class="accordion-collapse collapse" data-bs-parent="#faqlist">
                            <div class="accordion-body">
                                Yes, of course, and absolutely!
                                <span></span>
                                Your dashboard includes a direct link to our professional, friendly support team, happy to answer any questions that might come up.
                                <span></span>
                                Over the shoulder video training is accessible in the dashboard also, covering everything from getting started right through to optimizing your campaigns.
                                <span></span>
                                And we automatically push updates so you’ll always have the latest version of the software.  Interactr is supported by a full-time team of designers and developers who have been constantly updating and supporting Interactr since 2016.

                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-content-4">
                                4. Do you have a refund policy?
                            </button>
                        </h2>
                        <div id="faq-content-4" class="accordion-collapse collapse" data-bs-parent="#faqlist">
                            <div class="accordion-body">
                                Yes - we have a 30 day money back guarantee that allows you to try out our software risk free for 30 days.  If you don't like it, simply reach out to customer support for a complete refund and you'll get your money back without any hassle.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-content-5">
                                5. How do I contact support?
                            </button>
                        </h2>
                        <div id="faq-content-5" class="accordion-collapse collapse" data-bs-parent="#faqlist">
                            <div class="accordion-body">
                                You can get in touch with our support team either via email at <a href="mailto:{!! config('links.support_email') !!}">support@videosuite.io</a> or alternatively by visiting our support desk at <a target="_blank" href="{!! config('links.support') !!}">{!! config('links.support') !!}</a>
                            </div>
                        </div>
                    </div>


                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-content-6">
                                6.  Do These videos work with Social Media?
                            </button>
                        </h2>
                        <div id="faq-content-6" class="accordion-collapse collapse" data-bs-parent="#faqlist">
                            <div class="accordion-body">
                                <p style="margin-bottom: 10px">Yes and No.</p>
                                <p style="margin-bottom: 10px">The main social media platforms do not currently support HTML5 video, therefore they do not support interactive video.</p>
<p style="margin-bottom: 10px">We do, however, allow you to share the links of your interactive videos on social media with a thumbnail, headline and description so people can click to go to your interactive video landing page to view your video.</p>
<p style="margin-bottom: 10px">This means you can share your interactive video on social media, but you can't embed your video on social media.</p>
<p style="margin-bottom: 10px">The reason that is okay in terms of sales is because social media is best used as a traffic source to get people to your landing page/funnel.</p>
<p style="margin-bottom: 5px">The reasons you don't want to host your video on social media are:</p>
<p style="margin-bottom: 5px">1.  You don't own the Facebook platform therefore you have no control over it - you want to host your interactive video on a platform [your own funnel or website] so it can't be altered or shut down by a 3rd party.</p>
<p style="margin-bottom: 5px">2. You can't pixel high intent audiences if your interactive video is on Facebook - which means you would be leaving a lot of sales on the table by not being able to create custom or retargeting audiences of people that are interested enough to view your sales page/sales video etc.</p>
<p style="margin-bottom: 10px">(It's why you don't see websites or funnels on Social Media).</p>
<p style="margin-bottom: 10px">You want to host your interactive video on your landing page and use social media to drive traffic to that page, just like we do.</p>
                            </div>
                        </div>
                    </div>



                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-content-7">
                                7. I'm interested but not if I can't export the videos to my computer or phone. Please let me know if that's possible?
                            </button>
                        </h2>
                        <div id="faq-content-7" class="accordion-collapse collapse" data-bs-parent="#faqlist">
                            <div class="accordion-body">
                                The magic that makes video interactive, is the video player. Because of this, it's impossible for any interactive video company to allow the user to download the full interactive video to their computer or phone.
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>



    </div>


    <footer>
        <div class="container">
            <div class="d-flex">
                <div class="f-col-1">
                    <img alt="logo" src="{!! config('logos.interactr') !!}" style="width: 171px">
                    <p>A video software by VideoSuite Limited.</p>
                    <p>This site is not a part of the Facebook website or Facebook Inc.
                        Additionally, this site is NOT endorsed by Facebook in any way.
                        Facebook is a trademark of Facebook, Inc.</p>
                </div>
                <div class="f-col-2">
                    <p>Product</p>
                    <ul>
                        <li><a href="#reviews">Reviews</a></li>
                        <li><a href="#features">Features</a></li>
                        <li><a href="#cases">Use Cases</a></li>
                        <li><a href="#pricing">Pricing</a></li>
                    </ul>
                </div>
                <div class="f-col-2">
                    <p>Resources</p>
                    <ul>
                        <li><a target="_blank" href="{!! config('links.support') !!}">Help Centre</a></li>
                        <li><a target="_blank" href="{!! config('links.contact') !!}">Contact Support</a></li>
                    </ul>
                </div>
                <div class="f-col-3">
                    <p>Contact Us</p>
                    <ul>
                        <li><a target="_blank" href="{!! config('links.support') !!}">Contact Us</a></li>
                    </ul>
                </div>

            </div>
        </div>

        <div class="f-bottom-sec">
            <div class="container">
                <div class="d-flex"><p class="copyright">interactr.io - A VideoSuite Product - All Rights Reserved 2021</p>
                    <p><a target="_blank" href="{!! config('links.admin') !!}"> Admin </a><span>|</span> <span><a target="_blank" href="{!! config('links.privacy_policy') !!}"> Privacy Policy </a> <span>|</span><a target="_blank" href="{!! config('links.terms_of_service') !!}">Terms Of Service</a> <span>|</span><a target="_blank" href="{!! config('links.support') !!}">Support</a></p>
                </div>
            </div>
        </div>

    </footer>

</div>

@include('layouts.interactr-scripts')
</body>
</html>
