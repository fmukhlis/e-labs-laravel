<form action="/pendaftaran/{{ $test_data->no_lab }}/order" method="post">
    @method('put')
    @csrf
    <div class="row bg-light">
        <div class="container">
            <hr class="mt-3">
            <div class="row">
                <label class="col-1 col-form-label">No. Lab.</label>
                <div class="col-2">
                    <input type="text" class="form-control @error('nolab') is-invalid @enderror"
                        value="{{ $test_data->no_lab }}" readonly>
                </div>
                <label class="col-1 col-form-label text-end">No. RM</label>
                <div class="col-3">
                    <input type="text" class="form-control @error('nolab') is-invalid @enderror"
                        value="{{ $test_data->pasien->no_rm }}" readonly>
                </div>

                <label class="col-1 offset-1 col-form-label text-end">No. KTP</label>
                <div class="col-3">
                    <input type="text" class="form-control" value="{{ $test_data->pasien->no_ktp }}" readonly>
                </div>
            </div>
            <div class="row mt-2">
                <label class="col-1 col-form-label">Nama</label>
                <div class="col-4">
                    <input type="text" class="form-control" value="{{ $test_data->pasien->nama }}" readonly>
                </div>
                <div class="col-2">
                    <input type="text" class="form-control text-center"
                        value="{{ $test_data->pasien->jenis_kelamin }}" readonly>
                </div>
                <label class="col-1 offset-1 col-form-label text-end">Umur</label>
                <div class="col-2">
                    <input type="text" class="form-control" value="{{ $patient_age }}" readonly>
                </div>
            </div>
            <hr class="mt-3">
        </div>
    </div>

    <div class="row bg-light pb-1 justify-content-center">
        <h5 class="text-center">Pengirim & Pemeriksaan</h5>
        <hr class="pt-1 mt-2 mb-3">
    </div>

    <div class="row bg-light">
        <div class="col-5">
            <div class="container">
                <h6 class="text-center">Pengirim / Poli / Ruangan</h6>
                <hr>
                <div class="row g-3">
                    <div class="col-12">
                        <div class="row g-2">
                            <label for="asalruangan" class="col-3 col-form-label">Asal
                                Ruangan</label>
                            <div class="col-9">
                                <select class="form-select" id="asalruangan" name="asalruangan">
                                    <option selected value="">---Pilih Ruangan---</option>
                                    @foreach ($rooms as $room)
                                        <option {{ old('asalruangan') == $room->no_sep ? 'selected' : '' }}
                                            value="{{ $room->no_sep }}">{{ $room->ruangan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <label for="nosep" class="col-2 col-form-label">No. SEP</label>
                            <div class="col-3">
                                <input type="text" class="form-control" id="nosep" name="nosep"
                                    value="{{ old('nosep', $test_data->no_sep) }}">
                            </div>

                            <label for="metodebayar" class="col-3 col-form-label text-end pe-1">Metode
                                Bayar</label>
                            <div class="col-4">
                                <select class="form-select" id="metodebayar" name="metodebayar"
                                    onchange="onMetodeBayarChange()">
                                    <option {{ old('metodebayar') == 'Cash' ? 'selected' : '' }} value="Cash">
                                        Cash
                                    </option>
                                    <option {{ old('metodebayar') == 'BPJS' ? 'selected' : '' }} value="BPJS">
                                        BPJS
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>

                <h6 class="text-center mt-4">Dokter</h6>
                <hr>
                <div id="doctorTableSection" class="row g-1">
                    <div class="col-12">
                        <div id="data-table">
                            <table class="table table-hover table-bordered">
                                <thead class="table-secondary">
                                    <tr>
                                        <th class="text-center" scope="col" style="width: 8%">#</th>
                                        <th scope="col" style="width: 15%">Kode</th>
                                        <th scope="col" style="width: 77%">Nama Dokter</th>
                                    </tr>
                                </thead>
                                <tbody id="doctorTable" class="table-secondary">
                                    <tr>
                                        <td class="text-center" colspan="3">Loading...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-5">
                        <div id="remove-tabledoctor" class="btn btn-outline-danger disabled">
                            Remove Selected
                        </div>

                    </div>
                    <div class="col-7 text-end">
                        <div id="add-tabledoctor" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#selectDoctorModal">
                            Select
                        </div>
                        <div id="edit-tabledoctor" class="btn btn-outline-secondary disabled" data-bs-toggle="modal"
                            data-bs-target="#editDoctorModal">
                            Edit Selected
                        </div>
                    </div>
                </div>
                <hr>
            </div>
        </div>

        <div class="col-7">
            <div id="testTableSection" class="container">
                <h6 class="text-center">Pemeriksaan</h6>
                <hr>
                <div class="row g-1">
                    <div class="col-12">
                        <table class="table table-hover table-bordered">
                            <thead class="table-secondary">
                                <tr>
                                    <th class="text-center" scope="col" style="width: 5%">#</th>
                                    <th scope="col" style="width: 10%">Kode</th>
                                    <th scope="col" style="width: 35%">Nama Pemeriksaan</th>
                                    <th scope="col" style="width: 15%">Disc.</th>
                                    <th class="text-center" scope="col" style="width: 10%">CITO</th>
                                    <th class="text-center" scope="col" style="width: 25%">Harga</th>
                                </tr>
                            </thead>
                            <tbody id="testTable" class="table-secondary">
                                <tr>
                                    <td class="text-center" colspan="6">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-12">
                        <table class=" table table-hover table-bordered mb-2">
                            <tbody id="testTablePrice" class="table-secondary text-center">
                                <tr>
                                    <th scope="row" style="width: 20%">Bruto</th>
                                    <td style="width: 80%" class="text-end">Calculating...</td>
                                </tr>
                                <tr>
                                    <th scope="row">Netto</th>
                                    <td class="text-end">Calculating...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-5 offset-7 text-end">
                        <div id="remove-tabletest" class="btn btn-outline-danger disabled">
                            Remove Selected
                        </div>
                        <div id="add-tabletest" class="btn btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#testModal">
                            Add
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="testModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Pilih Pemeriksaan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="container-fluid">
                                        <div id="pilihan-kategori-pemeriksaan" class="row g-2 justify-content-center">
                                            @foreach ($test_cat as $cat)
                                                <div class="col-3">
                                                    <div data-value="{{ $cat->id }}"
                                                        class="col-12 btn btn-outline-secondary">{{ $cat->nama }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div id="pilihan-pemeriksaan" class="border border-1 p-4">
                                            @foreach ($test_cat as $cat)
                                                <div id="kategori-pemeriksaan-{{ $cat->id }}"
                                                    class="row gy-1 gx-2 d-none">
                                                    <h6 class="col-8 modal-title mt-0">{{ $cat->nama }}</h6>
                                                    <a href="" id="kategori-pemeriksaan-back"
                                                        class="col-4 p-0 m-0 text-end text-decoration-none">Back</a>
                                                    <hr class="mb-2">
                                                    @foreach ($cat->pemeriksaan as $pemeriksaan)
                                                        <div class="col-3">
                                                            <div class="col-12 form-check form-check-inline">
                                                                <input id="test{{ $pemeriksaan->kode }}"
                                                                    class="col-1 form-check-input" type="checkbox"
                                                                    value="{{ $pemeriksaan->kode }}">
                                                                <label class="col-11 form-check-label text-truncate"
                                                                    for="test{{ $pemeriksaan->kode }}">{{ $pemeriksaan->nama }}</label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="button" id="saveTest" class="btn btn-primary">Save
                                        changes</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <hr>
            </div>
        </div>

        <div class="col-12">
            <hr>
            <div class="row g-1 px-3">
                <div class="col-6">
                    <div class="form-check pt-2">
                        <input id="homeservice" class="form-check-input" name="homeservice" type="checkbox">
                        <label class="form-check-label" for="homeservice">
                            Pasien Home Service
                        </label>
                    </div>
                </div>

                <div class="col-6 text-end">
                    <div class="btn btn-outline-secondary">
                        Add Notes
                    </div>
                </div>
            </div>
            <hr>
        </div>
    </div>

    <div class="row bg-light pt-2 pb-1 justify-content-center">
        <hr class="pt-1">
    </div>

    <div id="content-controller" class="row bg-light pt-2 pb-4 gx-0 justify-content-between">
        <div class="col-3 text-start">
            <div class="btn btn-secondary btn-order-prev">
                Previous
            </div>
        </div>
        <div class="col-6 text-center">
            <button type="submit" class="btn btn-success btn-order-save">
                Save Data
            </button>
        </div>
        <div class="col-3 text-end">
            <div class="btn btn-primary btn-order-next">
                Next
            </div>
        </div>
    </div>
</form>

<!-- Doctor Modal -->
<div class="modal fade" id="selectDoctorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Pilih Dokter Pengirim</h5>
                <button type="button" id="close-doc-btn" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    {{-- Form Select Doctor --}}
                    <div id="select-doc-1" class="row g-2">
                        <label for="pilihdokter" class="col-3 col-form-label">Pilih
                            dokter</label>
                        <div class="col-9">
                            <input name="pilihdokter" id="pilihdokter" class="form-control" type="text"
                                placeholder="Cari berdasarkan nama..." autocomplete="off"
                                onfocusout="hideDoctorList()">

                            <div id="doctor-list" class="card border-0">
                                <div class="container p-0"></div>
                            </div>
                        </div>
                        <div class="col-4 text-muted"><small>Dokter tidak ditemukan ?</small>
                        </div>
                        <div class="col-4">
                            <div id="add-doc-btn" class="btn btn-outline-success btn-sm">
                                Tambahkan dokter</div>
                        </div>
                    </div>
                    {{-- End Of Form Select Doctor --}}
                    {{-- Form Add Doctor --}}
                    <div id="select-doc-2" class="row g-2 d-none">
                        <div id="error-doc-alert"
                            class="alert alert-danger alert-dismissible fade show mt-0 mb-0 d-none" role="alert">
                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img"
                                aria-label="Danger:">
                                <use xlink:href="#exclamation-triangle-fill" />
                            </svg>
                            Kesalahan validasi. Gagal menambahkan data dokter.
                        </div>
                        <label for="kodedokter" class="col-3 col-form-label">Kode dokter</label>
                        <div class="col-3">
                            <input id="kodedokter" name="kodedokter" class="form-control" type="text"
                                onkeypress="return onlyNumInput(event)">
                        </div>
                        <div class="col-6 text-end">
                            <div id="select-doc-btn" class="btn btn-outline-success btn-sm">
                                Pilih dokter</div>
                        </div>
                        <label for="spesialisasi" class="col-3 col-form-label">Spesialisasi</label>
                        <div class="col-7">
                            <select class="form-select" id="spesialisasi" name="spesialisasi">
                                <option selected value="">---Pilih Spesialisasi---</option>
                                @foreach ($spesialisasi as $s)
                                    <option {{ old('spesialisasi') == $s->gelar ? 'selected' : '' }}
                                        value="{{ $s->gelar }}">{{ $s->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <label for="namadokter" class="col-3 col-form-label">Nama</label>
                        <div class="col-9">
                            <input id="namadokter" name="namadokter" class="form-control" type="text"
                                autocomplete="off">
                        </div>
                        <label for="noskp" class="col-3 col-form-label">No. SKP</label>
                        <div class="col-8">
                            <input id="noskp" name="noskp" class="form-control" type="text"
                                autocomplete="off">
                        </div>
                        <label for="nosertifskp" class="col-3 col-form-label">No. Sertif.
                            SKP</label>
                        <div class="col-8">
                            <input id="nosertifskp" name="nosertifskp" class="form-control" type="text"
                                autocomplete="off">
                        </div>
                        <label for="ttddokter" class="col-3 col-form-label">TTD
                            Dokter</label>
                        <div class="col-6">
                            <input id="ttddokter" name="ttddokter" class="form-control form-control-sm"
                                type="file">
                        </div>
                        <hr class="mb-0 mt-3">
                        <label for="alamatdokter" class="col-12 mt-0 text-center bg-light">Alamat
                            Dokter</label>
                        <div class="col-12">
                            <textarea class="form-control" id="alamatdokter" name="alamatdokter" rows="2" placeholder="Detail Alamat"
                                required></textarea>
                        </div>
                        <div class="col-12">
                            <textarea class="form-control mb-2" id="alamatpraktek" name="alamatpraktek" rows="2"
                                placeholder="Detail Alamat Praktek" required></textarea>
                        </div>
                        <hr class="mb-0 mt-3">
                        <label for="notelpdokter" class="col-12 mt-0 text-center bg-light">Kontak
                            Dokter</label>
                        <label for="notelpdokter" class="col-3 col-form-label">No.
                            Telpon</label>
                        <div class="col-9">
                            <input id="notelpdokter" name="notelpdokter" class="form-control" type="text"
                                autocomplete="off">
                        </div>
                        <label for="nohpdokter" class="col-3 col-form-label">No. Hp</label>
                        <div class="col-9">
                            <input id="nohpdokter" name="nohpdokter" class="form-control" type="text"
                                autocomplete="off">
                        </div>
                        <label for="emaildokter" class="col-3 col-form-label">Email</label>
                        <div class="col-9">
                            <input id="emaildokter" name="emaildokter" class="form-control" type="text"
                                autocomplete="off">
                        </div>
                    </div>
                    {{-- End of Form Add Doctor --}}
                </div>
            </div>
            <div class="modal-footer">
                <div class="container">
                    <div class="row">
                        <div class="col-8"></div>
                        <div class="col-4 px-0 text-end">
                            <button type="button" id="cancel-doc-btn" class="btn btn-secondary"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="button" id="save-doc-btn-1" class="btn btn-primary d-none">Add</button>
                            <button type="button" id="save-doc-btn-2" class="btn btn-primary"
                                data-bs-dismiss="modal" hidden>Select</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Doctor Modal -->
<div class="modal fade" id="editDoctorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Dokter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div id="select-doc-3" class="row g-2">
                        <div id="error-edit-doc-alert"
                            class="alert alert-danger alert-dismissible fade show mt-0 mb-0" role="alert">
                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img"
                                aria-label="Danger:">
                                <use xlink:href="#exclamation-triangle-fill" />
                            </svg>
                            Kesalahan validasi. Gagal mengubah data dokter.
                        </div>
                        <label for="kodedokter-edit" class="col-3 col-form-label">Kode
                            dokter</label>
                        <div class="col-4">
                            <input id="kodedokter-edit" name="kodedokter-edit" class="form-control" type="text">
                        </div>
                        <div class="col-5"></div>
                        <label for="spesialisasi-edit" class="col-3 col-form-label">Spesialisasi</label>
                        <div class="col-7">
                            <select class="form-select" id="spesialisasi-edit" name="spesialisasi-edit">
                                <option selected value="">---Pilih Spesialisasi---</option>
                                @foreach ($spesialisasi as $s)
                                    <option value="{{ $s->gelar }}">{{ $s->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <label for="namadokter-edit" class="col-3 col-form-label">Nama</label>
                        <div class="col-9">
                            <input id="namadokter-edit" name="namadokter-edit" class="form-control" type="text"
                                placeholder="Nama dan gelar lengkap..." autocomplete="off">
                        </div>
                        <label for="noskp-edit" class="col-3 col-form-label">No. SKP</label>
                        <div class="col-8">
                            <input id="noskp-edit" name="noskp-edit" class="form-control" type="text"
                                autocomplete="off">
                        </div>
                        <label for="nosertifskp-edit" class="col-3 col-form-label">No. Sertif.
                            SKP</label>
                        <div class="col-8">
                            <input id="nosertifskp-edit" name="nosertifskp-edit" class="form-control" type="text"
                                autocomplete="off">
                        </div>
                        <label for="ttddokter-edit" class="col-3 col-form-label">TTD
                            Dokter</label>
                        <div class="col-6">
                            <input id="ttddokter-edit" name="ttddokter-edit" class="form-control form-control-sm"
                                type="file">
                        </div>
                        <hr class="mb-0 mt-3">
                        <label for="alamatdokter-edit" class="col-12 mt-0 text-center bg-light">Alamat
                            Dokter</label>
                        <div class="col-12">
                            <textarea class="form-control" id="alamatdokter-edit" name="alamatdokter-edit" rows="2"
                                placeholder="Detail Alamat" required></textarea>
                        </div>
                        <div class="col-12">
                            <textarea class="form-control mb-2" id="alamatpraktek-edit" name="alamatpraktek-edit" rows="2"
                                placeholder="Detail Alamat Praktek" required></textarea>
                        </div>
                        <hr class="mb-0 mt-3">
                        <label for="notelpdokter-edit" class="col-12 mt-0 text-center bg-light">Kontak
                            Dokter</label>
                        <label for="notelpdokter-edit" class="col-3 col-form-label">No.
                            Telpon</label>
                        <div class="col-9">
                            <input id="notelpdokter-edit" name="notelpdokter-edit" class="form-control"
                                type="text" autocomplete="off">
                        </div>
                        <label for="nohpdokter-edit" class="col-3 col-form-label">No. Hp</label>
                        <div class="col-9">
                            <input id="nohpdokter-edit" name="nohpdokter-edit" class="form-control" type="text"
                                autocomplete="off">
                        </div>
                        <label for="emaildokter-edit" class="col-3 col-form-label">Email</label>
                        <div class="col-9">
                            <input id="emaildokter-edit" name="emaildokter-edit" class="form-control" type="text"
                                autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="container">
                    <div class="row">
                        <div class="col-6 px-0">
                            <button id="deleteDoctor" class="btn btn-danger">Delete</button>
                        </div>
                        <div class="col-6 px-0 text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" id="updateDoctor" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
