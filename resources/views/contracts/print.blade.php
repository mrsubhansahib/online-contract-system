@extends('layout.app2')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <style>
        input {
            border: none;
        }

        label {
            font-size: 16px !important;
        }
        input[type="date"],input[type="checkbox"] {
            pointer-events: none;
        }   
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-md-11 mx-auto position-relative">
            <div class="spinner-border text-black spinner-border-sm main-loader" role="status">
                <span class="sr-only">Loading...</span>
            </div>

            <div class="card" style="width:595px ;margin:auto;">
                <div class="card-body py-3 pt-5 pb-5" id="photo">
                    @include('layout.alerts')

                    <form action="#" method="POST" id="form-builder-pages">
                        @csrf
                        <ul id="tabs" class="nav nav-tabs page-count-tabs d-none">
                            <li class="nav-item d-none"><a class="nav-link active" href="#page-1">Page 1</a></li>
                            <li class="nav-item d-none" id="add-page-tab"><a class="nav-link" href="#new-page">+ Page</a>
                            </li>
                        </ul>
                        <div id="page-1" class="fb-editor"></div>
                        <div id="new-page"></div>

                    </form>

                </div>
            </div>
        </div>
    </div>
    @include('layout.sign-modal')
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js">
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <script src="{{ asset('assets/bundles/form-builder/form-builder.min.js') }}"></script>
        <script src="{{ asset('assets/bundles/form-builder/form-render.min.js') }}"></script>
        <script>
            function downloadPdf() {
                // Your existing code for generating PDF
                html2canvas(document.querySelector("#photo")).then((canvas) => {
                    let base64Image = canvas.toDataURL('image/png');
                    // Calculate dimensions based on canvas size
                    let width = canvas.width;
                    let height = canvas.height;
                    let pdfWidth = 595; // A4 width in pixels
                    let pdfHeight = 842; // A4 height in pixels
                    if (width > pdfWidth || height > pdfHeight) {
                        pdfWidth = width;
                        pdfHeight = height;
                    }
                    let pdf = new jsPDF('p', 'px', [pdfWidth, pdfHeight]);
                    pdf.addImage(base64Image, 'PNG', 0, 0, pdfWidth, pdfHeight);
                    var dynamicId = "{{ $contract->id }}";
                    let filename = 'Generate_Pdf_' + dynamicId + '.pdf';
                    pdf.save(filename); // Save PDF with filename
                });
            }
            var loggedInUser = "{{ auth()->user()->id }}";

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
                    $newPageTemplate.parentElement.insertBefore($newPage, $newPageTemplate);
                    // $newTabTemplate.parentElement.insertBefore(
                    //     $("#line-ending-div")[0].cloneNode(),
                    //     $newPageTemplate
                    // );
                    $fbPages.tabs("refresh");
                    $fbPages.tabs("option", "active");
                    // console.log(formPage);
                    setTimeout(() => {
                        // fbInstances.push($($newPage).formBuilder());
                        fbInstances.push($($newPage).formRender({
                            formData: formPage,
                            fields,
                            templates: templates2
                        }));
                    }, 2000);

                })
                setTimeout(() => {
                    $(".main-loader").hide()
                    $(`.add-sign:not([data-user=${loggedInUser}])`).hide()
                    $(".fb-editor").show().not(":first,:last").after('<div class="print-page-break"></div>')

                }, 2000);

            })
        </script>

        @if (auth()->user()->role != 'admin')
            @push('scripts')
                <script>
                    setTimeout(() => {
                        console.log();
                        $("#form-builder-pages input,#form-builder-pages select,#form-builder-pages textarea").prop("disabled",
                            true)
                    }, 1000);
                </script>
                
            @endpush
        @endif
        <script>
            $(document).ready(function() {
                // alert('Page Loaded')
                // Assume dynamicId is already available or fetched dynamically



                setTimeout(function() {
                    html2canvas(document.querySelector("#photo")).then((canvas) => {
                        let base64Image = canvas.toDataURL('image/png');
                        // console.log(base64Image);

                        // Calculate dimensions based on canvas size
                        let width = canvas.width;
                        let height = canvas.height;

                        // Set default dimensions to A4 size
                        let pdfWidth = 595; // A4 width in pixels
                        let pdfHeight = 842; // A4 height in pixels

                        // Check if canvas dimensions exceed A4 size
                        if (width > pdfWidth || height > pdfHeight) {
                            // If canvas dimensions exceed A4 size, use dynamic dimensions
                            pdfWidth = width;
                            pdfHeight = height;
                        }

                        let pdf = new jsPDF('p', 'px', [pdfWidth, pdfHeight]);
                        pdf.addImage(base64Image, 'PNG', 0, 0, pdfWidth, pdfHeight);

                        var dynamicId = {{ $contract->id }};
                        // Generate dynamic filename with dynamic ID
                        let filename = 'Generate_Pdf_' + dynamicId + '.pdf';

                        // Convert PDF to Blob object
                        let pdfBlob = pdf.output('blob');

                        // Create FormData object to send PDF data to server
                        let formData = new FormData();
                        formData.append('pdf', pdfBlob, filename);
                        formData.append('dynamic_id', dynamicId); // Append dynamic ID to form data

                        // Send PDF data to server using jQuery Ajax
                        $.ajax({
                            url: '{{ route('save.pdf') }}',
                            type: 'POST',
                            data: formData,
                            processData: false, // Prevent jQuery from automatically processing the data
                            contentType: false, // Prevent jQuery from setting contentType
                            success: function(response) {
                                if (response == 'Sent') {
                                    console.log(
                                        'PDF saved on server and email sent successfully'
                                    );

                                } else if (response == 'Already Sent') {
                                    console.error(
                                        'PDF already sent.'
                                    );

                                } else {
                                    console.error(
                                        'Something went wrong.'
                                    );
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Error saving PDF or sending email: ' +
                                    error);
                            }
                        });
                    });
                }, 5000);
            });
        </script>
    @endpush

@endsection
