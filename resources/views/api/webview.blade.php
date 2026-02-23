<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR</title>
</head>
<style>
    .body {
        background-color: #fff;
        margin: 0 !important;
        padding: 0 !important;
    }
    * {
        box-sizing: border-box !important;
    }
    #main-payment-box {
        background-image: url("{{ asset('assets/images/background-khqr.png') }}");
        width: 414px !important;
        height: 481px !important;
        padding-top: 100px !important;
        padding-left: 109px !important;
    }
    .main {
        width: 190px !important;
        position: relative !important;
        height: 294px !important;
        box-shadow: 0 0 16px 0 rgba(0, 0, 0, .10);
        border-radius: 20px;
        overflow: hidden;
    }

    #qr {
        position: absolute !important;
        bottom: 24px !important;
        left: 24px !important;
        width: 146px !important;
        height: 146px !important;
    }

    #bg {
        width: 100% !important;
        height: 100% !important;
    }

    #name {
        position: absolute;
        top: 12%;
        left: 10%;
        font-size: 13px !important;
        color: #fff;
        color: #000;
        height: 22%;
    }

    #name-content {
        display: flex;
        flex-direction: column;
        justify-content: start;
    }

    #main-content {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }

    #label-amount {
        font-size: 20px !important;
        font-weight: bold;
    }

    #label-usd {
        font-size: 16px;
        font-weight: 400 !important;
    }

    p {
        margin: 0;
        padding: 0;
    }
</style>

<body>
    <div id="main-payment-box">
        <div class="main">
            <div id="name">
                <div id="main-content">
                    <div id="name-content">
                        <span id="label-name">{{ $data->name ?? '' }}</span>
                        <p><span id="label-amount">{{ number_format($data->amount, 2) }}</span><span id="label-usd"> USD</span></p>
                    </div>
                </div>
            </div>
            <img id="bg" src="{{ asset('assets/images/khqr_card.png') }}">
            <img id="qr" src="{{ $data->image }}">
        </div>
    </div>
</body>

</html>
