<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
            integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/bootstrap-slider.min.js"></script>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/css/bootstrap-slider.min.css">
    <link rel="stylesheet" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.21/datatables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.21/datatables.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    <style>
        .inner-box {
            margin-top: 50px;
        }

        .filter {
            margin-left: -80px;
            margin-right: auto;
        }

        .slider {
            width: 300px !important;
        }

        .row {
            margin-bottom: 35px;
        }
    </style>
</head>
<body>
@include('TopNavBar')
<div class="container">
    <div class="row inner-box">
        <div class="col-lg-3">
            <div class="row filter">
                <div class="row" style="width: 365px;">
                    <div class="col-sm-6"><i class="fa fa-filter" aria-hidden="true"></i>Filters</div>
                    <div id="clear" class="col-sm-6 text-right"><i class="fa fa-eraser" aria-hidden="true"></i>Clear All
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4"><input type="radio" name="stops" value="1" id="stops" class="stops">
                        <label>Non Stop</label>
                    </div>
                    <div class="col-sm-4"><input type="radio" name="stops" value="2" id="stops" class="stops">
                        <label>Upto 1 stop</label>
                    </div>
                    <div class="col-sm-4"><input type="radio" name="stops" value="3" id="stops"
                                                 class="stops" checked>
                        <label>Upto 2+ stops</label>
                    </div>
                </div>

                <div class="row">
                    <label for="departure">Departure Time</label>
                    <div class="col-sm-12">
                        <input id="departure" type="text" class="span2" value="" data-slider-min="0"
                               data-slider-max="24"
                               data-slider-step="1" data-slider-value="[0,24]" onmouseup="dataFunction(this)"/>
                    </div>
                </div>
                <div class="row">
                    <label>Duration</label>
                    <div class="col-sm-12">
                        <input id="duration" type="text" class="span2" value="" data-slider-min="0"
                               data-slider-max="24"
                               data-slider-step="1" data-slider-value="[0,24]" onmouseup="dataFunction(this)"/>
                    </div>
                </div>
                <div class="row">
                    <button id="filter" class="btn btn-primary">Filer Results</button>
                </div>
            </div>


        </div>
        <div class="col-lg-9">

            @include('ListingCards')

        </div>
    </div>
</div>
<div id="div1"></div>
<script>
    $("#departure").slider({});
    $("#duration").slider({});
    $(document).ready(function () {
        $('#example').DataTable();
        $('#filter').on('click', function () {
            filterData();
        });
    });

    function filterData() {
        let stops = $('.stops:checked').val();
        let departure = $('#departure').val();
        let duration = $('#duration').val();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url    : "/flightListing",
            data   : { 'stops': stops, 'departure': departure, 'duration': duration },
            method : 'POST',
            success: function (result) {
                console.log(result);
                $("#example").DataTable().destroy()
                $('#table-body tr').remove();
                $('#example tbody').append(result.data);
                $("#example").DataTable();
            }
        });
    }

</script>
</body>
</html>
