

<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="css/style.css" rel="stylesheet">

        <style>

            body{
                padding: 20px;
            }
        
        </style>
    </head>
    <body>



        <hr>


        <h1>
        Hello . {{$customer->name}} . <br>

        </h1>

    <a href="{{url('api/customers/register/verify')}}/{{$customer->id}}">Verify Your Mail</a>



    </body>
</html>
