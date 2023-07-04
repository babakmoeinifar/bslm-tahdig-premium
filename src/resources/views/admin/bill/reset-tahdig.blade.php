@extends('template.master-admin')

@section('title', 'اجرای فرآیند تسویه حساب')

@section('content')
    <div class="page-title mb-3 d-print-none">
        <div class="row">
            <div class="col-md-4">
                <h3>
                    اجرای فرآیند تسویه حساب
                </h3>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">

                        <div id="preloader">
                            <div class="spinner"></div>
                        </div>

                        <div class="progress mb-3">
                            <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                                 aria-valuemax="100"></div>
                        </div>

                        <div id="progress-container" style="min-height: 300px"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: transparent;
            z-index: 9999;
        }

        .spinner {
            position: absolute;
            top: 35%;
            right: 35%;
            width: 50px;
            height: 50px;
            border: 5px solid #ccc;
            border-top-color: #333;
            border-radius: 50%;
            animation: spin 1s infinite linear;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

    </style>
@endpush
@push('js')

    <script>

        $(document).ready(function () {
            function automateSettle() {
                $('#preloader').show();

                $.ajax({
                    url: '/automate-settle',
                    type: 'POST',
                    dataType: 'json',
                    success: function (data) {
                        var progressContainer = $('#progress-container');
                        var progressBar = $('.progress-bar');

                        var progressMessages = data.messages;
                        var progressContainer = $('#progress-container');

                        $('#preloader').hide();

                        printMessagesWithDelay(progressMessages, progressContainer, progressBar)
                            .then(function () {
                                progressBar.width('100%');
                                progressBar.text('پایان فرآیند تسوه حساب');

                                redirect('/admin/bills/lunch-users-export')
                                    .then(function () {
                                        progressContainer.append('<p>' + 'آماده سازی خروجی اکسل، دانلود به صورت اتوماتیک انجام میشود، لطفاً تا پایان دانلود رفرش نکنید...' + '</p>');
                                    });
                            })
                            .catch(function (error) {
                                console.error(error);
                            });


                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            }

            automateSettle();

            function printMessagesWithDelay(messages, container, progressBar) {
                return new Promise(function (resolve, reject) {
                    var i = 0;
                    var totalMessages = messages.length;
                    var progressPercentage = 0;

                    function printMessage() {
                        if (i < totalMessages) {
                            var message = messages[i];
                            container.append('<p>' + message + '</p>');
                            i++;

                            progressPercentage = Math.round((i / totalMessages) * 100);
                            progressBar.width(progressPercentage + '%');
                            progressBar.text(progressPercentage + '%');

                            setTimeout(printMessage, 2000);
                        } else {
                            resolve();
                        }
                    }

                    printMessage();
                });
            }

            function redirect(url) {
                return new Promise(function (resolve, reject) {
                    window.location.replace(url);
                    resolve();
                });
            }

        });
    </script>
@endpush
