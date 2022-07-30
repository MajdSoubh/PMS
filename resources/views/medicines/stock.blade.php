@extends("layouts.master")
@section('title', 'PMS')
@push('head')
    <link rel="stylesheet" href="{{ asset('css/stock.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('js/stock_manage.js') }}"></script>
    <script src="{{ asset('js/popup.js') }}"></script>
    <script src="{{ asset('js/suggestion.js') }}"></script>
@endpush
@section('main')

    <!-- Main -->
    <main>
        <h1 class="title">Manage Stock</h1>
        <div class="container">


            <div class="bill">
                <div class="bill-header search-area">

                    <div class="bill-labelsandinputs">
                        <div class="sub-title">Search By Medicine Name:</div>
                        <input oninput="searchPage()" id="sea-name" class="input-field" type="text"
                            placeholder="Medicine Name" name="name" value="">
                    </div>

                    <div class="bill-labelsandinputs">
                        <div class="sub-title">Search By Generic Name:</div>
                        <input oninput="searchPage()" id="sea-generic" class="input-field" type="text"
                            placeholder="Generic Name" name="generic" value="">
                    </div>

                    <div class="bill-labelsandinputs">
                        <div class="sub-title">Search By Supplier Name:</div>
                        <input oninput="searchPage()" id="sea-supplier" class="input-field" type="text"
                            placeholder="Supplier Name" name="supplier" value="">
                    </div>

                    <div>
                        <button onclick="addRule(this)" id="out-of-stock-btn">Out Of Stock</button>
                    </div>
                    <div>
                        <button onclick="addRule(this)" id="expire-btn">Expire</button>
                    </div>
                    <form action="{{ asset('stock/download') }}" method="POST">
                        <div class="pdf-file">
                            {{ csrf_field() }}
                            <button id="generate-pdf" type="submit">Generate PDF</button>

                        </div>
                    </form>
                </div>
                <hr>
                <div id="table-area" class="table-data">
                    @push('scripts')
                        <script>
                            searchPage();
                        </script>
                    @endpush
                </div>
                <hr>

                <div class=btn-adjustment>
                    <button onclick="openPopup('/ajax/popup/stock/add')" id="add-entry-btn">Add New Entry</button>
                </div>

            </div>
        </div>

        <!-- End Of Container -->

    </main>
    <!-- End Of Main -->

@endsection

@section('popup')

@endsection
