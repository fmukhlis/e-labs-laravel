@extends('layout/main')

@section('content')
    @php
    $tanggal = (int) Str::substr($current_date, 8, 2);
    $bulan = (int) Str::substr($current_date, 5, 2);
    $tahun = (int) Str::substr($current_date, 0, 4);
    @endphp
    <!-- Order Page -->
    <section id="pendaftaran-menu">
        <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
            <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                <path
                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
            </symbol>
            <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
                <path
                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
            </symbol>
            <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
                <path
                    d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
            </symbol>
        </svg>
        @if (session()->has('status'))
            <div class="container mt-4">
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
                            <use xlink:href="#check-circle-fill" />
                        </svg>
                        <b>[ {{ $current_date }} ]</b> : ( {{ $test_data->no_lab }} ) Data pemeriksaan pasien
                        <b>{{ $test_data->pasien->nama }}</b> berhasil diupdate !
                        <button type="button" class="btn-close" id="auto-cls" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @else
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
                            <use xlink:href="#info-fill" />
                        </svg>
                        <b>[ {{ $current_date }} ]</b> : ( {{ $test_data->no_lab }} ) Data pemeriksaan pasien
                        <b>{{ $test_data->pasien->nama }}</b> berhasil dihapus !
                        <button type="button" class="btn-close" id="auto-cls" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif
        @endif
        <div class="container mt-4 mb-4 shadow">
            <div class="row bg-light pt-4">
                <h4 class="col-8">
                    Order Pemeriksaan
                </h4>
                <div id="extra-1" class="col-4 text-end">
                    <form action="/pendaftaran/{{ $test_data->no_lab }}/order" method="post" class="d-inline">
                        @method('delete')
                        @csrf
                        <button class="btn btn-danger"
                            onclick="return confirm('Anda yakin ingin menghapus data pemeriksaan ini ?')">
                            Delete
                        </button>
                    </form>
                    <a href="/pendaftaran/order/" id="btn-order-save" class="btn btn-outline-success">
                        New...
                    </a>
                </div>
                <div id="extra-2" class="col-4 d-none">
                    <div class="row">
                        <label for="validationCustom01" class="col-6 col-form-label text-end">Tgl. Pemeriksaan</label>
                        <div class="col-6">
                            <input type="text" class="form-control text-center" id="validationCustom01"
                                value="{{ $tanggal }} / {{ $bulan }} / {{ $tahun }}" disabled>
                        </div>
                    </div>
                </div>
            </div>

            <div id="content-1" class="">
                @include('pendaftaran/order-reg')
            </div>
            <div id="content-2" class="d-none">
                @include('pendaftaran/order-test')
            </div>
            <div id="content-3" class="d-none">
                @include('pendaftaran/order-paying')
            </div>
        </div>

        <input id="pagenum" type="hidden" value="1">
    </section>
    <!-- End of Order Page -->
@endsection
