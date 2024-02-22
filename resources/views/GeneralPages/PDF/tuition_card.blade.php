<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>

        body {
            font-family: Arial, sans-serif;
            background-image: url(/build/export-images/bg-layer.jpg);

        }
        .bg-white{
            background-color: white;
        }

        .container {
            width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        header {
            display: flex;
            justify-content: space-between;
            padding: 10px;
        }

        header img {
            max-height: 100px;
        }

        header .title-description {
            text-align: center;
        }

        header .invoice-details {
            text-align: left;
        }

        section {
            margin: 20px 0;
            overflow: hidden;
            border: 2px solid;
            border-radius: 50px;
        }

        .contact-info>div {
            text-align: left;
        }

        .address {
            margin-top: 20px;
        }

        .flex {
            display: flex;
        }

        .justify-between {
            justify-content: space-between;
        }

        .contact-info {
            display: inline-flex;
            width: 100%;
        }

        .contact-info .name,
        .contact-info .contact-number {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            width: 100%;
        }

        .writing-rl {
            writing-mode: vertical-rl;
            transform: scale(-1);
            padding: 1.3rem 0;
            width: 60px;
        }

        .texthead {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .textbody {
            padding: 1rem;
            width: 100%;
        }



        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
        }

        #table1 tr th,
        tr td {
            padding: 1.2rem;
            border-left: 1px solid #9ddadf;
        }

        #table1 tr td {
            padding: 1.2rem;
            border-left: 1px solid;
            border-color: #9ddadf !important;
        ;
        }

        #table1 tr th:first-child {
            border-left: 0;
        }

        #table1 tr td:first-child {
            border-left: 0;
        }

        #table1 tr td {
            border-top: 1px solid #9ddadf;
        }




        #table2 tr th,
        tr td {
            padding: 1.2rem;
            border-left: 1px solid #ffe753;
        }

        #table2 tr th:first-child {
            border-left: 0;
        }

        #table2 tr td:first-child {
            border-left: 0;
        }

        #table2 tr td {
            border-top: 1px solid #ffe753;
        }


        .font-bold {
            font-weight: 600;
        }
        .font-light {
            font-weight: 300;
        }


        .border-table {
            border: 1px solid;
            border-radius: 20px;
            overflow: hidden;
            position: relative;
        }

        .title-section {
            background-color: #9ddadf;
            padding: 10px;
            text-align: center;
            font-weight: bold;
            border-top-right-radius: 20px;
            border-top-left-radius: 20px;
            color: rgb(0, 0, 0);
        }

        .table-container {
            display: flex;
            /* padding: 1rem; */
            justify-content: space-between;
        }

        .table-container table {
            padding: 1rem;
        }

        .bg-header {
            background-color: #e8f6f7;
        }

        .bg-blue {
            background-color: #9ddadf;
        }

        .bg-border-blue {
            border-color: #9ddadf !important;
        }

        .bg-yellow {
            background-color: #ffe753;
        }

        .bg-border-yellow {
            border-color: #ffe753 !important;
        }

        .text-white {
            color: white;
        }

        .p-1r {
            padding-right: 1rem;
        }

        .p-1l {
            padding-left: 1rem;
        }

        .m-0 {
            margin: 0;
        }

        .p-0 {
            padding: 0;
        }

        .mt-2rem {
            margin-top: 2rem;
        ;
        }

        .w50 {
            width: 50%;
        }

        .w-100 {
            width: 100%;
        }

        #table1 th:nth-child(3),
        #table1 td:nth-child(3) {
            width: 150px;
            /* عرض دلخواه را اینجا تنظیم کنید */
        }

        .considerations {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .consideration-item {
            margin-bottom: 10px;
            position: relative;
            margin-left: 1.2em;
        }

        .consideration-item::before {
            content: "\2022";
            /* کد یونیکد دایره */
            color: #9ddadf;
            /* رنگ دایره‌ها */
            font-size: 50px;
            position: absolute;
            left: -20px;
            /* فاصله از سمت چپ */
            top: 48%;
            transform: translateY(-50%);
        }


        footer {
            background-color: #f0f0f0;
            padding: 20px;
            background-color: #e8f6f7;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            margin-top: 1.5rem;
            margin-left: 10px;
        }

        .footer-text {
            font-size: 16px;
            line-height: 1.5;
        }
        .font-norm{
            font-family: normal;
        }
        .table-v p>span{
            font-weight: 400;
        }
        .table-v p{
            font-weight: 600;
        }

    </style>
    <title>Your Invoice</title>

</head>

<body class="container">
<header class="bg-header">
    <div>
        <img src="/build/export-images/logo.png" alt="Logo">
    </div>
    <div class="title-description">
        <h1>Tuition Card</h1>
        <p>Monji Noor Education Institute</p>
    </div>
    <div class="invoice-details">
        <p class="font-bold">Invoice Number: <span class="font-light">INV-001</span></p>
        <p class="font-bold">Date: <span class="font-light">2024-02-12</span></p>
        <p class="font-bold">Attachment: <span class="font-light">File.pdf</span></p>
    </div>
</header>


<section class="bg-border-blue bg-white table-v">
    <div class="flex">
        <div class="texthead bg-blue">
            <div class="writing-rl">
                <h5>Education Center Details</h5>
            </div>
        </div>
        <div class="textbody">
            <div class="contact-info">
                <div class="name">
                    <p>Name: <span>John Doe</span></p>
                </div>
                <div class="contact-number">
                    <p>Contact Number: <span>+123456789</span></p>
                </div>
            </div>
            <div class="flex justify-between">
                <p>Postal Code: <span>12345</span></p>
                <p>Registration Number: <span>ABC123</span></p>
                <p>National ID: <span>987654321</span></p>
            </div>
            <div class="address">
                <p>Address: <span>123 Main Street, City, Country Lorem ipsum dolor sit amet, consectetur adipisicing
                        elit. Quae aliquid repellendus tempora optio, eos quaerat iste est numquam id excepturi.</span></p>
            </div>
        </div>
    </div>
</section>

<section class="bg-border-yellow bg-white table-v">
    <div class="flex">
        <div class="texthead bg-yellow">
            <div class="writing-rl">
                <h5>Education Center Details</h5>
            </div>
        </div>
        <div class="textbody">
            <div class="flex justify-between">
                <p>Postal Code: <span>12345</span></p>
                <p>Registration Number: <span>ABC123</span></p>
                <p>National ID: <span>987654321</span></p>
            </div>
            <div class="flex justify-between">
                <p>Postal Code: <span>12345</span></p>
                <p>Registration Number: <span>ABC123</span></p>
                <p>National ID: <span>987654321</span></p>
            </div>
            <div class="contact-info">
                <div class="name">
                    <p>Name: <span>John Doe</span></p>
                </div>
                <div class="contact-number">
                    <p>Contact Number: <span>+123456789</span></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- اضافه کردن بخش جدید با جدول -->
<div id="table1" class="border-table bg-border-blue radius-table bg-white">
    <h3 class="title-section bg-blue p-1r m-0 radius-table">fee Details</h3>
    <div class="table-container">
        <table>
            <tr>
                <th>Currency of Payment</th>
                <th>Total Discount Amount</th>
                <th>Type of Payment</th>
                <th>Sub-Total Fee</th>
                <th>Total Fee</th>
            </tr>
            <tr>
                <td class="font-bold">Iranian Rial</td>
                <td>15,000,000</td>
                <td class="font-bold">Installments</td>
                <td>50,000,000</td>
                <td>35,000,000</td>
            </tr>

        </table>
    </div>
</div>


<div class="flex w-100">
    <div class="w-100 p-1r">
        <div id="table2" class="border-table bg-border-yellow radius-table mt-2rem bg-white">
            <h3 class="title-section bg-yellow p-1r m-0 radius-table">fee Details</h3>
            <div class="table-container ">
                <table class="font-bold">
                    <tr>
                        <th>Currency of Payment</th>
                        <th>Total Discount Amount</th>
                        <th>Type of Payment</th>
                    </tr>
                    <tr>
                        <td>15,000,000</td>
                        <td>15,000,000</td>
                        <td>35,000,000</td>

                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>

                    </tr>
                    <tr>
                        <td>15,000,000</td>
                        <td>15,000,000</td>
                        <td>35,000,000</td>

                    </tr>
                    <tr>
                        <td>15,000,000</td>
                        <td>15,000,000</td>
                        <td>35,000,000</td>

                    </tr>
                    <tr style="border-top: 1px solid #ffe753;">
                        <td class="font-bold">Total</td>
                        <td style="border: none;">15,000,000</td>

                    </tr>

                </table>
            </div>
        </div>
    </div>


    <div class="w-100 p-1l">
        <div id="table1" class="border-table bg-border-blue radius-table mt-2rem bg-white">
            <h3 class="title-section bg-blue p-1r m-0 radius-table">fee Details</h3>
            <div class="table-container ">
                <table class="font-bold">
                    <tr>
                        <th>Data Received</th>
                        <th>Amount</th>
                        <th>Method of Payment</th>
                        <th>Balance</th>
                    </tr>
                    <tr>
                        <td>2023-07-23</td>
                        <td>2,000,000</td>
                        <td>POS</td>
                        <td>25,000,000</td>

                    </tr>
                    <tr>
                        <td>15,000,000</td>
                        <td>15,000,000</td>
                        <td>35,000,000</td>
                        <td>35,000,000</td>

                    </tr>

                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>

                    </tr>

                    <tr style="border-top: 1px solid #9ddadf;">
                        <td class="font-bold" colspan="2">Total Received</td>
                        <td colspan="2" style="">15,000,000</td>

                    </tr>
                    <tr style="border-top: 1px solid #9ddadf;">
                        <td class="font-bold" colspan="2">Not Received</td>
                        <td colspan="2" style="">15,000,000</td>

                    </tr>

                </table>
            </div>
        </div>
    </div>
</div>


<div class="Considerations">
    <h1>Considerations</h1>
    <ul class="considerations ">
        <li class="consideration-item">Lorem ipsum dolor sit amet consectetur adipisicing elit. Delectus ullam ea natus iusto architecto libero.</li>
        <li class="consideration-item">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sint, neque!</li>
        <li class="consideration-item">Lorem, ipsum dolor sit amet consectetur adipisicing.</li>
        <li class="consideration-item">Lorem ipsum dolor sit amet.</li>
    </ul>
</div>
<footer class="mt-2rem">
    <div class="footer-text font-bold">Lorem ipsum dolor sit amet consectetur adipisicing elit. Sint atque suscipit, id, nulla asperiores repudiandae nemo aliquam nisi magni, iste tenetur dolores optio nobis impedit quo molestias laudantium dignissimos architecto!</div>
    <div class="footer-content font-bold">
        <div class="footer-text">Lorem ipsum dolor sit amet.</div>
        <div class="footer-text">Lorem ipsum dolor sit amet.</div>
        <div class="footer-text">Lorem ipsum dolor sit amet.</div>
    </div>
</footer>

</body>

</html>
