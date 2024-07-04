<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>
        Bootstrap Hijri Date Picker
    </title>
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" /> 
    <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" /> -->

</head>
<body class="bg-light">
    <div class="container">

        <div class="form-group">
            <label>
                Date
            </label>
            <div class="input-group">
                <input type='text' class="form-control" id="hijri-date-input" />
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script src="js/bootstrap-hijri-datepicker.min.js"></script>
    <script type="text/javascript">

        $(function () {

            $("#hijri-date-input").hijriDatePicker();

        });

    </script>

</body>
</html>