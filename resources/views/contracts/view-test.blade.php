@extends('layout.app')
@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/jquery.signature.css') }}">
    <style>
        input {
            border: none;
        }

        label {
            font-size: 16px !important;
        }

        input[type="date"],
        input[type="checkbox"] {
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

            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>
                        View Contract
                    </h4>
                    <a href="{{ route('contract.print', $contract->id) }}" class="btn btn-primary btn-sm fa fa-print"></a>
                </div>
                <div class="card-body py-3 pb-5">
                    @include('layout.alerts')

                    <form action="#" method="POST" id="form-builder-pages">
                        @csrf
                        <ul id="tabs" class="nav nav-tabs page-count-tabs">
                            <li class="nav-item d-none"><a class="nav-link active" href="#page-1">Page 1</a></li>
                            <li class="nav-item d-none" id="add-page-tab"><a class="nav-link" href="#new-page">+ Page</a>
                            </li>
                        </ul>
                        <div id="page-1" class="fb-editor"></div>
                        <div id="new-page"></div>

                    </form>

                    {{-- {{ $contract }} --}}
                    <form action="{{ route('contract.saveDetails') }}" id="main-form" method="POST"
                        onsubmit="disableSubmitButton()">
                        @csrf
                        <input type="hidden" value="{{ $contract->id }}" id="id" name="id">
                        <textarea name="details" class="d-none" id="form-data" cols="30" rows="10"></textarea>
                        <div class="row">
                            <div class="col-12">
                                @if ($contract->status == 'pending')
                                    <button class="btn btn-primary btn-block my-4" type="submit" id="submitButton">Save
                                        Details</button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('layout.sign-modal')
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <script src="{{ asset('assets/bundles/form-builder/form-builder.min.js') }}"></script>
        <script src="{{ asset('assets/bundles/form-builder/form-render.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/js/jquery.signature.js') }}"></script>
        <script src="{{ asset('assets/js/signature_pad.min.js') }}"></script>
        <script src="{{ asset('assets/js/signature.js') }}"></script>

        <script>
            function disableSubmitButton() {
                var submitButton = document.getElementById('submitButton');
                submitButton.disabled = true;
                submitButton.style.opacity = '0.5';
                submitButton.style.cursor = 'not-allowed';
            }
            var loggedInUser = "{{ auth()->user()->id }}";
            var changesMade = false;
            var sig = $('#sign-box').signature({
                syncField: '#signature',
                syncFormat: 'PNG'
            });
            $(window).on('beforeunload', function() {
                if (changesMade) {
                    return 'Are you sure you want to leave?';
                }
            });
            $('#clear').click(function(e) {
                e.preventDefault();
                sig.signature('clear');
                $("#signature64").val('');
            });

            $("#main-form").on("submit", function(e) {
                e.preventDefault();
                changesMade = false;
                const allData = fbInstances.map((fb) => {
                    return fb.options.formData;
                });
                let invalid = false;
                for (let i = 0; i < allData.length; i++) {
                    allData[i].forEach((field) => {
                        if (field.user == loggedInUser && (field.value == "" || field.value == undefined)) {
                            invalid = true;
                        }
                    })
                    if (invalid) {
                        alert("You can not save the form without filling all fields of yours")
                        break;
                    }
                }
                if (invalid) {
                    return;
                }
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
                    $newTabTemplate.parentElement.insertBefore($newTab, $newTabTemplate);
                    $fbPages.tabs("refresh");
                    $fbPages.tabs("option", "active", 1);
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
                }, 2000);


            })
        </script>

        @if (auth()->user()->role != 'admin')
            // @push('scripts')
                <script>
                    setTimeout(() => {
                        $("#form-builder-pages input,#form-builder-pages select,#form-builder-pages textarea").prop("disabled",
                            true)
                    }, 1000);
                </script>
                //
            @endpush
        @endif
    @endpush

@endsection
