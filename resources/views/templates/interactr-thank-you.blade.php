<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
@include('layouts.interactr-head')
@include('partials.facebook-pixel')
@include('partials.custom-scripts')

<!-- Paykickstart Tracking Snippet -->
    <script type="text/javascript" src="https://app.paykickstart.com/tracking-script"></script>
    <!-- End Paykickstart Tracking Snippet -->

</head>
<body>
    <!-- Page content wrapper-->
    <div id="wrapper">
        <div class="tutorial-landing-bg">
            <div class="top-menu">
                <div class="container">
                <!-- Top navigation-->
                    <div class="top-logo-row">
                        <a class="navbar-brand" href="#"><img alt="INTERACTR" src="{!! config('logos.interactr') !!}" style="width: 171px;height: 33px;" /></a>
                        <div class="hdr-login-sign-btn">
                            <a href="https://interactrapp.com/login" target="_blank" class="btn btn-primary topbtn">Log In</a>
                            <a href="{!! config('links.support') !!}" target="_blank" class="btn btn-success topbtn">Support</a>
                        </div>
                    </div>
                   <!-- Top navigation-->
                </div>
            </div>
            <div class="landing-top-sec tutorial-banner-caption">
                <div class="container">
                    <h1>Welcome To The Members Area</h1>
                    <p>First of all, we want to <strong>THANK YOU for joining Interactr.</strong> We’re really excited to have you here!</p>
                    <p>You will receive an e-mail within the next 10 minutes with your login password. You can access the software at <a href="https://interactrapp.com/" target="_blank">https://interactrapp.com/</a></p>
                    <p>If you purchased Interactr masterclass, you will receive an email from Kajabi with your login details.  You can access the Masterclass training at <a target="_blank" href="http://videosuite.mykajabi.com/products/interactr-masterclass">http://videosuite.mykajabi.com/products/interactr-masterclass</a></p>
                </div>
            </div>
            <div class="tutorial-banner-faq-link-area">
                <div class="container">
                    <div class="tutorial-banner-faq-link-row">
                        <aside>
                            <figure><img src="https://i-fast.b-cdn.net/interactr.io/question.svg" alt=""></figure>
                            <p>If you have any other questions, please visit our FAQ page at <span><a href="#">https://support.videosuite.io/</a></span></p>
                        </aside>
                        <aside>
                            <figure><img class="m-i" src="https://i-fast.b-cdn.net/interactr.io/email.svg" alt=""></figure>
                            <p>For any questions not answered in the FAQ’s, please contact our support team directly at  <span><a href="https://support.videosuite.io/contact" target="_blank">https://support.videosuite.io/contact</a></span></p>
                        </aside>
                    </div>
                </div>
            </div>
        </div>

        

        <section class="miss-upgrades-sec">
            <div class="container">
                <h2 class="title">Did you miss any of the upgrades?</h2>
                <div class="miss-upgrades-row">
                    <div class="miss-upgrades-item tu-upgrade r-border">
                        <figure>
                            <img src="https://i-fast.b-cdn.net/interactr.io/builder-img3.png" alt="">
                        </figure>
                        <h3 class="title">Interactr Pro</h3>
                        <ul class="dflt-list-item">
                            <li>Pro upgrade includes access to customizable pop up templates, next generation video interaction, an interactive survey + quiz builder, and more…</li>
                            <li>Over 250,000 premium stock videos</li>
                            <li>Complete interactive video campaign templates</li>
                        </ul>
                        <div class="get-btn of-bt"><a class="btn" href="https://interactr.io/upgrade/pro" target="_blank">go to offer</a></div>
                    </div>
                    <div class="miss-upgrades-item tu-upgrade r-border">
                        <figure>
                            <img src="https://i-fast.b-cdn.net/interactr.io/campaign-img3.png" alt="">
                        </figure>
                        <h3 class="title">Interactr Agency</h3>
                        <ul class="dflt-list-item">
                            <li>White-label the Interactr software with custom app branding, custom https domain, and custom pages</li>
                            <li>Create team and sub-user accounts</li>
                            <li>Use the interactr marketing material including animated, interactive explainer videos, interactive landing pages and case studies</li>
                        </ul>
                        <div class="get-btn of-bt"><a class="btn" href="https://interactr.io/upgrade/agency" target="_blank">go to offer</a></div>
                    </div>
                    <div class="miss-upgrades-item tu-upgrade">
                        <figure>
                            <img src="https://i-fast.b-cdn.net/interactr.io/masterclass.png" alt="">
                        </figure>
                        <h3 class="title im-texth2">Interactr Masterclass</h3>
                        <ul class="dflt-list-item">
                            <li>From scratch to $50,000 per month 
                                video agency.  Step by step modules 
                                show you how it's done</li>
                            <li>Learn battle tested methods to 
                                attract leads 24/7 who want to buy 
                                interactive videos from you</li>
                            <li>Real world case study shows you how 
                                to sell videos in the real world with 
                                real customers</li>
                        </ul>
                        <div class="get-btn of-bt"><a class="btn" href="https://interactr.io/upgrade/masterclass" target="_blank">go to offer</a></div>
                    </div>

                </div>
            </div>
        </section>
        <section class="tutorials-video-section">
            <div class="container">
                <h2 class="title">Interactr Tutorials</h2>
                <div class="tutorial-video-row">
                    <div class="tutorial-video-item">
                        <div class="tutorial-video-bg">
                            <div class="tutorial-video">
                                <iframe src="https://player.vimeo.com/video/456529319?h=2e4a862b73" allowfullscreen></iframe>
                            </div>
                        </div>
                        <h3>Video #1</h3>
                        <h4 class="title">Interactr Dashboard Layout</h4>
                    </div>
                    <!-- <div class="tutorial-video-item">
                        <div class="video-bg">
                            <img class="templates-img img-block" alt="bg" src="assets/images/video-bg.png">
                              <div class="diveo-col">                                    
                                <iframe src="https://player.vimeo.com/video/456529319?h=2e4a862b73" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                              </div>  
                        </div>
                        <h3>Video #1</h3>
                        <h4 class="title">Interactr Dashboard Layout</h4>
                    </div> -->
                    <div class="tutorial-video-item">
                        <div class="tutorial-video-bg">
                            <div class="tutorial-video">
                                <iframe src="https://player.vimeo.com/video/456530295?h=2962af28f5" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        </div>
                        <h3>Video #2</h3>
                        <h4 class="title">Create A Project</h4>
                    </div>
                    <div class="tutorial-video-item">
                        <div class="tutorial-video-bg">
                            <div class="tutorial-video">
                                <iframe src="https://player.vimeo.com/video/456530875?h=2ed36f8b62" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        </div>
                        <h3>Video #3</h3>
                        <h4 class="title">Canvas Overview</h4>
                    </div>
                    <div class="tutorial-video-item">
                        <div class="tutorial-video-bg">
                            <div class="tutorial-video">
                                <iframe src="https://player.vimeo.com/video/456534087?h=21d945f96b" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        </div>
                        <h3>Video #4</h3>
                        <h4 class="title">Interactive Elements</h4>
                    </div>
                    <div class="tutorial-video-item">
                        <div class="tutorial-video-bg">
                            <div class="tutorial-video">
                                <iframe src="https://player.vimeo.com/video/456538483?h=71c30872a4" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        </div>
                        <h3>Video #5</h3>
                        <h4 class="title">Animated Interaction Layers</h4>
                    </div>
                    <div class="tutorial-video-item">
                        <div class="tutorial-video-bg">
                            <div class="tutorial-video">
                                <iframe src="https://player.vimeo.com/video/456539872?h=55d264e2a5" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        </div>
                        <h3>Video #6</h3>
                        <h4 class="title">Enabling Chapters</h4>
                    </div>
                    <div class="tutorial-video-item">
                        <div class="tutorial-video-bg">
                            <div class="tutorial-video">
                                <iframe src="https://player.vimeo.com/video/456540404?h=7bedd329a7" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        </div>
                        <h3>Video #7</h3>
                        <h4 class="title">Publishing Your Video</h4>
                    </div>
                    <div class="tutorial-video-item">
                        <div class="tutorial-video-bg">
                            <div class="tutorial-video">
                                <iframe src="https://player.vimeo.com/video/456541886?h=25fbded6fc" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        </div>
                        <h3>Video #8</h3>
                        <h4 class="title">Sharing Your Video</h4>
                    </div>
                    <div class="tutorial-video-item">
                        <div class="tutorial-video-bg">
                            <div class="tutorial-video">
                                <iframe src="https://player.vimeo.com/video/456542512?h=051fb57b29" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        </div>
                        <h3>Video #9</h3>
                        <h4 class="title">Video Analytics</h4>
                    </div>
                    <div class="tutorial-video-item">
                        <div class="tutorial-video-bg">
                            <div class="tutorial-video">
                                <iframe src="https://player.vimeo.com/video/456547139?h=3abb8ab466" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        </div>
                        <h3>Video #10</h3>
                        <h4 class="title">Project Settings</h4>
                    </div>
                </div>
            </div>
        </section>

        <section class="join-fb-group">
            <div class="container">
                <!-- <h2>Click Here To Join Our <a href="#"><img src="assets/images/fb-img.png" alt=""></a>Group</h2> -->
                <h2>Click Here To Join Our <a href="https://www.facebook.com/groups/interactrusers" target="_blank">Facebook</a>Group</h2>
            </div>
        </section>

        <section class="still-question-sec">
            <div class="container">
                <h2 class="title">Still Have Questions?</h2>
                <div class="still-question-row">
                    <div class="still-question-item">
                        <figure><img src="https://i-fast.b-cdn.net/interactr.io/question.svg" alt=""></figure>
                        <aside>
                            <h3 class="title">Have you checked the FAQs?</h3>
                            <p>In effort to save time and energy, we’ve compiled FAQs based on the most common questions we’ve come across.</p>
                            <p>We encourage you to visit our FAQ page first, before submitting a support ticket.</p>
                        </aside>
                        <div class="get-btn of-bt"><a class="btn" href="https://support.videosuite.io/category/17-faqs" target="_blank">go to FAQs</a></div>
                    </div>
                    <div class="still-question-item">
                        <figure><img class="m-i" src="https://i-fast.b-cdn.net/interactr.io/email.svg" alt=""></figure>
                        <aside>
                            <h3 class="title">Contact Support</h3>
                            <p>Our customer happiness representatives are patiently standing by for your questions.</p>
                            <p>We pride ourselves on quick response times. While most questions will be answered almost immediately, please allow up to 48 hours for a personalized response.</p>
                        </aside>
                        <div class="get-btn of-bt"><a class="btn" href="{!! config('links.support') !!}">CONTACT SUPPORT</a></div>
                    </div>
                </div>
            </div>
        </section>

        <footer class="footer-sec">
            <div class="container">                                           
                <a class="navbar-brand" href="#"><img alt="INTERACTR" src="{!! config('logos.interactr') !!}" style="width: 171px;height: 33px;"></a>
                <p class="videosuite">A video software by VideoSuite</p>  
                <p class="copyright">interactr.io - A VideoSuite Product - All Rights Reserved 2021</p>                  
            </div>

            <div class="f-bottom-sec">
                <div class="container">
                    <div class="d-flex">
                        <p></p>
                        <p><a target="_blank" href="{!! config('links.privacy_policy') !!}"> Privacy Policy </a> <span>|</span><a target="_blank" href="{!! config('links.terms_of_service') !!}">Terms Of Service</a></p>
                    </div>
                </div>
            </div>

        </footer>
        
    </div>

   <!-- JS Start here -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/jquery.min.js"><\/script>')</script>       
    <!-- Bootstrap core JS-->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- Owl Carousel JS-->
    <script src="assets/js/owl.carousel.min.js"></script>
    <!-- Core theme JS-->
    <script src="assets/js/scripts.js"></script>
</body>
</html>
