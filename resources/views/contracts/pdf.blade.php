<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('assets/bootstrap.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/jquery.signature.css') }}">

</head>

<body>


    <div class="row">
        <div class="col-md-11 mx-auto position-relative">
            <div class="spinner-border text-black spinner-border-sm main-loader" role="status">
                <span class="sr-only">Loading...</span>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>
                        View Contract
                    </h4>
                    <a href="{{ route('contract') }}" class="btn btn-primary btn-sm">All Contracts</a>
                </div>
                <div class="card-body py-3 pb-5 bg-success">
                    @include('layout.alerts')

                    <form action="#" method="POST" id="form-builder-pages">
                        @csrf
                        <ul id="tabs" class="nav nav-tabs page-count-tabs">
                            <li class="nav-item d-none"><a class="nav-link active" href="#page-1">Page 1</a></li>
                            <li class="nav-item d-none" id="add-page-tab"><a class="nav-link" href="#new-page">+
                                    Page</a></li>
                        </ul>
                        <div id="page-1" class="fb-editor"></div>
                        <div id="new-page"></div>

                    </form>

                    {{-- {{ $contract }} --}}
                    <form action="{{ route('contract.saveDetails') }}" id="main-form" method="POST">
                        @csrf
                        <input type="hidden" value="{{ $contract->id }}" id="id" name="id">
                        <textarea name="details" class="d-none" id="form-data" cols="30" rows="10"></textarea>
                        <div class="row">
                            <div class="col-12">
                                @if ($contract->status == 'pending')
                                    <button class="btn btn-primary btn-block my-4" type="submit">Save Details</button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="{{ asset('assets/bundles/form-builder/form-builder.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/form-builder/form-render.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jquery.signature.js') }}"></script>
    <script src="{{ asset('assets/js/signature_pad.min.js') }}"></script>
    <script src="{{ asset('assets/js/signature.js') }}"></script>

    <script>
        var loggedInUser = "{{ auth()->user()->id }}";
        var sig = $('#sign-box').signature({
            syncField: '#signature',
            syncFormat: 'PNG'
        });
        $('#clear').click(function(e) {
            e.preventDefault();
            sig.signature('clear');
            $("#signature64").val('');
        });

        $("#main-form").on("submit", function(e) {
            e.preventDefault();
            const allData = fbInstances.map((fb) => {
                return fb.options.formData;
            });
            $("#form-data").val(JSON.stringify(allData))
            // Remove the event listener
            $(this).off('submit');
            // Submit the form manually
            $(this).submit()
        })
        // $("#").on("click",function(){


        // })

        var $fbPages = $(document.getElementById("form-builder-pages"));
        var addPageTab = document.getElementById("add-page-tab");
        var fbInstances = [];
        $fbPages.tabs({
            beforeActivate: function(event, ui) {
                if (ui.newPanel.selector === "#new-page") {
                    return false;
                }
            }
        });

        <?php
        $js_array = $contract->form_data;
        echo 'var javascript_array = ' . $js_array . ";\n";
        ?>
        data = javascript_array;
        var status = "{{ $contract->status }}";
        var disabledButton = ['data', 'clear']
        status == 'pending' ? '' : disabledButton.push('save');


        $(document).ready(function() {

            data.forEach((formPage, i) => {

                const tabCount = document.getElementById("tabs").children.length;
                const tabId = "page-" + tabCount.toString();
                const $newPageTemplate = document.getElementById("new-page");
                const $newTabTemplate = document.getElementById("add-page-tab");
                const $newPage = $newPageTemplate.cloneNode(true);
                $newPage.setAttribute("id", tabId);
                $newPage.classList.add("fb-editor");
                const $newTab = $newTabTemplate.cloneNode(true);
                $newTab.removeAttribute("id");
                $newTab.classList.remove("d-none"); //custom
                if (i == 0) {
                    $newTab.querySelector("a").classList.add("active"); //custom
                }
                const $tabLink = $newTab.querySelector("a");
                $tabLink.setAttribute("href", "#" + tabId);
                $tabLink.innerText = "Page " + (tabCount - 1);
                // console.log(tabCount);

                $newPageTemplate.parentElement.insertBefore($newPage, $newPageTemplate);
                $newTabTemplate.parentElement.insertBefore($newTab, $newTabTemplate);
                $fbPages.tabs("refresh");
                $fbPages.tabs("option", "active", 1);
                // console.log(formPage);
                setTimeout(() => {
                    // fbInstances.push($($newPage).formBuilder());
                    fbInstances.push($($newPage).formRender({
                        formData: formPage,
                        fields,
                        templates
                    }));
                }, 2000);

            })
            setTimeout(() => {
                $(".main-loader").hide()
                $(`.add-sign:not([data-user=${loggedInUser}])`).hide()
            }, 500);

        })
    </script>



</body>

</html>








{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Salary Sheet</title>
    <style>
        
        table,tr,td,th{
            font-size: 11px;
            text-align: center;
        }
        .test{
            display: none;
        }
        /* #footer { position: fixed; right: 0px; bottom: 10px; text-align: center;border-top: 1px solid black;}
        #footer .page:after { content: counter(page, decimal); }
        @page { margin: 20px 30px 40px 50px; } */
    </style>
</head>
<body>
    <script type="text/php">
        if (isset($pdf)) {
            $pdf->page_text(300, 545 , "Software Developed By :- SAR ZONE" , 'sans-sarif', 9, 'red', 0.0, 0.0, 0.0);
            $pdf->page_text(350, 560 , "www.sarzone.com" , 'sans-sarif', 9, 'red', 0.0, 0.0, 0.0);
            $pdf->page_text(335, 575 , "Contact :- 03333068686" , 'sans-sarif', 9, 'red', 0.0, 0.0, 0.0);
        }
    </script>
    
    <h1 align="center">Reaching</h1>

</body>
</html> --}}
