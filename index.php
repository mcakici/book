<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezervasyon Api test</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</head>

<body class="bg-light">
    <div class="container">
        <form class="form-control" onsubmit="$.post('https://book.laplup.com/api/', $('#body').val()).done(function(data){ $('#response').html(JSON.stringify(data)); });return false;">

            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Json input data</label>
                <textarea class="form-control" rows="18" id="body" name="requestbody">{
                    "Tren":
                    {
                        "Ad":"Başkent Ekspres",
                        "Vagonlar":
                        [
                            {"Ad":"Vagon 1", "Kapasite":100, "DoluKoltukAdet":68},
                            {"Ad":"Vagon 2", "Kapasite":90, "DoluKoltukAdet":50},
                            {"Ad":"Vagon 3", "Kapasite":80, "DoluKoltukAdet":80}
                        ]
                    },
                    "RezervasyonYapilacakKisiSayisi":3,
                    "KisilerFarkliVagonlaraYerlestirilebilir":true
                }</textarea>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary mb-3">Kontrol et</button>
            </div>
        </form>

        <div id="response" class="form-control mt-2"></div>
    </div>



</body>

</html>