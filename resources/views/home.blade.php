@extends('layout/main')

@section('content')
<!-- Main Menu -->
<section id="main-menu">
    <div class="container">
        <div class="row mt-4 mb-4 justify-content-center">
            <div class="col-2">
                <a href="/pendaftaran" class="main-feature">
                    <div class="card btn btn-outline-secondary shadow-sm text-center">
                        <img src="assets/logo_pendaftaran.png" class="card-img" alt="Logo Pendaftaran">
                        <div class="card-img-overlay">
                            <b>Pendaftaran</b>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-2">
                <a href="" class="main-feature">
                    <div class="card btn btn-outline-secondary shadow-sm text-center">
                        <img src="assets/ambil_sampel.png" class="card-img" alt="Logo Pendaftaran">
                        <div class="card-img-overlay">
                            <b>Ambil Sampel</b>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-2">
                <a href="" class="main-feature">
                    <div class="card btn btn-outline-secondary shadow-sm text-center">
                        <img src="assets/terima_sampel.png" class="card-img" alt="Logo Pendaftaran">
                        <div class="card-img-overlay">
                            <b>Terima Sampel</b>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-2">
                <a href="" class="main-feature">
                    <div class="card btn btn-outline-secondary shadow-sm text-center">
                        <img src="assets/proses_sampel.png" class="card-img" alt="Logo Pendaftaran">
                        <div class="card-img-overlay">
                            <b>Proses Sampel</b>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row mb-4 justify-content-center">
            <div class="col-2">
                <a href="" class="main-feature">
                    <div class="card btn btn-outline-secondary shadow-sm text-center">
                        <img src="assets/input_hasil.png" class="card-img" alt="Logo Pendaftaran">
                        <div class="card-img-overlay">
                            <b>Input Hasil</b>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-2">
                <a href="" class="main-feature">
                    <div class="card btn btn-outline-secondary shadow-sm text-center">
                        <img src="assets/validasi_hasil.png" class="card-img" alt="Logo Pendaftaran">
                        <div class="card-img-overlay">
                            <b>Validasi Hasil</b>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-2">
                <a href="" class="main-feature">
                    <div class="card btn btn-outline-secondary shadow-sm text-center">
                        <img src="assets/cetak_hasil.png" class="card-img" alt="Logo Pendaftaran">
                        <div class="card-img-overlay">
                            <b>Cetak Hasil</b>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>
<!-- End of Main Menu -->

<hr>

<!-- Sub Menu -->
<section id="sub-menu">
    <div class="container">
        <div class="row mt-4 justify-content-between text-center">
            <div class="col-2">
                <a href="#" class="btn btn-lg btn-outline-secondary rounded-circle"><img src="assets/master.png" alt="Logo Master"></a>
                <p class="card-title text-secondary">Master</p>
            </div>
            <div class="col-2">
                <a href="#" class="btn btn-lg btn-outline-secondary rounded-circle"><img src="assets/utility.png" alt="Logo Master"></a>
                <p class="card-title text-secondary">Utility</p>
            </div>
            <div class="col-2">
                <a href="#" class="btn btn-lg btn-outline-secondary rounded-circle"><img src="assets/report.png" alt="Logo Master"></a>
                <p class="card-title text-secondary">Report</p>
            </div>
        </div>
    </div>
</section>
<!-- End of Sub Menu -->
@endsection