@extends('layout/main')

@section('content')
    @php
    $tanggal = (int) Str::substr($current_date, 8, 2);
    $bulan = (int) Str::substr($current_date, 5, 2);
    $tahun = (int) Str::substr($current_date, 0, 4);

    if (strlen($tanggal) != 2) {
        $tanggal = '0' . $tanggal;
    }
    if (strlen($bulan) != 2) {
        $bulan = '0' . $bulan;
    }
    @endphp
    <!-- Order Page -->
    <section id="pendaftaran-menu">
        @if (session()->has('status'))
            <div class="container mt-4">
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
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img"
                            aria-label="Success:">
                            <use xlink:href="#check-circle-fill" />
                        </svg>
                        <b>[ {{ $current_date }} ]</b> : ({{ session('nolab') }}) Data pemeriksaan pasien
                        <b>{{ session('namapasien') }}</b> berhasil ditambahkan ! --- <a class="text-success"
                            href="/pendaftaran/{{ session('nolab') }}/order">Select</a> ---
                        <button type="button" class="btn-close" id="auto-cls" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @else
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
                            <use xlink:href="#info-fill" />
                        </svg>
                        <b>[ {{ $current_date }} ]</b> : ({{ session('nolab') }}) Data pemeriksaan pasien
                        <b>{{ session('namapasien') }}</b> berhasil
                        dihapus !
                        <button type=" button" class="btn-close" id="auto-cls" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif
            </div>
        @endif
        <div class="container mt-4 mb-4 shadow">
            <div class="row bg-light pt-4">
                <h4 class="col-8">
                    Order Pemeriksaan
                </h4>
                <div id="extra-1" class="col-3 offset-1">
                    <input name="search" class="form-control select-field" type="text" placeholder="Select patient..."
                        aria-label="Search" onfocusout="hidePatientList()" autocomplete="off">

                    <div class="position-absolute">
                        <div id="patient-list" class="card mb-3" style="max-width: 540px;">
                        </div>
                    </div>
                </div>
            </div>

            <div id="content-1">
                @include('pendaftaran/order-reg-new')
            </div>
        </div>
    </section>
    <!-- End of Order Page -->
@endsection
