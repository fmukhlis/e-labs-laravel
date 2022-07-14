<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Dokter;
use App\Models\KategoriPemeriksaan;
use App\Models\Pendaftaran;
use App\Models\Pasien;
use App\Models\Periksa;
use App\Models\Pemeriksaan;
use App\Models\Ruangan;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Mockery\Generator\StringManipulation\Pass\Pass;
use SebastianBergmann\Environment\Console;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PendaftaranController extends Controller
{
    public function index()
    {
        // $datas = Periksa::with('pasien')->with('dokter')->whereHas('dokter', function (Builder $query) use ($doctorFilt) {
        //     dd($query);
        // })->orderBy('created_at', 'ASC')->skip(0)->take(20)->get();
        return view('pendaftaran/pendaftaran-index', [
            "title" => "Pendaftaran",
            "sub_title" => "semua-pemeriksaan",
            "current_date" => Carbon::now()->toDateTimeString()
        ]);
    }

    public function orderNew()
    {
        return view('pendaftaran/pendaftaran-order-new', [
            "title" => "Pendaftaran",
            "sub_title" => "order-pemeriksaan",
            "current_date" => Carbon::now()->toDateTimeString(),
            "latest_test" => Periksa::orderBy('created_at', 'DESC')->first(),
            "latest_patient" => Pasien::orderBy('created_at', 'DESC')->first(),
        ]);
    }

    public function redOrder(Pasien $pasien)
    {
        return redirect('pendaftaran/order')->with('selected_patient', $pasien);
    }

    public function order(Periksa $periksa)
    {
        $testData = $periksa->load('pasien');
        return view('pendaftaran/pendaftaran-order', [
            "title" => "Pendaftaran",
            "sub_title" => "order-pemeriksaan",
            "current_date" => Carbon::now()->toDateTimeString(),
            "test_data" => $testData,
            "rooms" => DB::table('ruangans')->get(),
            "spesialisasi" => DB::table('spesialisasis')->get(),
            "test_cat" => KategoriPemeriksaan::all(),
            "patient_age" => Carbon::parse($testData->pasien->tanggal_lahir)->diff(Carbon::now())->format('%y tahun, %m bulan')
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nolab' => ['required', 'numeric', 'unique:periksas,no_lab'],
            'norm' => ['required', 'numeric'],
            'noktp' => ['required', 'numeric'],
            'namapasien' => ['required'],
            'tempatlahir' => ['required'],
            'jeniskelamin' => ['required'],
            'fotopasien' => ['image', 'file', 'max:1024'],
            'detailalamat' => ['required']
        ]);

        if (!Pasien::where('no_rm', '=', $validatedData['norm'])->get()->count()) {
            if ($request->file('fotopasien')) $validatedData['fotopasien'] = $request->file('fotopasien')->store('profilePhoto');
            else $validatedData['fotopasien'] = '';
            Pasien::create([
                'no_rm' => $validatedData['norm'],
                'no_ktp' => $validatedData['noktp'],
                'nama' => $validatedData['namapasien'],
                'jenis_kelamin' => $validatedData['jeniskelamin'],
                'alamat_detail' => $validatedData['detailalamat'],
                'tempat_lahir' => $validatedData['tempatlahir'],
                'foto_pasien' => $validatedData['fotopasien'],

                'tanggal_lahir' => $request->get('tahunlahir') . '-' . $request->get('bulanlahir') . '-' . $request->get('tanggallahir'),
                'agama' => $request->get('agama'),
                'status' => $request->get('status'),
                'pendidikan' => $request->get('pendidikanterakhir'),
                'pekerjaan' => $request->get('pekerjaan'),
                'nama_ibu' => $request->get('namaibupasien'),
                'negara' => $request->get('negara'),
                'prov' => $request->get('provinsi'),
                'kab_kota' => $request->get('kabkota'),
                'kecamatan' => $request->get('kecamatan'),
                'desa' => $request->get('desa'),
                'kode_pos' => $request->get('kodepos'),
                'pangkat_gol' => $request->get('pangkatgolongan'),
                'kesatuan' => $request->get('kesatuan'),
                'nrp' => $request->get('nrp'),
                'no_hp' => $request->get('nohp'),
                'no_telp' => $request->get('notelp'),
                'fax' => $request->get('fax'),
                'email' => $request->get('email')
            ]);
        }

        Periksa::create([
            'no_lab' => $validatedData['nolab'],
            'pasien_id' => Pasien::where('no_rm', $validatedData['norm'])->pluck('id')[0],
            'dokter_id' => 0,
            'home_service' => 0
        ]);

        return redirect('/pendaftaran/order')->with('status',  1)->with('namapasien',  $validatedData['namapasien'])->with('nolab', $validatedData['nolab']);
    }

    public function update(Request $request, Periksa $periksa)
    {
        if ($request->ajax()) {
            // Validation
            $rules = [
                'kode' => ['exists:dokters,kode']
            ];
            $validatedData = $request->validate($rules);
            // Add/Update Doctor Validated Data to 'periksas' table
            if ($request->get('isRemove') === 'true') {
                Periksa::where('id', '=', $periksa->id)->update([
                    'dokter_id' => 0
                ]);
            } else {
                Periksa::where('id', '=', $periksa->id)->update([
                    'dokter_id' => Dokter::where('kode', $validatedData['kode'])->pluck('id')[0]
                ]);
            }
            // Send random data back
            $datas = array(
                'random' => ''
            );
            echo json_encode($datas);
        } else {
            $rules = [
                'namapasien' => ['required'],
                'tempatlahir' => ['required'],
                'jeniskelamin' => ['required'],
                'fotopasien' => ['image', 'file', 'max:1024'],
                'detailalamat' => ['required']
            ];

            if ($request->get('nolab') != $periksa->no_lab) {
                $rules['nolab'] = 'required|numeric|unique:periksas,no_lab';
            } else {
                $rules['nolab'] = 'required|numeric';
            }
            if ($request->get('norm') != $periksa->pasien->no_rm) {
                $rules['norm'] = 'required|numeric|unique:pasiens,no_rm';
            } else {
                $rules['norm'] = 'required|numeric';
            }
            if ($request->get('noktp') != $periksa->pasien->no_ktp) {
                $rules['noktp'] = 'required|numeric|unique:pasiens,no_ktp';
            } else {
                $rules['noktp'] = 'required|numeric';
            }

            $validatedData = $request->validate($rules);

            if ($request->file('fotopasien')) {
                $currentPhoto = $periksa->pasien->foto_pasien;
                if ($currentPhoto) Storage::delete($currentPhoto);
                $validatedData['fotopasien'] = $request->file('fotopasien')->store('profilePhoto');
            } else $validatedData['fotopasien'] = '';

            Pasien::where('id', '=', $periksa->pasien->id)->update([
                'no_rm' => $validatedData['norm'],
                'no_ktp' => $validatedData['noktp'],
                'nama' => $validatedData['namapasien'],
                'tanggal_lahir' => $request->get('tahunlahir') . '-' . $request->get('bulanlahir') . '-' . $request->get('tanggallahir'),
                'jenis_kelamin' => $validatedData['jeniskelamin'],
                'alamat_detail' => $validatedData['detailalamat'],
                'tempat_lahir' => $validatedData['tempatlahir'],
                'foto_pasien' => $validatedData['fotopasien'],
                'agama' => $request->get('agama'),
                'status' => $request->get('status'),
                'pendidikan' => $request->get('pendidikanterakhir'),
                'pekerjaan' => $request->get('pekerjaan'),
                'nama_ibu' => $request->get('namaibupasien'),
                'negara' => $request->get('negara'),
                'prov' => $request->get('provinsi'),
                'kab_kota' => $request->get('kabkota'),
                'kecamatan' => $request->get('kecamatan'),
                'desa' => $request->get('desa'),
                'pangkat_gol' => $request->get('pangkatgolongan'),
                'kesatuan' => $request->get('kesatuan'),
                'NRP' => $request->get('nrp'),
                'no_hp' => $request->get('nohp'),
                'no_telp' => $request->get('notelp'),
                'fax' => $request->get('fax'),
                'email' => $request->get('email')
            ]);

            Periksa::where('id', '=', $periksa->id)->update([
                'no_lab' => $validatedData['nolab'],
                'pasien_id' => Pasien::where('no_rm', $validatedData['norm'])->pluck('id')[0],
                'dokter_id' => 0
            ]);

            return redirect('/pendaftaran/' . $validatedData['nolab'] . '/order')->with('status',  2)->with('namapasien',  $validatedData['namapasien'])->with('nolab', $validatedData['nolab']);
        }
    }

    public function destroy(Periksa $periksa)
    {
        Periksa::destroy($periksa->id);
        return redirect('/pendaftaran/order')->with('status',  0)->with('namapasien',  $periksa->pasien->nama)->with('nolab', $periksa->no_lab);
    }

    // AJAX HANDLER
    // Src : pendaftaran-index
    public function searchResTable(Request $req)
    {
        if ($req->ajax()) {
            $output = '';

            $searchVal = $req->get('searchValue');
            $sortOpt = $req->get('sortOption');
            $date = $req->get('dateTime');
            $genderFilt = $req->get('genderFilter');
            $doctorFilt = $req->get('doctorFilter');

            // Default Value 
            // $searchVal = '' 
            // $sortOpt = 'DESC'
            // $date = ''
            // $genderFilt = ''
            // $doctorFilt = ''

            if ($searchVal == '') {
                if ($date == '') {
                    if ($genderFilt == '') {
                        if ($doctorFilt == '') {
                            // All Default
                            $datas = Periksa::with(['pasien', 'dokter'])->orderBy('created_at', $sortOpt)->skip(0)->take(20)->get();
                        } else {
                            // $doctorFilt != ''
                            $datas = Periksa::with(['pasien', 'dokter'])->whereHas('dokter', function (Builder $query) use ($doctorFilt) {
                                $query->where('nama', '=', $doctorFilt);
                            })->orderBy('created_at', $sortOpt)->skip(0)->take(20)->get();
                        }
                    } else {
                        if ($doctorFilt == '') {
                            // $genderFilt != ''
                            $datas = Periksa::with(['pasien', 'dokter'])->whereHas('pasien', function (Builder $query) use ($genderFilt) {
                                $query->where('jenis_kelamin', '=', $genderFilt);
                            })->orderBy('created_at', $sortOpt)->skip(0)->take(20)->get();
                        } else {
                            // $genderFilt != '', $doctorFilt != ''
                            $datas = Periksa::with(['pasien', 'dokter'])->whereHas('pasien', function (Builder $query) use ($genderFilt) {
                                $query->where('jenis_kelamin', '=', $genderFilt);
                            })->whereHas('dokter', function (Builder $query) use ($doctorFilt) {
                                $query->where('nama', '=', $doctorFilt);
                            })->orderBy('created_at', $sortOpt)->skip(0)->take(20)->get();
                        }
                    }
                } else {
                    if ($genderFilt == '') {
                        if ($doctorFilt == '') {
                            // $date != ''
                            $datas = Periksa::with(['pasien', 'dokter'])->whereDate('created_at', '=', $date)->orderBy('created_at', $sortOpt)->skip(0)->take(20)->get();
                        } else {
                            // $date != '', $doctorFilt != ''
                            $datas = Periksa::with(['pasien', 'dokter'])->whereDate('created_at', '=', $date)->whereHas('dokter', function (Builder $query) use ($doctorFilt) {
                                $query->where('nama', '=', $doctorFilt);
                            })->orderBy('created_at', $sortOpt)->skip(0)->take(20)->get();
                        }
                    } else {
                        if ($doctorFilt == '') {
                            // $date != '', $genderFilt != ''
                            $datas = Periksa::with(['pasien', 'dokter'])->whereDate('created_at', '=', $date)->whereHas('pasien', function (Builder $query) use ($genderFilt) {
                                $query->where('jenis_kelamin', '=', $genderFilt);
                            })->orderBy('created_at', $sortOpt)->skip(0)->take(20)->get();
                        } else {
                            // $date != '', $genderFilt != '', $doctorFilt != '' 
                            $datas = Periksa::with(['pasien', 'dokter'])->whereDate('created_at', '=', $date)->whereHas('pasien', function (Builder $query) use ($genderFilt) {
                                $query->where('jenis_kelamin', '=', $genderFilt);
                            })->whereHas('dokter', function (Builder $query) use ($doctorFilt) {
                                $query->where('nama', '=', $doctorFilt);
                            })->orderBy('created_at', $sortOpt)->skip(0)->take(20)->get();
                        }
                    }
                }
            } else {
                if ($date == '') {
                    if ($genderFilt == '') {
                        if ($doctorFilt == '') {
                            // $searchVal != ''
                            $datas = Periksa::with(['pasien', 'dokter'])->whereHas('pasien', function (Builder $query) use ($searchVal) {
                                $query->where('nama', 'like', '%' . $searchVal . '%');
                            })->orWhere('no_lab', '=', $searchVal)->orderBy('created_at', $sortOpt)->skip(0)->take(20)->get();
                        } else {
                            // $searchVal != '', $doctorFilt != ''                      
                            $datas = Periksa::with(['pasien', 'dokter'])->whereHas('pasien', function (Builder $query) use ($searchVal) {
                                $query->where('nama', 'like', '%' . $searchVal . '%');
                            })->orWhere('no_lab', '=', $searchVal)->whereHas('dokter', function (Builder $query) use ($doctorFilt) {
                                $query->where('nama', '=', $doctorFilt);
                            })->orderBy('created_at', $sortOpt)->skip(0)->take(20)->get();
                        }
                    } else {
                        if ($doctorFilt == '') {
                            // $searchVal != '', $genderFilt != ''
                            $datas = Periksa::with(['pasien', 'dokter'])->whereHas('pasien', function (Builder $query) use ($searchVal) {
                                $query->where('nama', 'like', '%' . $searchVal . '%');
                            })->orWhere('no_lab', '=', $searchVal)->whereHas('pasien', function (Builder $query) use ($genderFilt) {
                                $query->where('jenis_kelamin', '=', $genderFilt);
                            })->orderBy('created_at', $sortOpt)->skip(0)->take(20)->get();
                        } else {
                            // $searchVal != '', $genderFilt != '', $doctorFilt != ''
                            $datas = Periksa::with(['pasien', 'dokter'])->whereHas('pasien', function (Builder $query) use ($searchVal) {
                                $query->where('nama', 'like', '%' . $searchVal . '%');
                            })->orWhere('no_lab', '=', $searchVal)->whereHas('pasien', function (Builder $query) use ($genderFilt) {
                                $query->where('jenis_kelamin', '=', $genderFilt);
                            })->whereHas('dokter', function (Builder $query) use ($doctorFilt) {
                                $query->where('nama', '=', $doctorFilt);
                            })->orderBy('created_at', $sortOpt)->skip(0)->take(20)->get();
                        }
                    }
                } else {
                    if ($genderFilt == '') {
                        if ($doctorFilt == '') {
                            // $searchVal != '', $date != ''
                            $datas = Periksa::with(['pasien', 'dokter'])->whereHas('pasien', function (Builder $query) use ($searchVal) {
                                $query->where('nama', 'like', '%' . $searchVal . '%');
                            })->orWhere('no_lab', '=', $searchVal)->whereDate('created_at', '=', $date)->orderBy('created_at', $sortOpt)->skip(0)->take(20)->get();
                        } else {
                            // $searchVal != '', $date != '', $doctorFilt != ''
                            $datas = Periksa::with(['pasien', 'dokter'])->whereHas('pasien', function (Builder $query) use ($searchVal) {
                                $query->where('nama', 'like', '%' . $searchVal . '%');
                            })->orWhere('no_lab', '=', $searchVal)->whereDate('created_at', '=', $date)->whereHas('dokter', function (Builder $query) use ($doctorFilt) {
                                $query->where('nama', '=', $doctorFilt);
                            })->orderBy('created_at', $sortOpt)->skip(0)->take(20)->get();
                        }
                    } else {
                        if ($doctorFilt == '') {
                            // $searchVal != '', $date != '', $genderFilt != ''
                            $datas = Periksa::with(['pasien', 'dokter'])->whereHas('pasien', function (Builder $query) use ($searchVal) {
                                $query->where('nama', 'like', '%' . $searchVal . '%');
                            })->orWhere('no_lab', '=', $searchVal)->whereDate('created_at', '=', $date)->whereHas('pasien', function (Builder $query) use ($genderFilt) {
                                $query->where('jenis_kelamin', '=', $genderFilt);
                            })->orderBy('created_at', $sortOpt)->skip(0)->take(20)->get();
                        } else {
                            // $searchVal != '', $date != '', $genderFilt != '', $doctorFilt != ''
                            $datas = Periksa::with(['pasien', 'dokter'])->whereHas('pasien', function (Builder $query) use ($searchVal) {
                                $query->where('nama', 'like', '%' . $searchVal . '%');
                            })->orWhere('no_lab', '=', $searchVal)->whereDate('created_at', '=', $date)->whereHas('pasien', function (Builder $query) use ($genderFilt) {
                                $query->where('jenis_kelamin', '=', $genderFilt);
                            })->whereHas('dokter', function (Builder $query) use ($doctorFilt) {
                                $query->where('nama', '=', $doctorFilt);
                            })->orderBy('created_at', $sortOpt)->skip(0)->take(20)->get();
                        }
                    }
                }
            }

            $total_row = $datas->count();
            if ($total_row) {
                foreach ($datas as $data) {
                    $output .= "
                        <tr class='align-middle'>
                            <td class='text-center'><a class='btn btn-success p-1 pt-0 pb-0' href='/pendaftaran/" . $data->no_lab . "/order'><small>Select</small></a></td>
                            <td>" . $data->no_lab . "</td>
                            <td>" . $data->pasien->nama . "</td>
                            <td>" . $data->created_at . "</td>
                            <td>" . $data->pasien->jenis_kelamin . "</td>
                            <td>" . Carbon::parse($data->pasien->tanggal_lahir)->diff(Carbon::now())->format('%y tahun, %m bulan') . "</td>
                            <td>" . $data->dokter->nama . "</td>
                        </tr>
                        ";
                }
            } else {
                $output = '
                <tr>
                    <td class="text-center" colspan="7">Data not found</td>
                </tr>
                ';
            }

            $datas = array(
                'table_data' => $output
            );

            echo json_encode($datas);
        }
    }

    // Src : pendaftaran-order-new order-reg-new
    public function getPatientData(Request $req)
    {

        if ($req->ajax()) {
            $output = '';
            $isNull = true;
            $searchVal = $req->get('searchValue');

            if ($searchVal == '') {
                $datas = Pasien::where('nama', '=', '!@#$%^&*()')->get();
                $isNull = true;
            } else {
                $datas = Pasien::where('nama', 'like', '%' . $searchVal . '%')->skip(0)->take(10)->get();
                $isNull = false;
            }

            $total_row = $datas->count();
            if ($total_row) {
                foreach ($datas as $data) {
                    $output .= '
                    <div class="row g-0">
                        <div class="col">
                            <a href="/pendaftaran/redirect/' . $data->no_rm . '" class="text-decoration-none">
                                <div class="card-body">
                                    <h6 class="card-title">' . $data->nama . '</h6>
                                    <p class="card-text mb-0"><small class="text-muted">No.RM : ' . $data->no_rm . '</small></p>
                                </div>
                            </a>
                        </div>
                    </div>
                    <hr class="mt-0 mb-0">
                    ';
                }
            } else {
                if ($isNull) {
                    $output = '';
                } else {
                    $output = '
                    <div class="row g-0 text-center">
                        <div class="col">
                            <div class="card-body">
                                <p class="card-text mb-0"><small class="text-muted">Patient Not Found</small></p>
                            </div>
                        </div>
                    </div>
                    <hr class="mt-0 mb-0">
                    ';
                }
            }

            $datas = array(
                'table_data' => $output
            );

            echo json_encode($datas);
        }
    }

    // Src : pendaftaran-order order-test
    public function storeDoctor(Request $request)
    {
        if ($request->ajax()) {
            $rules = [
                'kode' => ['required', 'numeric', 'unique:dokters,kode'],
                'spesialisasi' => ['required'],
                'nama' => ['required'],
                'ttd' => ['required'],
                'alamat' => ['required']
            ];
            $validatedData = $request->validate($rules);
            Dokter::create($validatedData);
            $datas = array(
                'alert' => '<div class="alert alert-success alert-dismissible fade show mt-0 mb-0" role="alert">
                                <div class="container-fluid px-0">
                                    <div class="row">
                                        <div class="col-11 text-truncate">
                                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill" /></svg>
                                            Berhasil menambahkan <b>dr. ' . $validatedData['nama'] . ' ' . $validatedData['spesialisasi'] . '</b> !
                                            <button type="button" class="btn-close" id="btn-close-doctor-alert" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>        
                                    <div>    
                                </div>    
                            </div>'
            );
            echo json_encode($datas);
        }
    }

    // Src : pendaftaran-order order-test
    public function displayDoctor(Request $request)
    {
        if ($request->ajax()) {
            $rules = [
                'nolab' => ['exists:periksas,no_lab']
            ];
            $validatedData = $request->validate($rules);
            $datas = Periksa::where('no_lab', '=', $validatedData['nolab'])->get();
            $output = '';
            $output .= "
            <tr class='align-middle'>
                <td class='text-center'><input class='form-check-input' type='checkbox' value=''></td>
                <td>" . $datas[0]->dokter->kode . "</td>
                <td>dr. " . $datas[0]->dokter->nama . " " . $datas[0]->dokter->spesialisasi . "</td>
            </tr>
            ";
            if ($datas[0]->dokter->kode == 0) {
                $output = '
                <tr>
                    <td class="text-center" colspan="3">Doctor not set</td>
                </tr>
                ';
            }
            $datas = array(
                'table_data' => $output
            );
            echo json_encode($datas);
        }
    }

    // Src : pendaftaran-order order-test
    public function updateDoctor(Request $request, Dokter $dokter)
    {
        if ($request->ajax()) {
            // Prevent doctor's name == '-'
            if ($request->get('nama') == "-") {
                return;
            }
            // Validation
            $rules = [
                'nama' => ['required']
            ];
            if ($request->get('kode') != $dokter->kode) {
                $rules['kode'] = 'required|numeric|unique:dokters,kode';
            }
            $validatedData = $request->validate($rules);
            // Update Validated Data to 'dokters' table
            Dokter::where('id', '=', $dokter->id)->update($validatedData);
        }
    }

    // Src : pendaftaran-order order-test
    public function destroyDoctor(Request $request, Dokter $dokter)
    {
        if ($request->ajax()) {
            // Delete selected doctor
            Dokter::destroy($dokter->id);
            // Send random data back
            $datas = array(
                'random' => ''
            );
            echo json_encode($datas);
        }
    }

    // Src : pendaftaran-order order-test
    public function getDoctorData(Request $req)
    {
        if ($req->ajax()) {
            $output = '';
            $isNull = true;
            $searchVal = $req->get('searchValue');

            if ($searchVal == '') {
                $datas = Dokter::where('nama', '=', '!@#$%^&*()')->get();
                $isNull = true;
            } else {
                $datas = Dokter::where('nama', 'like', '%' . $searchVal . '%')->skip(0)->take(10)->get();
                $isNull = false;
            }

            $total_row = $datas->count();
            if ($total_row) {
                foreach ($datas as $data) {
                    $output .= '
                    <div class="row g-0">
                        <div class="col">
                            <div class="card-body p-0">
                                <p class="card-text dokterlist mb-0 p-2">dr. ' . $data->nama . ' ' . $data->gelar . '</p>
                                <div class="d-none">' . $data->kode . '</div>
                            </div>  
                        </div>
                    </div>
                    <hr class="mt-0 mb-0">
                    ';
                }
            } else {
                if ($isNull) {
                    $output = '';
                } else {
                    $output = '
                    <div class="row g-0 text-center">
                        <div class="col">
                            <div class="card-body p-1">
                                <p class="card-text mb-0"><small class="text-muted">Doctor not found</small></p>
                            </div>
                        </div>
                    </div>
                    <hr class="mt-0 mb-0">
                    ';
                }
            }

            $datas = array(
                'table_data' => $output
            );

            echo json_encode($datas);
        }
    }

    // Src : pendaftaran-order order-test
    public function syncOrderTest(Request $req, Periksa $periksa)
    {
        if ($req->ajax()) {
            $rules = [
                'testCode' => ['exists:pemeriksaans,kode']
            ];
            $validatedData = $req->validate($rules);
            if ($req->get('isRemove') == 'false') {
                $item_arr = array();
                foreach ($validatedData['testCode'] as $item) {
                    array_push($item_arr, Pemeriksaan::where('kode', $item)->pluck('id')[0]);
                }
                $periksa->pemeriksaan()->syncWithoutDetaching($item_arr);
            } else {
                $item_arr = array();
                foreach ($validatedData['testCode'] as $item) {
                    array_push($item_arr, Pemeriksaan::where('kode', $item)->pluck('id')[0]);
                }
                $periksa->pemeriksaan()->detach($item_arr);
            }
        }
    }

    // Src : pendaftaran-order order-test
    public function displayOrderTest(Request $req, Periksa $periksa)
    {
        if ($req->ajax()) {
            $datas = $periksa->pemeriksaan;
            $bruto = 0;
            $netto = 0;
            $output = '';
            $total_row = $datas->count();
            if ($total_row) {
                foreach ($datas as $data) {
                    $bruto += $data->harga;
                    $netto += $data->harga;

                    if ($req->get('tableFixed')) {
                        $output .= "
                        <tr>
                            <td>" . $data->kode . "</td>
                            <td>" . $data->nama . "</td>
                            <td>0.00%</td>
                            <td>Rp. " . $data->harga . " ,-</td>
                            <td>Rp. " . $data->harga . " ,-</td>
                        </tr>
                        ";
                    } else {
                        $output .= "
                        <tr>
                            <td class='text-center'><input class='form-check-input testSelected' type='checkbox'
                                    value=''></td>
                            <td>" . $data->kode . "</td>
                            <td>" . $data->nama . "</td>
                            <td>0.00%</td>
                            <td class='text-center'><input class='form-check-input' type='checkbox'
                                    value=''></td>
                            <td class='text-end'>Rp. " . $data->harga . " ,-</td>
                        </tr>
                        ";
                    }
                }
            } else {
                $output = '
                <tr>
                    <td class="text-center" colspan="6">Test not set</td>
                </tr>
                ';
            }
            $datas = array(
                'table_data' => $output,
                'bruto' => $bruto,
                'netto' => $netto
            );
            echo json_encode($datas);
        }
    }
}
