<form novalidate>
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
        <h5 class="text-center">Pembayaran</h5>
        <hr class="pt-1 mt-2 mb-3">
    </div>

    <div class="row bg-light">
        <div class="col-8">
            <div class="container">
                <h6 class="text-center">Pemeriksaan</h6>
                <hr>
                <div class="row g-1">
                    <div class="col-12">
                        <div id="data-table">
                            <table class="table table-hover table-bordered">
                                <thead class="table-secondary">
                                    <tr>
                                        <th scope="col" style="width: 10%">Kode</th>
                                        <th scope="col" style="width: 35%">Nama Pemeriksaan</th>
                                        <th scope="col" style="width: 15%">Disc.</th>
                                        <th scope="col" style="width: 25%">Bruto</th>
                                        <th scope="col" style="width: 25%">Netto</th>
                                    </tr>
                                </thead>
                                <tbody id="testTableFixed" class="table-secondary">
                                    <tr>
                                        <td class="text-center" colspan="5">Loading...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <div id="data-table">
                            <table class="table table-hover table-bordered text-center">
                                <thead class="table-secondary">
                                    <tr>
                                        <th scope="col" colspan="2">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="testTablePriceFixed" class="table-secondary">
                                    <tr>
                                        <td scope="col" style="width: 50%"><b>Bruto</b></td>
                                        <td scope="col" style="width: 50%"><b>Netto</b></td>
                                    </tr>
                                    <tr>
                                        <td>Calculating...</td>
                                        <td>Calculating...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
        </div>

        <div class="col-4">
            <div id="payingSummary" class="container">
                <h6 class="text-center">Pembayaran</h6>
                <hr>
                <div class="row g-2 justify-content-between">
                    <label class="col-6 col-form-label">Metode Pembayaran</label>
                    <div class="col-6">
                        <select class="form-select" disabled>
                            <option {{ $test_data->metode_bayar == 'Cash' ? 'selected' : '' }}>Cash</option>
                            <option {{ $test_data->metode_bayar == 'BPJS' ? 'selected' : '' }}>BPJS</option>
                        </select>
                    </div>

                    <label class="col-5 col-form-label">Tagihan <small class="text-muted">(Rp. )</small></label>
                    <div class="col-7">
                        <input type="text" id="tagihan" class="form-control text-end" readonly>
                    </div>

                    <label for="bayar" class="col-5 col-form-label">Bayar <small class="text-muted">(Rp.
                            )</small></label>
                    <div class="col-7">
                        <input type="text" id="bayar" class="form-control text-end" name="bayar"
                            onkeypress="return onlyNumInput(event)" placeholder="0"
                            {{ $test_data->metode_bayar == 'BPJS' ? 'readonly' : '' }}>
                    </div>

                    <label class="col-5 col-form-label">Kekurangan <small class="text-muted">(Rp. )</small></label>
                    <div class="col-7">
                        <input type="text" id="kekurangan" class="form-control text-end" readonly>
                    </div>

                    <div class="btn btn-secondary col-5 mt-4">
                        Diskon Global
                    </div>
                    <div class="btn btn-secondary col-5 mt-4">
                        Pengembalian
                    </div>
                    <div id="bayarorder" class="btn btn-success col-12 disabled" data-bs-toggle="modal"
                        data-bs-target="#modalBayar" data-test-status="{{ $test_data->is_done }}">
                        Bayar
                    </div>
                    {{-- <div class="btn btn-success col-12">
                        Ok
                    </div> --}}
                </div>
                <hr>
            </div>
        </div>


        <div class="row">
            <div class="col-12">
                <h6 class="text-center mt-4">Print</h6>
                <hr>
                <div class="row g-1 px-3">
                    <div class="col-12 text-center">
                        <div class="btn btn-outline-secondary">
                            Barcode
                        </div>
                        <div class="btn btn-outline-secondary">
                            Label
                        </div>
                        <div class="btn btn-outline-secondary">
                            Kwitansi
                        </div>
                        <div class="btn btn-outline-secondary">
                            Print Nota
                        </div>
                        <div class="btn btn-outline-secondary">
                            Informed Consel
                        </div>
                    </div>
                </div>
                <hr>
            </div>
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
            {{-- <button type="submit" class="btn btn-success btn-order-save">
                Save Data
            </button> --}}
        </div>
        <div class="col-3 text-end">
            <div class="btn btn-success btn-order-next disabled">
                Done
            </div>
        </div>
    </div>
</form>

<div class="modal fade" id="modalBayar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <h4 class="modal-title text-center pb-3">Confirmation</h4>
                        <hr>
                        <div class="col-12 px-0 text-center">
                            <form action="/pendaftaran/{{ $test_data->no_lab }}/order" method="post">
                                @method('put')
                                @csrf
                                <input class="d-none" name="is_confirm" hidden value="ok">
                                <button type="button" id="cancel-doc-btn" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success">Confirm</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
