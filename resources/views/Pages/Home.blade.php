@extends('Components.ThreeJSPage')

@section('page-content')
    <div id="home-page">
        <div id="start-scan-intro-box">
            <div id="start-scan-intro-box-box">
                <h3>Start your Chrome Driver!</h3>
                <p>
                    Scan all the listed stores and store the prices into the Coeus database. From there, we will
                    create an exportable file that we can mail to any interested parties.
                </p>
            </div>
        </div>
        <div id="home-chrome-scanning-info-section">
            <h4>We are currently scanning specials</h4>
            <p>
                This process will take a while as there are many stores and products to run through.
                Do not navigate away!
            </p>
        </div>
        <div class="start-button-box">
            @include('Components.StartScanButton')
        </div>
        <div id="scan-results-block">
            <h4>
                Scan Completed
            </h4>
            <p>
                Your scan has finished, the results have been saved and are ready for use.
            </p>
        </div>
    </div>
        <script src="{{ asset('/js/pages/Home.js') }}"></script>
    </div>
@endsection
