<!-- Navbar -->
<nav class="navbar navbar-dark bg-dark">
    <div class="container">

        <!-- Top Navbar -->
        <a class="navbar-brand" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button"
            aria-controls="offcanvasExample">
            <img src="{{ asset('assets/logo_app.png') }}" class="d-inline-block" alt="Logo Aplikasi">
            E - LABs.
        </a>

        <span class="navbar-text">
            <b>{{ $title }}</b>
        </span>

        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="#">
                    Chemical Analyst
                    <img src="{{ asset('assets/def_profile_pict.jpg') }}" class="rounded-circle" alt="Logo Aplikasi">
                </a>
            </li>
        </ul>
        <!-- End of Top Navbar -->

        <!-- Side Navbar -->
        <div class="offcanvas bg-dark offcanvas-start" tabindex="-1" id="offcanvasExample"
            aria-labelledby="offcanvasExampleLabel" <div class="offcanvas-header">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasExampleLabel">
                    <img src="{{ asset('assets/logo_app.png') }}" class="d-inline-block me-3" alt="Logo Aplikasi">
                </h5>
                <div class="me-auto">E - LABs.</div>

                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link {{ $title === 'Home' ? 'active' : '' }}" aria-current="page" href="/">
                            <img src="{{ asset('assets/home.png') }}" class="rounded-circle me-2" alt="Logo Aplikasi">
                        </a>
                    </li>
                </ul>

                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>

            <div class="offcanvas-body">

                <hr>
                <ul class="navbar-nav flex-grow-1 pe-3">
                    <li class="nav-item">
                        <a class="nav-link {{ $title === 'Pendaftaran' ? 'active' : '' }}" href="/pendaftaran">
                            <img src="{{ asset('assets/def_profile_pict.jpg') }}" class="rounded-circle me-2"
                                alt="Logo Aplikasi">
                            Pendaftaran
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <img src="{{ asset('assets/def_profile_pict.jpg') }}" class="rounded-circle me-2"
                                alt="Logo Aplikasi">
                            Ambil Sampel
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <img src="{{ asset('assets/def_profile_pict.jpg') }}" class="rounded-circle me-2"
                                alt="Logo Aplikasi">
                            Terima Sampel
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <img src="{{ asset('assets/def_profile_pict.jpg') }}" class="rounded-circle me-2"
                                alt="Logo Aplikasi">
                            Proses Sampel
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <img src="{{ asset('assets/def_profile_pict.jpg') }}" class="rounded-circle me-2"
                                alt="Logo Aplikasi">
                            Input Hasil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <img src="{{ asset('assets/def_profile_pict.jpg') }}" class="rounded-circle me-2"
                                alt="Logo Aplikasi">
                            Validasi Hasil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <img src="{{ asset('assets/def_profile_pict.jpg') }}" class="rounded-circle me-2"
                                alt="Logo Aplikasi">
                            Cetak Hasil
                        </a>
                    </li>
                </ul>

                <hr>
                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <img src="{{ asset('assets/def_profile_pict.jpg') }}" class="rounded-circle me-2"
                                alt="Logo Aplikasi">
                            Master
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <img src="{{ asset('assets/def_profile_pict.jpg') }}" class="rounded-circle me-2"
                                alt="Logo Aplikasi">
                            Utility
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <img src="{{ asset('assets/def_profile_pict.jpg') }}" class="rounded-circle me-2"
                                alt="Logo Aplikasi">
                            Report
                        </a>
                    </li>
                </ul>

                <hr>
                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <img src="{{ asset('assets/def_profile_pict.jpg') }}" class="rounded-circle me-2"
                                alt="Logo Aplikasi">
                            Settings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <img src="{{ asset('assets/def_profile_pict.jpg') }}" class="rounded-circle me-2"
                                alt="Logo Aplikasi">
                            Log-out
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- End of Side Navbar -->

    </div>
</nav>
<!-- End of Navbar -->



@if ($title === 'Pendaftaran')
    <!-- Sub Navbar -->

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sub-nav">
        <div class="container">
            <div class="navbar-nav">
                <div class="vr text-light"></div>
                <a class="nav-link have-page {{ $sub_title === 'semua-pemeriksaan' ? 'active' : '' }}"
                    href="/pendaftaran">Semua Pemeriksaan</a>
                <div class="vr text-light"></div>
                <a class="nav-link have-page {{ $sub_title === 'order-pemeriksaan' ? 'active' : '' }}"
                    href="/pendaftaran/order">Order
                    Pemeriksaan</a>
                <div class="vr text-light"></div>
                <a class="nav-link have-page disabled {{ $sub_title === 'ubah-pemeriksaan' ? 'active' : '' }}"
                    href="" tabindex="-1" aria-disabled="true">Ubah</a>
                <div class="vr text-light"></div>
            </div>
            <div class="btn btn-show-nav btn-light d-none">Show</div>
        </div>
    </nav>

    @if ($sub_title === 'semua-pemeriksaan')
        <div class="container-fluid hideable">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark sub-nav">
                <div class="container">
                    <div class="navbar-nav">
                        <div class="vr text-light"></div>
                        <form class="d-flex ms-2 me-2">
                            <input name="search" class="form-control search-field" type="text"
                                placeholder="Search by No. RM or Name" aria-label="Search">
                            <div for="search" class="btn btn-reset btn-secondary ms-1">Reset</div>
                        </form>
                        <div class="vr text-light"></div>
                        <span class="navbar-text text-secondary ms-2">
                            Sort by :
                        </span>
                        <ul class="navbar-nav ms-1 me-2">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle btn-dropdown-sort" href="" id="btn-dropdown-sort"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Latest
                                </a>
                                <ul class="dropdown-menu bg-dark" aria-labelledby="btn-dropdown-sort">
                                    <div class="btn btn-refresh btn-outline-light d-block m-1" id="btn-sort"
                                        value='DESC' tabindex="-1">Latest</div>
                                    <div class="btn btn-refresh btn-outline-light d-block m-1" id="btn-sort"
                                        value='ASC'>Oldest</div>
                                </ul>
                            </li>
                        </ul>
                        <div class="vr text-light"></div>
                        <span class="navbar-text text-secondary ms-2 me-1">
                            Filter :
                        </span>
                        <div class="btn btn-refresh btn-outline-light" data-bs-toggle="modal"
                            data-bs-target="#filterModal">
                            Add</div>
                        <div class="btn btn-clear-filter btn-secondary ms-1 me-2 disabled">Clear</div>
                        <div class="vr text-light"></div>
                        <div class="btn btn-refresh btn-success ms-2 me-2">Refresh</div>
                        <div class="vr text-light"></div>
                    </div>
                    <div class="btn btn-hide-nav btn-light">Hide</div>
                </div>
            </nav>
        </div>

        <!-- Filter Modal -->
        <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Filter</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body modal-filter">
                        <div>Tanggal Pemeriksaan</div>
                        <hr class="m-0 mb-2">
                        @php
                            $tanggal = (int) Str::substr($current_date, 8, 2);
                            $bulan = (int) Str::substr($current_date, 5, 2);
                            $tahun = (int) Str::substr($current_date, 0, 4);
                        @endphp
                        <div class="d-inline-block">
                            <select class="form-select filter-datetime-d" disabled>
                                @if ($bulan <= 7)
                                    {{-- Jika Merupakan Bulan Juli dan yang sebelumnya --}}
                                    @if ($bulan % 2 == 0)
                                        {{-- Jika Merupakan Bulan Genap --}}
                                        @if ($bulan == 2)
                                            {{-- Jika Bulan Februari --}}
                                            @if ($tahun % 4 == 0)
                                                {{-- Tahun Kabisat --}}
                                                @for ($i = 1; $i <= 29; $i++)
                                                    @if ($tanggal == $i)
                                                        <option selected value="{{ $i }}">
                                                            {{ $i }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $i }}">{{ $i }}
                                                        </option>
                                                    @endif
                                                @endfor
                                            @else
                                                {{-- Bukan Tahun Kabisat --}}
                                                @for ($i = 1; $i <= 28; $i++)
                                                    @if ($tanggal == $i)
                                                        <option selected value="{{ $i }}">
                                                            {{ $i }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $i }}">{{ $i }}
                                                        </option>
                                                    @endif
                                                @endfor
                                            @endif
                                        @else
                                            {{-- Jika April dan Juni --}}
                                            @for ($i = 1; $i <= 30; $i++)
                                                @if ($tanggal == $i)
                                                    <option selected value="{{ $i }}">{{ $i }}
                                                    </option>
                                                @else
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endif
                                            @endfor
                                        @endif
                                    @else
                                        {{-- Jika Merupakan Bulan Ganjil --}}
                                        @for ($i = 1; $i <= 31; $i++)
                                            @if ($tanggal == $i)
                                                <option selected value="{{ $i }}">{{ $i }}
                                                </option>
                                            @else
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endif
                                        @endfor
                                    @endif
                                @else
                                    {{-- Jika Merupakan Bulan Agustus dan yang setelahnya --}}
                                    @if ($bulan % 2 == 0)
                                        {{-- Jika Merupakan Bulan Genap --}}
                                        @for ($i = 1; $i <= 31; $i++)
                                            @if ($tanggal == $i)
                                                <option selected value="{{ $i }}">{{ $i }}
                                                </option>
                                            @else
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endif
                                        @endfor
                                    @else
                                        {{-- Jika Merupakan Bulan Ganjil --}}
                                        @for ($i = 1; $i <= 30; $i++)
                                            @if ($tanggal == $i)
                                                <option selected value="{{ $i }}">{{ $i }}
                                                </option>
                                            @else
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endif
                                        @endfor
                                    @endif
                                @endif
                            </select>
                        </div>
                        <div class="d-inline-block">
                            <select class="form-select filter-datetime-m" disabled>
                                @for ($i = 1; $i <= 12; $i++)
                                    @if ($bulan == $i)
                                        <option selected value="{{ $i }}">{{ $i }}
                                        </option>
                                    @else
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endif
                                @endfor
                            </select>
                        </div>
                        <div class="d-inline-block">
                            <select class="form-select filter-datetime-y" disabled>
                                @for ($i = $tahun; $i >= 1990; $i--)
                                    @if ($tahun == $i)
                                        <option selected value="{{ $i }}">{{ $i }}
                                        </option>
                                    @else
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endif
                                @endfor
                            </select>
                        </div>
                        <input class="btn-check" type="checkbox" id="allTimeCheck" value="" autocomplete="off"
                            checked>
                        <label class="ms-2 btn btn-outline-primary align-top" for="allTimeCheck">All Time</label>

                        <p></p>

                        <div>Jenis Kelamin</div>
                        <hr class="m-0 mb-2">
                        <p>
                            <input class="btn-check" type="checkbox" id="gender-check-1" value="Laki-laki"
                                autocomplete="off">
                            <label class="btn btn-outline-primary" for="gender-check-1">Laki-Laki</label>

                            <input type="checkbox" class="btn-check" id="gender-check-2" value="Perempuan"
                                autocomplete="off">
                            <label class="btn btn-outline-primary" for="gender-check-2">Perempuan</label>
                            <span class="ms-2 text-danger"></span>
                        </p>

                        <div>Dokter Pengirim</div>
                        <hr class="m-0 mb-2">
                        <p>
                            <select class="form-select filter-doctor" aria-label="Default select">
                                <option value="" selected>All</option>
                                <option value="Dr. Muhammad Ravi Edho, S.Kom.">Dr. Muhammad Ravi Edho, S.Kom.</option>
                                <option value="Dr. Yusuf Wijaya, S.Kom.">Dr. Yusuf Wijaya, S.Kom.</option>
                                <option value="Dr. Iqbal Fariz, S.Kom.">Dr. Iqbal Fariz, S.Kom.</option>
                            </select>
                        </p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success btn-apply-filter"
                            data-bs-dismiss="modal">Apply</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Filter Modal -->
    @endif
    <!-- End of Sub Navbar -->
@endif
