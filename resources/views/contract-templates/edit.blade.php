@extends('layout.app')
@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/jquery.signature.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bundles/select2/dist/css/select2.min.css') }}">
    <script>
        var allUsers = @json($selectUsers);
    </script>
    <style>
        .formbuilder-icon-number,
        .formbuilder-icon-radio-group,
        .formbuilder-icon-select,
        .formbuilder-icon-textarea {
            display: none;
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <!-- Modal -->
        <div class="col-md-11 mx-auto">
            <div class="spinner-border text-black spinner-border-sm main-loader" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>
                        Edit Template
                        {{-- <a href="#signature-modal" class="add-sign" data-value="${fieldData.name}" data-toggle="modal">Add Signature</a> --}}
                    </h4>
                    <a href="{{ route('template') }}" class="btn btn-primary btn-sm">All Templates</a>
                </div>
                <div class="card-body py-3 pb-5">
                    @include('layout.alerts')

                    <form action="{{ route('template.update') }}" method="POST" id="form-builder-pages"
                        onsubmit="disableSubmitButton()">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="title">Template Title <small class="text-danger">*</small></label>
                                <input type="text" id="title" required name="title"
                                    placeholder="Enter Template Title" value="{{ $template->title }}" class="form-control">
                                @error('title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="category">Category</label>
                                <select name="category" id="category" required class="form-control">
                                    <option value="" disabled selected>Select From the Following</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $template->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}</option>
                                    @endforeach
                                    <option value="other" {{ is_null($template->category_id) ? 'selected' : '' }}>Other
                                    </option>

                                </select>
                                @error('category')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <input type="hidden" name="id" value="{{ $template->id }}">
                        <ul id="tabs" class="nav nav-tabs page-count-tabs">
                            <li class="nav-item" id="add-page-tab"><a class="nav-link" href="#new-page">+ Page</a></li>
                        </ul>
                        <div id="new-page" class="tabs-pages"></div>


                        <div class="form-group col-md-12 d-none">
                            <label for="form-data">Enter The Code Here</label>
                            <input name="details" id="form-data" />
                        </div>
                        <div class="col-md-12 mt-4">
                            <button class="btn btn-primary btn-block submit-button" onclick="exitCheck = true ;"
                                type="submit" id="submitButton">Save</button>
                        </div>
                    </form>
                    {{-- <div class="save-all-wrap"><button id="save-all" type="button">Save All</button></div> --}}

                </div>
            </div>
        </div>
    </div>
    @include('layout.sign-modal')
    @include('layout.image-modal')
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <script src="{{ asset('assets/bundles/form-builder/form-builder.min.js') }}"></script>
        <script src="{{ asset('assets/bundles/form-builder/form-render.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/js/jquery.signature.js') }}"></script>
        <script src="{{ asset('assets/js/signature_pad.min.js') }}"></script>
        <script src="{{ asset('assets/js/signature.js') }}"></script>
        <script src="{{ asset('assets/bundles/select2/dist/js/select2.full.min.js') }}"></script>

        <script type='text/javascript'>
            function disableSubmitButton() {
                var submitButton = document.getElementById('submitButton');
                submitButton.disabled = true;
                submitButton.style.opacity = '0.5';
                submitButton.style.cursor = 'not-allowed';
            }
            <?php
            $js_array = $template->form_data;
            echo 'var javascript_array = ' . $js_array . ";\n";
            ?>
            let data = javascript_array;
        </script>
        <script>
            var exitCheck = false;
            window.addEventListener('beforeunload', function(e) {
                if (exitCheck) {
                    return;
                }
                e.preventDefault();
                e.returnValue = '';
                var confirmationMessage = 'Are you sure you want to leave this page? Your data may not be saved.'
                    (event || window.event).returnValue = confirmationMessage;
                return confirmationMessage;
            });
            var changesMade = false;
            var $fbPages = $(document.getElementById("form-builder-pages"));
            var addPageTab = document.getElementById("add-page-tab");
            var fbInstances = [];
            jQuery(($) => {
                "use strict";

                $fbPages.tabs({
                    beforeActivate: function(event, ui) {
                        if (ui.newPanel.selector === "#new-page") {
                            return false;
                        }
                    }
                });

                addPageTab.addEventListener(
                    "click",
                    (click) => {
                        const tabCount = document.getElementById("tabs").children.length;
                        const tabId = "page-" + tabCount.toString();
                        const $newPageTemplate = document.getElementById("new-page");
                        const $newTabTemplate = document.getElementById("add-page-tab");
                        const $newPage = $newPageTemplate.cloneNode(true);
                        $newPage.setAttribute("id", tabId);
                        $newPage.classList.add("fb-editor");
                        const $newTab = $newTabTemplate.cloneNode(true);
                        $newTab.removeAttribute("id");
                        const $tabLink = $newTab.querySelector("a");
                        $tabLink.setAttribute("href", "#" + tabId);
                        $tabLink.innerText = "Page " + tabCount;

                        $newPageTemplate.parentElement.insertBefore($newPage, $newPageTemplate);
                        $newTabTemplate.parentElement.insertBefore($newTab, $newTabTemplate);
                        $fbPages.tabs("refresh");
                        $fbPages.tabs("option", "active", tabCount - 1);
                        fbInstances.push($($newPage).formBuilder(fBOptions));
                    },
                    false
                );




                let count = 0;
                var mainInterval = setInterval(() => {


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
                    if (count == 0) {
                        $newTab.querySelector("a").classList.add("active"); //custom
                    }
                    const $tabLink = $newTab.querySelector("a");
                    $tabLink.setAttribute("href", "#" + tabId);
                    $tabLink.innerText = "Page " + (tabCount);
                    // console.log(tabCount);

                    $newPageTemplate.parentElement.insertBefore($newPage, $newPageTemplate);
                    $newTabTemplate.parentElement.insertBefore($newTab, $newTabTemplate);
                    // console.log($newTabTemplate);
                    if (count == 0) {
                        $fbPages.tabs("option", "active", 1);
                    }
                    $fbPages.tabs("refresh");
                    $fbPages.tabs("option", "active", 0);

                    fbInstances.push($($newPage).formBuilder({
                        ...fBOptions,
                        formData: data[count]
                    }));

                    count++;
                    if (count >= data.length) {
                        $(".main-loader").hide()
                        clearInterval(mainInterval)
                    }

                }, 2000);

            });
            $(document).ready(function() {
                // Digital Signature Config
                $(".select2").select2()
                setTimeout(() => {
                    var sig = $('#sign-box').signature({
                        syncField: '#signature',
                        syncFormat: 'PNG'
                    });
                    $('#clear').click(function(e) {
                        e.preventDefault();
                        sig.signature('clear');
                        $("#signature64").val('');
                    });
                }, 2000);

            })

            $("#form-builder-pages").on("submit", function(e) {
                exitCheck = true;

                e.preventDefault();
                $(".main-loader").show()
                const allData = [];

                for (let i = 0; i < fbInstances.length; i++) {
                    allData.push(fbInstances[i].actions.getData())
                    console.log(fbInstances[i].actions.getData());
                }

                $("#form-data").val(JSON.stringify(allData))
                // Remove the event listener
                $(this).off('submit');
                // Submit the form manually
                $(this).submit()
            })
            // $("#submit-button").on("click", function(e) {
            //     e.preventDefault();
            //     $(".main-loader").show()
            //     const allData = fbInstances.map((fb) => {
            //         // return fb.actions.getData();
            //         return fb.options.formData;
            //     });
            //     $("#form-data").val(JSON.stringify(allData))
            //     // Remove the event listener
            //     $(this).off('submit');
            //     // Submit the form manually
            //     $(this).submit()
            // })

            function copyData() {
                $(".copy-button").prop('disabled', (i, v) => !v).find(".c-loader").show();
                const allData = fbInstances.map((fb) => {
                    return fb.actions.getData();
                });
                $("#form-data").val(JSON.stringify(allData))
                setTimeout(() => {
                    $(".copy-button").prop('disabled', (i, v) => !v).find(".c-loader").hide()
                    $("#form-builder-pages").submit();
                }, 500);
            }

            function copyImgName(selectedSign) {
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val(selectedSign).select();
                document.execCommand("copy");
                $temp.remove();
            }
        </script>
    @endpush
@endsection
