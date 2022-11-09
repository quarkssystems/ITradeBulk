<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6 text-center">
                <p>Delivering the latest product trends and industry news straight to your inbox.</p>
                <form class="form-inline my-2 my-lg-0">
                    <input class="form-control mr-sm-2" type="email" placeholder="Enter your email">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Subscribe</button>
                </form>
                <div class="space"></div>
                <div class="social-app d-flex align-items-center">
                    <div class="app d-flex align-items-center mr-4">
                        <p class="mb-0 mr-3">Download</p>
                        <!-- <img src="{{asset("assets/frontend/images/apple-store.png")}}" class="mr-2"> -->
                        <img src="{{asset("assets/frontend/images/google-play.png")}}">
                    </div>
                    <div class="social d-flex align-items-center mr-5">
                        <p class="mb-0 mr-3">Follow Us</p>
                        <i class="fab fa-facebook-f"></i>
                        <i class="fab fa-instagram"></i>
                        <i class="fab fa-twitter"></i>
                        <i class="fab fa-pinterest-p"></i>
                    </div>
                </div>
                <div class="space lg"></div>
            </div>
            <div class="col-md-3"></div>
        </div>
        <div class="row mb-4">
            <div class="col col-1-5">
                <h6>Customer Services</h6>
                <ul>
                    <li><a href="#">Get the App</a></li>
                    <li><a href="{{route('contact')}}">Contact Us</a></li>
                    <li><a href="{{url('report-abuse')}}">Report Abuse</a></li>
                    <li><a href="{{url('feedback')}}">Feedback</a></li>
                    <li><a href="{{url('pages/cancellation-policy')}}">Cancellation Policy</a></li>
                    <li><a href="{{url('pages/return-policy')}}">Return Policy</a></li>
                    <li><a href="{{url('pages/help-community')}}">Help & Community</a></li>
                    <li><a href="{{url('pages/faqs')}}">FAQ's</a></li>
                </ul>
            </div>
            <div class="col col-1-5">
                <h6>Information</h6>
                <ul>
                    <li><a href="{{url('pages/terms-condition')}}">Terms of Use</a></li>
                    <li><a href="{{url('pages/shipping')}}">Shipping</a></li>
                    <li><a href="{{url('pages/security')}}">Security</a></li>
                    <li><a href="{{url('pages/payments')}}">Payments</a></li>
                    <li><a href="{{url('pages/privacy-policy')}}">Privacy Policy</a></li>
                    <li><a href="{{url('pages/return-policy')}}">Return Policy</a></li>
                </ul>
            </div>
            <div class="col col-1-5">
                <h6>ITRADEBULK SERVICES</h6>
                <ul>
                    <li><a href="{{url('become-vendor')}}">Become Trader</a></li>
                    <li><a href="{{url('become-supplier')}}">Become Supplier</a></li>
                    <li><a href="{{url('become-driver')}}">Become Transporter</a></li>
                </ul>
            </div>
            <div class="col col-1-5">
                <h6>MENU</h6>
                <ul>
                    <li><a href="{{route('products')}}">Products</a></li>
                    <li><a href="{{route('offers')}}">Promotions</a></li>
                    <li><a href="{{url('/user/login')}}">Login</a></li>
                    <li><a href="{{url('/register')}}">Register</a></li>
                </ul>
            </div>
            <div class="col col-1-5">
                <h6>CONNECT WITH US</h6>
                <ul>
                    <li><a href="{{url('become-vendor')}}">Facebook</a></li>
                    <li><a href="{{url('become-supplier')}}">Twitter</a></li>
                    <li><a href="{{url('become-driver')}}">Instagram</a></li>
                    <li><a href="{{url('become-driver')}}">Linkedin</a></li>
                </ul>
            </div>

            <!-- <div class="col col-1-5">
                <h6>About Us</h6>
                <ul>
                    <li><a href="{{route('about')}}">About</a></li>
                    <li><a href="#">Career</a></li>
                </ul>
            </div> -->
            <!-- <div class="col col-1-5">
                <h6>Buy on itradezon.com</h6>
                <ul>
                    <li><a href="{{url('products/detail/category')}}">All Categories</a></li>
                    @if(checkUserLoggedIn() == 1)
                        <li><a href="{{url('request-quote')}}">Request for Quotation</a></li>
                    @endif
                </ul>
            </div> -->
            <!-- <div class="col col-1-5">
                <h6>Sell on itradezon.com</h6>
                <ul>
                    <li><a href="{{url('become-supplier')}}">Become a Supplier</a></li>
                    <li><a href="{{url('supplier')}}">Our suppliers</a></li>
                </ul>
            </div> -->
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <p>
                    &copy; Copyrights {{date('Y')}} {{env("APP_NAME")}}. All Rights Reserved.
                    <a href="{{url('pages/terms-condition')}}">Terms of Use</a>  |
                    <a href="{{url('pages/privacy-policy')}}">Privacy Policy</a>
                </p>
            </div>
        </div>
    </div>
</footer>
<div class="modal fade" id="ajax-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">

        </div>
    </div>
</div>

@include('frontend.utils.selectLocation')