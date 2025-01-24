<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Default Title'; ?></title>
    <link rel="icon" href="static/ytc.png" type="image/x-icon">
    <link rel="shortcut icon" href="static/ytc.png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/formlogin1.css">
    <link rel="stylesheet" href="css/tooltip.css">
    <link rel="stylesheet" href="css/footer.css">
    <!--<link rel="stylesheet" href="css/card.css">-->
    <style>
        @font-face {
            font-family: ma;
            src: url(static/Athiti-Regular.woff);
        }

        * {
            font-family: ma;
        }
        
        ul {
        list-style-type: none;
        }
        a:link{
            text-decoration: none;
        }
        
        ::-webkit-scrollbar {
        width: 10px;
        }
        
        ::-webkit-scrollbar-track {
        background: #858585;
        }
        
        ::-webkit-scrollbar-thumb {
        background: rgb(12, 78, 73);
        }
        
        ::-webkit-scrollbar-thumb:hover {
        background: rgb(23, 167, 167);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            width: 14.28%;
            border: 1px solid #ddd;
            text-align: center;
            padding: 10px;
        }
        th {
            background-color: #f4f4f4;
        }
        .today {
            background-color: #ffeb3b;
        }
        .button1 {
            width: 150px;
            height: 100%;
            border-radius: 10px;
            background: none;
            cursor: pointer;
            padding: 5px;
            margin-bottom: 20px;
            float: left;
        }
        .button2 {
            width: 150px;
            height: 100%;
            border-radius: 10px;
            background: none;
            cursor: pointer;
            padding: 5px;
            margin-bottom: 20px;
            float: right;
        }
        button:hover {
            background-color: #f0f8ff;
        }
    </style>
</head>