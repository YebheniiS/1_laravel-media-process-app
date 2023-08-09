<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.interactr-head')
    @include('partials.facebook-pixel')
    @include('partials.custom-scripts')

    <!-- Paykickstart Tracking Snippet -->
        <script type="text/javascript" src="https://app.paykickstart.com/tracking-script"></script>
    <!-- End Paykickstart Tracking Snippet -->

    <style >
        .testimonial-card {
            background: white;
            overflow: hidden;
        }
        body {
            background: url(/interactr_assets/images/banner-bg.png) 0 0 no-repeat;
            background-size: 100% auto;
        }
        .list {
            padding-left: 20px;
        }
        .list li {
            margin-bottom: 7px;
        }
        li img {
            height: 15px;
            width: 15px;
            margin-right: 5px;
        }
        .testimonials-list {
            padding: 0 5px;
            padding-top: 10px;
        }
        .testimonials-list img {
            margin-bottom: 30px;
            box-shadow: 0 0 8px rgb(0 0 0 / 9%);
        }
        h1 {
            font-size: 3.429703125rem;
        }
        h3 {
            font-size:1.63125rem;
        }
        .header {
            margin-top:80px;
            padding-bottom: 20px
        }
        iframe {margin: 20px}
        @media (max-width: 500px) {
            h1 {
                font-size: 1.953125rem;
            }
            h3 {
                font-size: 1.25rem;
            }
            .header {
                margin-top: 25px;
            }
            iframe {margin: 0}
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row align-items-center" style="height:5em;margin-top: 0.5em;">
        <div class="col">
            <a class="navbar-brand" href="#" style="width:271px;" ><img alt="INTERACTR" src="{!! config('logos.interactr') !!}" class="img-fluid" style="margin-top: 7px;width:271px;"></a>
        </div>
        <div class="col hidden-xs text-right">
            <a href="{!! config('links.support') !!}" class="btn btn-primary float-end topbtn">Support</a>
        </div>
    </div>

    <div class="header">
        <div>
            <h1 style="text-align: center">Interactr Special Offer</h1>
            <h3 style="text-align: center">1-Time Price (No monthly fees ever)</h3>
        </div>
    </div>

    <div class="max-w-4xl mx-auto grid grid-cols-1 lg:grid-cols-2">
        <div class="px-4 md:px-8 lg:px-0">
            <div  class="rounded-md shadow-lg mt-4 overflow-hidden min-h-screen bg-white lg:mb-12 mb-4 bg-white">
                <div class="row">
                    <div class="col-sm-8">
                        <iframe src="https://app.paykickstart.com/checkout-embed/{!! $page->custom_content_one !!}" width="100%" scrolling="no" frameborder="0" style="border: 2px solid #f2f2f2;"></iframe>
                        <script type="text/javascript" src="https://app.paykickstart.com/checkout/embed_forms/iframe.js"></script>
                    </div>
                    <div class="col-sm-4" style="padding: 30px;">
                        <div style="border-bottom: 2px solid #f2f2f2;margin-bottom: 30px;text-align: center;padding: 0 30px;padding-bottom: 30px;">
                            <img src="https://i-fast.b-cdn.net/interactr.io/batch-img.png" />
                            <h3 style="margin-bottom: 10px;margin-top: 5px;">30-DAY <br/>MONEY BACK GUARANTEE</h3>
                            <p style="margin-bottom: 10px;">
                                If you are not completely satisfied with your purchase within 30 days of buying Interactr, we will refund 100% of your money. </p>
                            <p>Just email us at <a href="mailto:support@interactr.io">support@interactr.io</a> and every penny will be gladly refunded to you within the first 30 days. Nothing could be more fairer than that.
                            </p>
                        </div>
                        <div style="border-bottom: 2px solid #f2f2f2;padding-bottom: 30px;margin-bottom: 30px">
                            <h3 style="text-align: center;margin-bottom: 15px">FEATURES</h3>
                            <ul class="list">
                                <li><img src="https://i-fast.b-cdn.net/interactr.io/access-bulet-icon.png" /> Interactr software</li>
                                <li><img src="https://i-fast.b-cdn.net/interactr.io/access-bulet-icon.png" /> Zero monthly or annual fees</li>
                                <li><img src="https://i-fast.b-cdn.net/interactr.io/access-bulet-icon.png" /> Special offer 65% off</li>
                                <li><img src="https://i-fast.b-cdn.net/interactr.io/access-bulet-icon.png" /> Unlimited video projects</li>
                                <li><img src="https://i-fast.b-cdn.net/interactr.io/access-bulet-icon.png" /> Commercial license included</li>
                                <li><img src="https://i-fast.b-cdn.net/interactr.io/access-bulet-icon.png" /> Superstar support</li>
                                <li><img src="https://i-fast.b-cdn.net/interactr.io/access-bulet-icon.png" /> 30-Day money back guarantee</li>
                            </ul>
                        </div>
                        <div class="testimonials-list">
                            <img src="https://i-fast.b-cdn.net/testimonials/Mem%20testimonial.png" class="img-fluid"/>
                            <img src="https://i-fast.b-cdn.net/testimonials/Nice%20Facebook%20Comment.png" class="img-fluid"/>
                            <img src="https://i-fast.b-cdn.net/interactr.io/Fabio%20Testimonial.png" class="img-fluid"/>
                            <img src="https://i-fast.b-cdn.net/testimonials/The%20Musicians%20Edge.png" class="img-fluid"/>
                            <img src="https://i-fast.b-cdn.net/interactr.io/Glenn%20McCreedy%20-%20Interactr%20Results%20(1).png" class="img-fluid"/>
                            <img src="https://i-fast.b-cdn.net/interactr.io/I%20love%20it%20(1).png" class="img-fluid"/>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</body>
</html>
