<html style="height: 100%;">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
    <style>
        :root {
            --app-color: #ff80ab;
        }

        {{--@font-face {--}}
        {{--    font-family: "IRANYekanMedium";--}}
        {{--    src: url("{{ asset('landing/wp-includes/fonts/IRANYekanMedium.ttf') }}");--}}
        {{--}--}}

        body {
            direction: rtl;
            background-color: #fffbfc;
            /*font-family: "IRANYekanMedium";*/
            font-size: 1.2rem;
            /*background-image: url('../../landing/wp-includes/images/back 3.png');*/
            background-repeat: no-repeat;
            background-size: 350px;
            background-attachment: fixed;
            background-position: top left;
        }

        .chip {
            align-items: center;
            font-size: 35px;
            font-weight: bold;
            padding: 12px 12px;
            margin-bottom: 60px;
            text-decoration: none;
            vertical-align: middle;
            text-align: center;
            white-space: nowrap;
        }
    </style>
</head>

<body style="margin: 0px 0px 8px; display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
<div>
    <div>
        <div>
            <div style="text-align:center; margin-top: 80px;">

                @if ($success)
                    <div style="padding: 0px 15% ;">
                        <div class="chip" style="color: var(--app-color);"> موفقیت آمیز
                        </div>

                        <p>کاربر عزیز پرداخت شما با موفقیت انجام شد</p>
                        @if ($code)
                            <p>کد پیگیری: {{$code}}</p>
                        @endif
                    </div>

                @else
                    <div style="padding: 0px 15% ; margin-top:80px;">
                        <div class="chip" style="color: var(--app-color);">خطا</div>

                        <p>خطایی در عملیات رخ داده است</p>
                        <p>جهت پیگیری می توانید با پشتیبانی تماس بگیرید</p>
                        @if ($code)
                            <p>کد پیگیری: {{$code}}</p>
                        @endif
                    </div>
                @endif

            </div>

        </div>
    </div>
</div>
<div style="text-align: center;margin-bottom: 20px;padding: 10px;">
    <a style="font-weight:bold;background-color: var(--app-color);
                     /*font-family: 'IRANYekanMedium';*/
                     color: black;
                     font-size: 22px;
                     border-radius: 4px;
                     text-decoration: none;
                     display: block;
                     padding: 5px 0;
                     margin: 0 auto;
                     max-width: 300px;
                     box-shadow: 0 11px 15px -7px rgba(0,0,0,.2),0 24px 38px 3px rgba(0,0,0,.14),0 9px 46px 8px rgba(0,0,0,.12)!important;
                     "
       id="myLink" href="#" onclick="redirectToApp();"
    >بازگشت به برنامه</a>
</div>

</body>
<script>
    function redirectToApp() {
        window.location.replace('market://Hamekareh?action=up');
    };
</script>
</html>
