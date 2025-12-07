<!DOCTYPE html>
<html lang="fa">


<!-- Mirrored from hamakareh.ir/ by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 16 Sep 2023 17:15:24 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=utf-8"/><!-- /Added by HTTrack -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="قالب html, قالب معرفی اپلیکیشن, لندینگ پیج"/>
    <meta name="description" content="لندینگ پیج معرفی اپلیکیشن"/>
    <title>اپلیکیشن همه کاره</title>

    <!--font-awesome icons link-->
    <link rel="stylesheet" href="Content/PublicTheme/css/font-awesome.min.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="Content/PublicTheme/css/bootstrap.min.css">
    <link rel="stylesheet" href="Content/PublicTheme/css/slick.css">
    <link rel="stylesheet" href="Content/PublicTheme/css/venobox.css">
    <link rel="stylesheet" href="Content/PublicTheme/css/killercarousel.css">
    <!--main style file-->
    <link rel="stylesheet" href="Content/PublicTheme/css/style.css">
    <link rel="stylesheet" href="Content/PublicTheme/css/responsive.css">
    <link rel="stylesheet" href="Content/PublicTheme/css/rtl.css">
    <link href="Content/PublicTheme/css/rtl.css" rel="stylesheet"/>
</head>

<body id="index2">
<!-- perloader part start -->
<div id="main-preloader" class="main-preloader semi-dark-background">
    <div class="main-preloader-inner center">

        <div class="preloader-percentage center">
            <div class="object object_one"></div>
            <div class="object object_two"></div>
            <div class="object object_three"></div>
            <div class="object object_four"></div>
            <div class="object object_five"></div>
        </div>
        <div class="preloader-bar-outer">
            <div class="preloader-bar"></div>
        </div>
    </div>
</div>
<!-- perloader part start -->
<!-- HEADER AREA START -->
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index-2.html"><b>همه کاره</b></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa fa-bars" aria-hidden="true"></i>
        </button>
    </div>
</nav>
<!-- HEADER AREA END -->

<section>
    <section id="contact">
        <div class="container zindex2">
            <div class="row">
                <div class="col-lg-12 text-center overview-head down-oh color-fit wh mix-pro">
                    <h2>درخواست نمایندگی</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-10 m-auto">
                    <form action="{{ route('requestProvider') }}" method="post">
                        {{--                    <form action="#" method="post">--}}
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group mix">
                                    <p><i class="fa fa-user" aria-hidden="true"></i> نام شما</p>
                                    <input name="first_name" value="{{old('first_name')}}" type="text"
                                           required
                                           class="form-control" placeholder="مثال: میلاد">
                                    @error('first_name')
                                    <div class="error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group mix">
                                    <p><i class="fa fa-user" aria-hidden="true"></i> نام خانوادگی</p>
                                    <input type="text" name="last_name" class="form-control"
                                           required
                                           value="{{old('last_name')}}" placeholder="مثال: نوروزی">
                                    @error('last_name')
                                    <div class="error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row input-pa">
                            <div class="col-lg-12 col-md-12">
                                <div class="form-group mix">
                                    <p><i class="fa fa-envelope" aria-hidden="true"></i>شماره تماس</p>
                                    <input type="number" class="form-control" name="contact_number"
                                           value="{{old('contact_number')}}"
                                           required
                                           placeholder="0917******">
                                    @error('contact_number')
                                    <div class="error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row input-pa">
                            <div class="col-lg-12 col-md-12">
                                <div class="input-btn-pa mix">
                                    <div class="faq-bottom-btn text-center">
                                        <button type="submit" class="btn btn-action btn-success">
                                            <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                            ارسال درخواست
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>
</section>

<!--  FOOTER AREA START -->
<section id="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4">
                <a class="navbar-brand nb-2" href="index-2.html"><b>همه کاره</b></a>
            </div>
            <div class="col-lg-5 col-md-6 mr-auto">
                <form>
                    <div class="form-group fg2">
                        <input type="email" class="form-control" id="exampleInputEmail1" placeholder="عضویت در خبرنامه">
                        <button type="submit" class="btn "><i class="fa fa-paper-plane-o" aria-hidden="true"></i> ثبت
                            نام
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row no-p2 pt-5">
            <div class="col-lg-3 col-sm-6 col-md-6 footer-text">
                <h3>درباره ما</h3>
                <p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است.</p>
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6 footer-text">
                <h3>دسترسی سریع</h3>
                <a href="#">صفحه نخست</a>
                <a href="#">بررسی اپلیکیشن</a>
                <a href="#">ویژگی ها</a>
                <a href="#">دانلود</a>
                <a href="#">غیره</a>
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6 footer-text">
                <h3>ما را دنبال کنید</h3>
                <a href="#">فیسبوک</a>
                <a href="#">توییتر</a>
                <a href="#">تلگرام</a>
                <a href="#">یوتیوب</a>
                <a href="#">اینستاگرام</a>
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6 footer-text">
                <h3>ویژگی های ما</h3>
                <a href="#">بازاریابی</a>
                <a href="#">سوالات متداول</a>
                <a href="#">آموزش ها</a>
                <a href="#">گروه ها</a>
                <a href="#">غیره</a>
            </div>
        </div>
    </div>
</section>
<!--  FOOTER AREA END -->
<!--  COPYRIGHT AREA START -->
<div id="copyright">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center copyright-text">
                <p>تمامی حقوق این وب‌‌سایت محفوظ می‌باشد. طراحی و توسعه توسط <a href="#">شرکت مهراد پردازش</a></p>
            </div>
        </div>
    </div>
</div>
<!--  COPYRIGHT AREA END -->
<!-- Optional JavaScript -->
<script src="Content/PublicTheme/js/jquery-3.7.1.min.js"></script>
<script src="Content/PublicTheme/js/jquery-migrate-3.4.1.js"></script>
<script src="Content/PublicTheme/js/bootstrap.min.js"></script>
<script src="Content/PublicTheme/js/slick.min.js"></script>
<script src="Content/PublicTheme/js/killercarousel.js"></script>
<script src="Content/PublicTheme/js/particles.js"></script>
<script src="Content/PublicTheme/js/app.js"></script>
<script src="Content/PublicTheme/js/venobox.min.js"></script>
<script src="Content/PublicTheme/js/circular.js"></script>
<script src="Content/PublicTheme/js/custom.js"></script>

@if (Session::has('flash_message'))

    <script>
        $(document).ready(function () {
            alert('{{ Session::get('flash_message') }}');
        });
    </script>

@endif
</body>


</html>

