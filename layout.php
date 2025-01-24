<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $title = isset($title) ? $title : "Default Title"; ?>
    <title><?php echo isset($title) ? $title : 'Default Title'; ?></title>
    <link rel="icon" href="static/ytc.png" type="image/x-icon">
    <link rel="shortcut icon" href="static/ytc.png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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
        #grad1 {
        background-image: linear-gradient(to left, #FFFFFFFF, #D2ECFFFF, #B1DDFFFF, #6EC0FFFF, #42ADFFFF);
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
        table, th, td {
            border: 1px solid black;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
        }
    </style>
</head>