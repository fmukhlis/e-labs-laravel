<form action="/pendaftaran/{{ $test_data->pasien->no_rm }}/pasien" method="post" enctype="multipart/form-data">
    @method('put')
    @csrf
    {{-- Row1 --}}
    <div class="row bg-light">
        <div class="container">
            <hr class="mt-3">
            <div class="row">
                <label for="nolab" class="col-1 col-form-label">No. Lab.</label>
                <div class="col-2">
                    <input type="text" class="form-control @error('nolab') is-invalid @enderror" id="nolab"
                        name="nolab" value="{{ old('nolab', $test_data->no_lab) }}" required readonly>
                    @error('nolab')
                        <div class="invalid-feedback">No. Lab tidak valid</div>
                    @enderror
                </div>
                <label for="norm" class="col-1 col-form-label">No. RM</label>
                <div class="col-3">
                    <input type="text" class="form-control @error('norm') is-invalid @enderror" id="norm"
                        name="norm" value="{{ old('norm', $test_data->pasien->no_rm) }}" required readonly>
                    @error('norm')
                        <div class="invalid-feedback">No. RM tidak valid</div>
                    @enderror
                </div>
                <label for="noktp" class="col-1 offset-1 col-form-label">No. KTP</label>
                <div class="col-3">
                    <input type="text" class="form-control @error('noktp') is-invalid @enderror" id="noktp"
                        name="noktp" value="{{ old('noktp', $test_data->pasien->no_ktp) }}" required>
                    <div class="valid-feedback"></div>
                    @error('noktp')
                        <div class="invalid-feedback">No. KTP tidak valid</div>
                    @enderror
                </div>
            </div>
            <hr class="mt-3">
        </div>
    </div>
    {{-- EndRow1 --}}


    {{-- Row2 --}}
    <div class="row bg-light pb-1 justify-content-center">
        <h5 class="text-center">Data Pasien</h5>
        <hr class="pt-1 mt-2 mb-2">
    </div>

    <div class="row bg-light mt-2">
        {{-- --- --}}
        <div class="col-6">
            <div class="container">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="namapasien" class="form-label">Nama Pasien</label>
                        <input type="text" class="form-control @error('namapasien') is-invalid @enderror"
                            id="namapasien" name="namapasien" value="{{ old('namapasien', $test_data->pasien->nama) }}"
                            required>
                        @error('namapasien')
                            <div class="invalid-feedback">Nama Pasien harus diisi</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="tempatlahir" class="form-label">Tempat / Tanggal Lahir</label>
                        <div class="row gx-2">
                            <div class="col-5">
                                <input type="text" class="form-control @error('tempatlahir') is-invalid @enderror"
                                    id="tempatlahir" name="tempatlahir"
                                    value="{{ old('tempatlahir', $test_data->pasien->tempat_lahir) }}" required>
                                @error('tempatlahir')
                                    <div class="invalid-feedback">Tempat / Tanggal Lahir harus diisi</div>
                                @enderror
                            </div>
                            <div class="col-2">
                                <select class="form-select" id="tanggallahir" name="tanggallahir">
                                    @if ($bulan <= 7)
                                        {{-- Jika Merupakan Bulan Juli dan yang sebelumnya --}}
                                        @if ($bulan % 2 == 0)
                                            {{-- Jika Merupakan Bulan Genap --}}
                                            @if ($bulan == 2)
                                                {{-- Jika Bulan Februari --}}
                                                @if ($tahun % 4 == 0)
                                                    {{-- Tahun Kabisat --}}
                                                    @for ($i = 1; $i <= 29; $i++)
                                                        <option
                                                            {{ old('tanggallahir', Str::substr($test_data->pasien->tanggal_lahir, 8, 2)) == $i ? 'selected' : '' }}
                                                            value="{{ $i }}">
                                                            {{ $i }}
                                                        </option>
                                                    @endfor
                                                @else
                                                    {{-- Bukan Tahun Kabisat --}}
                                                    @for ($i = 1; $i <= 28; $i++)
                                                        <option
                                                            {{ old('tanggallahir', Str::substr($test_data->pasien->tanggal_lahir, 8, 2)) == $i ? 'selected' : '' }}
                                                            value="{{ $i }}">
                                                            {{ $i }}
                                                        </option>
                                                    @endfor
                                                @endif
                                            @else
                                                {{-- Jika April dan Juni --}}
                                                @for ($i = 1; $i <= 30; $i++)
                                                    <option
                                                        {{ old('tanggallahir', Str::substr($test_data->pasien->tanggal_lahir, 8, 2)) == $i ? 'selected' : '' }}
                                                        value="{{ $i }}">
                                                        {{ $i }}
                                                    </option>
                                                @endfor
                                            @endif
                                        @else
                                            {{-- Jika Merupakan Bulan Ganjil --}}
                                            @for ($i = 1; $i <= 31; $i++)
                                                <option
                                                    {{ old('tanggallahir', Str::substr($test_data->pasien->tanggal_lahir, 8, 2)) == $i ? 'selected' : '' }}
                                                    value="{{ $i }}">
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        @endif
                                    @else
                                        {{-- Jika Merupakan Bulan Agustus dan yang setelahnya --}}
                                        @if ($bulan % 2 == 0)
                                            {{-- Jika Merupakan Bulan Genap --}}
                                            @for ($i = 1; $i <= 31; $i++)
                                                <option
                                                    {{ old('tanggallahir', Str::substr($test_data->pasien->tanggal_lahir, 8, 2)) == $i ? 'selected' : '' }}
                                                    value="{{ $i }}">
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        @else
                                            {{-- Jika Merupakan Bulan Ganjil --}}
                                            @for ($i = 1; $i <= 30; $i++)
                                                <option
                                                    {{ old('tanggallahir', Str::substr($test_data->pasien->tanggal_lahir, 8, 2)) == $i ? 'selected' : '' }}
                                                    value="{{ $i }}">
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        @endif
                                    @endif
                                </select>
                            </div>
                            <div class="col-2">
                                <select class="form-select" id="bulanlahir" name="bulanlahir">
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option
                                            {{ old('bulanlahir', Str::substr($test_data->pasien->tanggal_lahir, 5, 2)) == $i ? 'selected' : '' }}
                                            value="{{ $i }}">{{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-3">
                                <select class="form-select" id="tahunlahir" name="tahunlahir">
                                    @for ($i = $tahun; $i >= 1990; $i--)
                                        <option
                                            {{ old('tahunlahir', Str::substr($test_data->pasien->tanggal_lahir, 0, 4)) == $i ? 'selected' : '' }}
                                            value="{{ $i }}">{{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-inline-block me-3">Jenis Kelamin</div>
                        <input type="radio" class="btn-check" id="jk-lk" value="Laki-laki" name="jeniskelamin"
                            {{ old('jeniskelamin', $test_data->pasien->jenis_kelamin) === 'Laki-laki' ? 'checked' : '' }}
                            required>
                        <label class="btn btn-outline-primary me-1" for="jk-lk">Laki-Laki</label>
                        <input type="radio" class="btn-check" id="jk-pr" value="Perempuan" name="jeniskelamin"
                            {{ old('jeniskelamin', $test_data->pasien->jenis_kelamin) === 'Perempuan' ? 'checked' : '' }}
                            required>
                        <label class="btn btn-outline-primary" for="jk-pr">Perempuan</label>
                        @error('jeniskelamin')
                            <small class="ms-1 text-danger">
                                Silakan pilih jenis kelamin
                            </small>
                        @enderror
                    </div>
                    <div class="col-5">
                        <label for="agama" class="form-label">Agama</label>
                        <input type="text" class="form-control" id="agama" name="agama"
                            value="{{ old('agama', $test_data->pasien->agama) }}">
                    </div>
                    <div class="offset-2 col-5">
                        <label for="status" class="form-label">Status</label>
                        <input type="text" class="form-control" id="status" name="status"
                            value="{{ old('status', $test_data->pasien->status) }}">
                    </div>
                    <div class="col-5">
                        <label for="pendidikanterakhir" class="form-label">Pendidikan Terakhir</label>
                        <input type="text" class="form-control" id="pendidikanterakhir" name="pendidikanterakhir"
                            value="{{ old('pendidikanterakhir', $test_data->pasien->pendidikan) }}">
                    </div>
                    <div class="offset-2 col-5">
                        <label for="pekerjaan" class="form-label">Pekerjaan</label>
                        <input type="text" class="form-control" id="pekerjaan" name="pekerjaan"
                            value="{{ old('pekerjaan', $test_data->pasien->pekerjaan) }}">
                    </div>
                    <div class="col-12">
                        <label for="namaibupasien" class="form-label">Nama Ibu Pasien</label>
                        <input type="text" class="form-control" id="namaibupasien" name="namaibupasien"
                            value="{{ old('namaibupasien', $test_data->pasien->nama_ibu) }}">
                    </div>
                </div>
            </div>
        </div>
        {{-- --- --}}



        {{-- --- --}}
        <div class="col-6">
            <div class="container">
                <div class="row g-3">
                    <div class="col-4">
                        <div class="row">
                            <div class="col-12 pt-4">
                                @if ($test_data->pasien->foto_pasien)
                                    <img src="{{ asset('storage/' . $test_data->pasien->foto_pasien) }}"
                                        id="fotothumbnailpasien" class="img-thumbnail" alt="Foto Pasien">
                                @else
                                    <img src="{{ asset('assets/def_profile_pict.jpg') }}" id="fotothumbnailpasien"
                                        class="img-thumbnail" alt="Foto Pasien">
                                @endif
                            </div>
                            <div class="col-12">
                                <div class="btn btn-primary btn-order-photo col-12">
                                    Change Photo
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-7 offset-1 gap-registrasi">
                        <div class="row g-2">
                            <div class="col-12">
                                <label for="pangkatgolongan" class="form-label">Pangkat / Golongan</label>
                                <input type="text" class="form-control" id="pangkatgolongan"
                                    name="pangkatgolongan"
                                    value="{{ old('pangkatgolongan', $test_data->pasien->pangkat_gol) }}">
                            </div>
                            <div class="col-12">
                                <label for="kesatuan" class="form-label">Kesatuan</label>
                                <input type="text" class="form-control" id="kesatuan" name="kesatuan"
                                    value="{{ old('kesatuan', $test_data->pasien->kesatuan) }}">
                            </div>
                            <div class="col-12">
                                <label for="nrp" class="form-label">NRP</label>
                                <input type="text" class="form-control" id="nrp" name="nrp"
                                    value="{{ old('nrp', $test_data->pasien->nrp) }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        @php
                            $curl = curl_init();
                            curl_setopt_array($curl, [
                                CURLOPT_URL => 'https://api.countrystatecity.in/v1/countries',
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_HTTPHEADER => ['X-CSCAPI-KEY: ZHlNZTd0bDdvSXVGSzI2WXVxbVN4SWFGR1NGZm1lZ1RmaFJ0VElUcA=='],
                            ]);
                            $response = curl_exec($curl);
                            curl_close($curl);
                            $countries = json_decode($response, true);
                        @endphp
                        <label for="alamatpasien" class="form-label">Alamat Pasien</label>
                        <div class="row g-2">
                            <div class="col-4">
                                <select class="form-select" id="negara" name="negara">
                                    <option {{ old('negara', $test_data->pasien->negara) === 'ID' ? 'selected' : '' }}
                                        value="ID">Indonesia</option>
                                    @foreach ($countries as $country)
                                        @if ($country['iso2'] == 'ID')
                                            @continue;
                                        @endif
                                        <option
                                            {{ old('negara', $test_data->pasien->negara) === $country['iso2'] ? 'selected' : '' }}
                                            value="{{ $country['iso2'] }}">{{ $country['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4">
                                <select class="form-select" id="provinsi" name="provinsi"
                                    {{ old('negara', $test_data->pasien->negara) !== 'ID' ? 'disabled' : '' }}>
                                    <option {{ old('provinsi', $test_data->pasien->prov) === '' ? 'selected' : '' }}
                                        value="">Provinsi</option>
                                    @if (old('negara', $test_data->pasien->negara) === 'ID')
                                        @php
                                            $provinces = DB::table('t_provinsi')->get();
                                        @endphp
                                        @foreach ($provinces as $province)
                                            <option
                                                {{ old('provinsi', $test_data->pasien->prov) === $province->id ? 'selected' : '' }}
                                                value="{{ $province->id }}">
                                                {{ ucwords(strtolower($province->nama)) }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-4">
                                <select class="form-select" id="kabkota" name="kabkota"
                                    {{ old('negara', $test_data->pasien->negara) !== 'ID' ? 'disabled' : '' }}>
                                    <option {{ old('kabkota', $test_data->pasien->kab_kota) === '' ? 'selected' : '' }}
                                        value="">Kab/Kota
                                    </option>
                                    @if (old('provinsi', $test_data->pasien->prov))
                                        @php
                                            $idProvinsi = $test_data->pasien->prov;
                                            if (old('provinsi')) {
                                                $idProvinsi = old('provinsi');
                                            }
                                            $cities = DB::table('t_kota')
                                                ->where('id', 'LIKE', $idProvinsi . '%')
                                                ->get();
                                        @endphp
                                        @foreach ($cities as $city)
                                            <option
                                                {{ old('kabkota', $test_data->pasien->kab_kota) === $city->id ? 'selected' : '' }}
                                                value="{{ $city->id }}">
                                                {{ ucwords(strtolower($city->nama)) }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-4">
                                <select class="form-select" id="kecamatan" name="kecamatan"
                                    {{ old('negara', $test_data->pasien->negara) !== 'ID' ? 'disabled' : '' }}>
                                    <option
                                        {{ old('kecamatan', $test_data->pasien->kecamatan) === '' ? 'selected' : '' }}
                                        value="">Kecamatan
                                    </option>
                                    @if (old('kabkota', $test_data->pasien->kab_kota))
                                        @php
                                            $idKota = $test_data->pasien->kab_kota;
                                            if (old('kabkota')) {
                                                $idProvinsi = old('kabkota');
                                            }
                                            $districts = DB::table('t_kecamatan')
                                                ->where('id', 'LIKE', $idKota . '%')
                                                ->get();
                                        @endphp
                                        @foreach ($districts as $district)
                                            <option
                                                {{ old('kecamatan', $test_data->pasien->kecamatan) === $district->id ? 'selected' : '' }}
                                                value="{{ $district->id }}">
                                                {{ ucwords(strtolower($district->nama)) }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-4">
                                <select class="form-select" id="desa" name="desa"
                                    {{ old('negara', $test_data->pasien->negara) !== 'ID' ? 'disabled' : '' }}>
                                    <option {{ old('desa', $test_data->pasien->desa) === '' ? 'selected' : '' }}
                                        value="">
                                        Desa</option>
                                    @if (old('kecamatan', $test_data->pasien->kecamatan))
                                        @php
                                            $idKecamatan = $test_data->pasien->kecamatan;
                                            if (old('kecamatan')) {
                                                $idProvinsi = old('kecamatan');
                                            }
                                            $villages = DB::table('t_kelurahan')
                                                ->where('id', 'LIKE', $idKecamatan . '%')
                                                ->get();
                                        @endphp
                                        @foreach ($villages as $village)
                                            <option
                                                {{ old('desa', $test_data->pasien->desa) === $village->id ? 'selected' : '' }}
                                                value="{{ $village->id }}">
                                                {{ ucwords(strtolower($village->nama)) }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-4">
                                <input type="text" class="form-control" id="kodepos" name="kodepos"
                                    {{ old('negara', $test_data->pasien->negara) !== 'ID' ? 'disabled' : '' }}
                                    value="{{ old('kodepos', $test_data->pasien->kode_pos) }}"
                                    placeholder="Kode Pos">
                            </div>
                            <div class="col-12">
                                <textarea class="form-control @error('detailalamat') is-invalid @enderror" id="detailalamat" name="detailalamat"
                                    rows="3" placeholder="Detail Alamat" required>{{ old('detailalamat', $test_data->pasien->alamat_detail) }}</textarea>
                                <div class="invalid-feedback">Alamat harus diisi</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- --- --}}

        {{-- --- --}}
        <div>
            <div class="col-12 mt-4 gap-registrasi">
                <div class="container">
                    <div class="row g-2">
                        <div class="col-3">
                            <input type="text" class="form-control" id="nohp" name="nohp"
                                value="{{ old('nohp', $test_data->pasien->no_hp) }}" placeholder="No. HP">
                        </div>
                        <div class="col-3">
                            <input type="text" class="form-control" id="notelp" name="notelp"
                                value="{{ old('notelp', $test_data->pasien->no_telp) }}" placeholder="No. Telp.">
                        </div>
                        <div class="col-3">
                            <input type="text" class="form-control" id="fax" name="fax"
                                value="{{ old('fax', $test_data->pasien->fax) }}" placeholder="Fax">
                        </div>
                        <div class="col-3">
                            <input type="text" class="form-control" id="email" name="email"
                                value="{{ old('email', $test_data->pasien->email) }}" placeholder="Email">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- --- --}}
    </div>

    <input id="fotopasien" name="fotopasien" type="file" class="d-none">

    <div class="row bg-light pt-2 pb-1 justify-content-center">
        <hr class="pt-1">
    </div>

    <div id="content-controller" class="row bg-light pt-2 pb-4 gx-0 justify-content-between">
        <div class="col-3 text-start">
        </div>
        <div class="col-6 text-center">
            <button id="btn-order-save" type="submit" class="btn btn-success col-3">
                Update
            </button>
        </div>
        <div class="col-3 text-end">
            <div class="btn btn-primary btn-order-next">
                Next
            </div>
        </div>
    </div>
</form>
