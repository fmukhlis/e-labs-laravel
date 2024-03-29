<form action="/pendaftaran/order" method="post" enctype="multipart/form-data">
    @csrf
    {{-- Row1 --}}
    <div class="row bg-light">
        <div class="container">
            <hr class="mt-3">
            <div class="row">
                <label for="nolab" class="col-1 col-form-label">No. Lab.</label>
                <div class="col-2">
                    @if ($latest_test)
                        @if ($tanggal . $bulan . Str::substr($tahun, 2, 4) == Str::substr($latest_test->no_lab, 0, 6))
                            @php
                                $nolab = Str::substr($latest_test->no_lab, 6, 10) + 1;
                            @endphp
                            <input type="text" class="form-control @error('nolab') is-invalid @enderror"
                                id="nolab" name="nolab"
                                value="{{ old('nolab', Str::substr($latest_test->no_lab, 0, 6) . $nolab) }}" required
                                readonly>
                        @else
                            <input type="text" class="form-control @error('nolab') is-invalid @enderror"
                                id="nolab" name="nolab"
                                value="{{ old('nolab', $tanggal . $bulan . Str::substr($tahun, 2, 4) . '10001') }}"
                                required readonly>
                        @endif
                    @else
                        <input type="text" class="form-control @error('nolab') is-invalid @enderror" id="nolab"
                            name="nolab"
                            value="{{ old('nolab', $tanggal . $bulan . Str::substr($tahun, 2, 4) . '10001') }}" required
                            readonly>
                    @endif
                    @error('nolab')
                        <div class="invalid-feedback">No. Lab tidak valid</div>
                    @enderror
                </div>
                <label for="norm" class="col-1 col-form-label">No. RM</label>
                <div class="col-3">
                    @if (session('selected_patient') == null)
                        @if ($latest_patient)
                            <input type="text" class="form-control @error('norm') is-invalid @enderror"
                                id="norm" name="norm" value="{{ old('norm', $latest_patient->no_rm + 1) }}"
                                required readonly>
                        @else
                            <input type="text" class="form-control @error('norm') is-invalid @enderror"
                                id="norm" name="norm" value="{{ old('norm') }}" required>
                        @endif
                    @else
                        <input type="text" class="form-control @error('norm') is-invalid @enderror" id="norm"
                            name="norm"
                            value="{{ old('norm', session('selected_patient') ? session('selected_patient')['no_rm'] : '') }}"
                            required readonly>
                    @endif
                    @error('norm')
                        <div class="invalid-feedback">No. RM tidak valid</div>
                    @enderror
                </div>
                <label for="noktp" class="col-1 offset-1 col-form-label">No. KTP</label>
                <div class="col-3">
                    <input type="text" class="form-control @error('noktp') is-invalid @enderror" id="noktp"
                        name="noktp"
                        value="{{ old('noktp', session('selected_patient') == null ? '' : session('selected_patient')['no_ktp']) }}"
                        required>
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
        <h5 class="text-center">Registrasi</h5>
        <hr class="pt-1 mt-2 mb-2">
    </div>

    {{-- Row3 --}}
    <div class="row bg-light mt-2">
        {{-- --- --}}
        <div class="col-6">
            <div class="container">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="namapasien" class="form-label">Nama Pasien</label>
                        <input type="text" class="form-control @error('namapasien') is-invalid @enderror"
                            id="namapasien" name="namapasien"
                            value="{{ old('namapasien', session('selected_patient') == null ? '' : session('selected_patient')['nama']) }}"
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
                                    value="{{ old('tempatlahir', session('selected_patient') == null ? '' : session('selected_patient')['tempat_lahir']) }}"
                                    required>
                                @error('tempatlahir')
                                    <div class="invalid-feedback">Tempat / Tanggal Lahir harus diisi</div>
                                @enderror
                            </div>
                            @if (session('selected_patient') == null)
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
                                                                {{ old('tanggallahir', $tanggal) == $i ? 'selected' : '' }}
                                                                value="{{ $i }}">
                                                                {{ $i }}
                                                            </option>
                                                        @endfor
                                                    @else
                                                        {{-- Bukan Tahun Kabisat --}}
                                                        @for ($i = 1; $i <= 28; $i++)
                                                            <option
                                                                {{ old('tanggallahir', $tanggal) == $i ? 'selected' : '' }}
                                                                value="{{ $i }}">
                                                                {{ $i }}
                                                            </option>
                                                        @endfor
                                                    @endif
                                                @else
                                                    {{-- Jika April dan Juni --}}
                                                    @for ($i = 1; $i <= 30; $i++)
                                                        <option
                                                            {{ old('tanggallahir', $tanggal) == $i ? 'selected' : '' }}
                                                            value="{{ $i }}">
                                                            {{ $i }}
                                                        </option>
                                                    @endfor
                                                @endif
                                            @else
                                                {{-- Jika Merupakan Bulan Ganjil --}}
                                                @for ($i = 1; $i <= 31; $i++)
                                                    <option {{ old('tanggallahir', $tanggal) == $i ? 'selected' : '' }}
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
                                                    <option {{ old('tanggallahir', $tanggal) == $i ? 'selected' : '' }}
                                                        value="{{ $i }}">
                                                        {{ $i }}
                                                    </option>
                                                @endfor
                                            @else
                                                {{-- Jika Merupakan Bulan Ganjil --}}
                                                @for ($i = 1; $i <= 30; $i++)
                                                    <option {{ old('tanggallahir', $tanggal) == $i ? 'selected' : '' }}
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
                                            <option {{ old('bulanlahir', $bulan) == $i ? 'selected' : '' }}
                                                value="{{ $i }}">{{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-3">
                                    <select class="form-select" id="tahunlahir" name="tahunlahir">
                                        @for ($i = $tahun; $i >= 1990; $i--)
                                            <option {{ old('tahunlahir', $tahun) == $i ? 'selected' : '' }}
                                                value="{{ $i }}">{{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                        </div>
                    </div>
                @else
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
                                                    {{ old('tanggallahir', Str::substr(session('selected_patient')['tanggal_lahir'], 8, 2)) == $i ? 'selected' : '' }}
                                                    value="{{ $i }}">
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        @else
                                            {{-- Bukan Tahun Kabisat --}}
                                            @for ($i = 1; $i <= 28; $i++)
                                                <option
                                                    {{ old('tanggallahir', Str::substr(session('selected_patient')['tanggal_lahir'], 8, 2)) == $i ? 'selected' : '' }}
                                                    value="{{ $i }}">
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        @endif
                                    @else
                                        {{-- Jika April dan Juni --}}
                                        @for ($i = 1; $i <= 30; $i++)
                                            <option
                                                {{ old('tanggallahir', Str::substr(session('selected_patient')['tanggal_lahir'], 8, 2)) == $i ? 'selected' : '' }}
                                                value="{{ $i }}">
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    @endif
                                @else
                                    {{-- Jika Merupakan Bulan Ganjil --}}
                                    @for ($i = 1; $i <= 31; $i++)
                                        <option
                                            {{ old('tanggallahir', Str::substr(session('selected_patient')['tanggal_lahir'], 8, 2)) == $i ? 'selected' : '' }}
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
                                            {{ old('tanggallahir', Str::substr(session('selected_patient')['tanggal_lahir'], 8, 2)) == $i ? 'selected' : '' }}
                                            value="{{ $i }}">
                                            {{ $i }}
                                        </option>
                                    @endfor
                                @else
                                    {{-- Jika Merupakan Bulan Ganjil --}}
                                    @for ($i = 1; $i <= 30; $i++)
                                        <option
                                            {{ old('tanggallahir', Str::substr(session('selected_patient')['tanggal_lahir'], 8, 2)) == $i ? 'selected' : '' }}
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
                                    {{ old('bulanlahir', Str::substr(session('selected_patient')['tanggal_lahir'], 5, 2)) == $i ? 'selected' : '' }}
                                    value="{{ $i }}">{{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-3">
                        <select class="form-select" id="tahunlahir" name="tahunlahir">
                            @for ($i = $tahun; $i >= 1990; $i--)
                                <option
                                    {{ old('tahunlahir', Str::substr(session('selected_patient')['tanggal_lahir'], 0, 4)) == $i ? 'selected' : '' }}
                                    value="{{ $i }}">{{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
            @endif
            <div class="col-12">
                <div class="d-inline-block me-3">Jenis Kelamin</div>
                <input type="radio" class="btn-check" id="jk-lk" value="Laki-laki" name="jeniskelamin"
                    {{ old('jeniskelamin', session('selected_patient') == null ? '' : session('selected_patient')['jenis_kelamin']) === 'Laki-laki' ? 'checked' : '' }}
                    required>
                <label class="btn btn-outline-primary me-1" for="jk-lk">Laki-Laki</label>
                <input type="radio" class="btn-check" id="jk-pr" value="Perempuan" name="jeniskelamin"
                    {{ old('jeniskelamin', session('selected_patient') == null ? '' : session('selected_patient')['jenis_kelamin']) === 'Perempuan' ? 'checked' : '' }}
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
                    value="{{ old('agama', session('selected_patient') == null ? '' : session('selected_patient')['agama']) }}">
            </div>
            <div class="offset-2 col-5">
                <label for="status" class="form-label">Status</label>
                <input type="text" class="form-control" id="status" name="status"
                    value="{{ old('status', session('selected_patient') == null ? '' : session('selected_patient')['status']) }}">
            </div>
            <div class="col-5">
                <label for="pendidikanterakhir" class="form-label">Pendidikan Terakhir</label>
                <input type="text" class="form-control" id="pendidikanterakhir" name="pendidikanterakhir"
                    value="{{ old('pendidikanterakhir', session('selected_patient') == null ? '' : session('selected_patient')['pendidikan']) }}">
            </div>
            <div class="offset-2 col-5">
                <label for="pekerjaan" class="form-label">Pekerjaan</label>
                <input type="text" class="form-control" id="pekerjaan" name="pekerjaan"
                    value="{{ old('pekerjaan', session('selected_patient') == null ? '' : session('selected_patient')['pekerjaan']) }}">
            </div>
            <div class="col-12">
                <label for="namaibupasien" class="form-label">Nama Ibu Pasien</label>
                <input type="text" class="form-control" id="namaibupasien" name="namaibupasien"
                    value="{{ old('namaibupasien', session('selected_patient') == null ? '' : session('selected_patient')['nama_ibu']) }}">
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
                            @if (session('selected_patient'))
                                @if (session('selected_patient')['foto_pasien'])
                                    <img src="{{ asset('storage/' . session('selected_patient')['foto_pasien']) }}"
                                        id="fotothumbnailpasien" class="img-thumbnail" alt="Foto Pasien">
                                @else
                                    <img src="{{ asset('assets/def_profile_pict.jpg') }}" id="fotothumbnailpasien"
                                        class="img-thumbnail" alt="Foto Pasien">
                                @endif
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
                            <input type="text" class="form-control" id="pangkatgolongan" name="pangkatgolongan"
                                value="{{ old('pangkatgolongan', session('selected_patient') == null ? '' : session('selected_patient')['pangkat_gol']) }}">
                        </div>
                        <div class="col-12">
                            <label for="kesatuan" class="form-label">Kesatuan</label>
                            <input type="text" class="form-control" id="kesatuan" name="kesatuan"
                                value="{{ old('kesatuan', session('selected_patient') == null ? '' : session('selected_patient')['kesatuan']) }}">
                        </div>
                        <div class="col-12">
                            <label for="nrp" class="form-label">NRP</label>
                            <input type="text" class="form-control" id="nrp" name="nrp"
                                value="{{ old('nrp', session('selected_patient') == null ? '' : session('selected_patient')['nrp']) }}">
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
                                <option
                                    {{ old('negara', session('selected_patient') == null ? 'ID' : session('selected_patient')['negara']) == 'ID' ? 'selected' : '' }}
                                    value="ID">
                                    Indonesia</option>
                                @foreach ($countries as $country)
                                    @if ($country['iso2'] == 'ID')
                                        @continue;
                                    @endif
                                    <option
                                        {{ old('negara', session('selected_patient') == null ? '' : session('selected_patient')['negara']) == $country['iso2'] ? 'selected' : '' }}
                                        value="{{ $country['iso2'] }}">{{ $country['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4">
                            <select class="form-select" id="provinsi" name="provinsi"
                                {{ old('negara', session('selected_patient') == null ? 'ID' : session('selected_patient')['negara']) != 'ID' ? 'disabled' : '' }}>
                                <option
                                    {{ old('provinsi', session('selected_patient') == null ? '' : session('selected_patient')['prov']) == '' ? 'selected' : '' }}
                                    value="">
                                    Provinsi
                                </option>
                                @if (old('negara', session('selected_patient') == null ? 'ID' : session('selected_patient')['negara']) == 'ID')
                                    @php
                                        $provinces = DB::table('t_provinsi')->get();
                                    @endphp
                                    @foreach ($provinces as $province)
                                        <option
                                            {{ old('provinsi', session('selected_patient') == null ? '' : session('selected_patient')['prov']) == $province->id ? 'selected' : '' }}
                                            value="{{ $province->id }}">
                                            {{ ucwords(strtolower($province->nama)) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-4">
                            <select class="form-select" id="kabkota" name="kabkota"
                                {{ old('negara', session('selected_patient') == null ? 'ID' : session('selected_patient')['negara']) != 'ID' ? 'disabled' : '' }}>
                                <option
                                    {{ old('kabkota', session('selected_patient') == null ? '' : session('selected_patient')['kab_kota']) === '' ? 'selected' : '' }}
                                    value="">Kab/Kota
                                </option>
                                @if (old('provinsi', session('selected_patient')))
                                    @php
                                        $idProvinsi = old('provinsi');
                                        if (session('selected_patient')) {
                                            $idProvinsi = session('selected_patient')['prov'];
                                        }
                                        $cities = DB::table('t_kota')
                                            ->where('id', 'LIKE', $idProvinsi . '%')
                                            ->get();
                                    @endphp
                                    @foreach ($cities as $city)
                                        <option
                                            {{ old('kabkota', session('selected_patient') == null ? '' : session('selected_patient')['kab_kota']) === $city->id ? 'selected' : '' }}
                                            value="{{ $city->id }}">
                                            {{ ucwords(strtolower($city->nama)) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-4">
                            <select class="form-select" id="kecamatan" name="kecamatan"
                                {{ old('negara', session('selected_patient') == null ? 'ID' : session('selected_patient')['negara']) != 'ID' ? 'disabled' : '' }}>
                                <option
                                    {{ old('kecamatan', session('selected_patient') == null ? '' : session('selected_patient')['kecamatan']) === '' ? 'selected' : '' }}
                                    value="">Kecamatan
                                </option>
                                @if (old('kabkota', session('selected_patient')))
                                    @php
                                        $idKota = old('kabkota');
                                        if (session('selected_patient')) {
                                            $idKota = session('selected_patient')['kab_kota'];
                                        }
                                        $districts = DB::table('t_kecamatan')
                                            ->where('id', 'LIKE', $idKota . '%')
                                            ->get();
                                    @endphp
                                    @foreach ($districts as $district)
                                        <option
                                            {{ old('kecamatan', session('selected_patient') == null ? '' : session('selected_patient')['kecamatan']) === $district->id ? 'selected' : '' }}
                                            value="{{ $district->id }}">
                                            {{ ucwords(strtolower($district->nama)) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-4">
                            <select class="form-select" id="desa" name="desa"
                                {{ old('negara', session('selected_patient') == null ? 'ID' : session('selected_patient')['negara']) != 'ID' ? 'disabled' : '' }}>
                                <option
                                    {{ old('desa', session('selected_patient') == null ? '' : session('selected_patient')['desa']) === '' ? 'selected' : '' }}
                                    value="">
                                    Desa</option>
                                @if (old('kecamatan', session('selected_patient')))
                                    @php
                                        $idKecamatan = old('kecamatan');
                                        if (session('selected_patient')) {
                                            $idKecamatan = session('selected_patient')['kecamatan'];
                                        }
                                        $villages = DB::table('t_kelurahan')
                                            ->where('id', 'LIKE', $idKecamatan . '%')
                                            ->get();
                                    @endphp
                                    @foreach ($villages as $village)
                                        <option
                                            {{ old('desa', session('selected_patient') == null ? '' : session('selected_patient')['desa']) === $village->id ? 'selected' : '' }}
                                            value="{{ $village->id }}">
                                            {{ ucwords(strtolower($village->nama)) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-4">
                            <input type="text" class="form-control" id="kodepos" name="kodepos"
                                value="{{ old('kodepos', session('selected_patient') == null ? '' : session('selected_patient')['kode_pos']) }}"
                                placeholder="Kode Pos">
                        </div>
                        <div class="col-12">
                            <textarea class="form-control @error('detailalamat') is-invalid @enderror" id="detailalamat" name="detailalamat"
                                rows="3" placeholder="Detail Alamat" required>{{ old('detailalamat', session('selected_patient') == null ? '' : session('selected_patient')['alamat_detail']) }}</textarea>
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
                            value="{{ old('nohp', session('selected_patient') == null ? '' : session('selected_patient')['no_hp']) }}"
                            placeholder="No. HP">
                    </div>
                    <div class="col-3">
                        <input type="text" class="form-control" id="notelp" name="notelp"
                            value="{{ old('notelp', session('selected_patient') == null ? '' : session('selected_patient')['no_telp']) }}"
                            placeholder="No. Telp.">
                    </div>
                    <div class="col-3">
                        <input type="text" class="form-control" id="fax" name="fax"
                            value="{{ old('fax', session('selected_patient') == null ? '' : session('selected_patient')['fax']) }}"
                            placeholder="Fax">
                    </div>
                    <div class="col-3">
                        <input type="text" class="form-control" id="email" name="email"
                            value="{{ old('email', session('selected_patient') == null ? '' : session('selected_patient')['email']) }}"
                            placeholder="Email">
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
        </div>
        <div class="col-3 text-end">
            <button id="btn-order-save" type="submit" class="btn btn-success">
                Add Data
            </button>
        </div>
    </div>
</form>
