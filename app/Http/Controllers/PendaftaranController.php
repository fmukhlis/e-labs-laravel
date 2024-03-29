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
use LengthException;

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
            'home_service' => 0,
            'is_done' => 0
        ]);

        return redirect('/pendaftaran/order')->with('status',  1)->with('namapasien',  $validatedData['namapasien'])->with('nolab', $validatedData['nolab']);
    }

    public function update(Request $request, Periksa $periksa)
    {
        if ($request->ajax()) {
            if ($periksa->is_done != 0) return;
            // Validation
            $rules = [
                'kode' => ['exists:dokters,kode']
            ];
            $validatedData = $request->validate($rules);
            // Update Doctor Validated Data to 'periksas' table
            if ($request->get('isRemove') === 'true') {
                Periksa::where('id', '=', $periksa->id)->update([
                    'dokter_id' => 0
                ]);
            } else {
                Periksa::where('id', '=', $periksa->id)->update([
                    'dokter_id' => Dokter::where('kode', $validatedData['kode'])->pluck('id')[0]
                ]);
            }
        } else {
            if ($periksa->is_done != 0) return redirect('/pendaftaran/' . $periksa->no_lab . '/order')->with('status',  99);
            if ($request->get('is_confirm')) {
                Periksa::where('id', '=', $periksa->id)->update([
                    'is_done' => -1
                ]);
                return redirect('/pendaftaran/');
            };
            if ($request->get('metodebayar')) {
                $rules = [
                    'asalruangan' => ['required'],
                    'homeservice' => ['nullable']
                ];
                if ($request->get('metodebayar') == 'BPJS') $rules['nosep'] = ['required', 'numeric'];
                else $rules['nosep'] = ['nullable'];
                $validatedData = $request->validate($rules);
                if (!$request->get('homeservice')) $validatedData['homeservice'] = 0;
                Periksa::where('id', '=', $periksa->id)->update([
                    'asal_ruangan' => $validatedData['asalruangan'],
                    'metode_bayar' => $request->get('metodebayar'),
                    'no_sep' => $validatedData['nosep'],
                    'home_service' => $validatedData['homeservice']
                ]);
                return redirect('/pendaftaran/' . $periksa->no_lab . '/order')->with('status',  1);
            } else {
            }
        }
    }

    public function destroy(Periksa $periksa)
    {
        if ($periksa->is_done != 0) return redirect('/pendaftaran/' . $periksa->no_lab . '/order')->with('status',  99);
        Periksa::destroy($periksa->id);
        return redirect('/pendaftaran/order')->with('status',  0)->with('namapasien',  $periksa->pasien->nama)->with('nolab', $periksa->no_lab);
    }

    public function updatePatient(Request $request, Pasien $pasien)
    {
        $rules = [
            'namapasien' => ['required'],
            'tempatlahir' => ['required'],
            'jeniskelamin' => ['required'],
            'fotopasien' => ['image', 'file', 'max:1024'],
            'detailalamat' => ['required']
        ];
        if ($request->get('noktp') != $pasien->no_ktp) {
            $rules['noktp'] = 'required|numeric|unique:pasiens,no_ktp';
        } else {
            $rules['noktp'] = 'required|numeric';
        }

        $validatedData = $request->validate($rules);

        if ($request->file('fotopasien')) {
            $currentPhoto = $pasien->foto_pasien;
            if ($currentPhoto) Storage::delete($currentPhoto);
            $validatedData['fotopasien'] = $request->file('fotopasien')->store('profilePhoto');
        } else $validatedData['fotopasien'] = $pasien->foto_pasien;

        Pasien::where('id', '=', $pasien->id)->update([
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
            'kode_pos' => $request->get('kodepos'),
            'pangkat_gol' => $request->get('pangkatgolongan'),
            'kesatuan' => $request->get('kesatuan'),
            'NRP' => $request->get('nrp'),
            'no_hp' => $request->get('nohp'),
            'no_telp' => $request->get('notelp'),
            'fax' => $request->get('fax'),
            'email' => $request->get('email')
        ]);

        return redirect('/pendaftaran/' .  $request->get('nolab') . '/order')->with('status',  2)->with('namapasien',  $validatedData['namapasien'])->with('nolab', $request->get('nolab'));
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
                    $dokter = '';
                    if (strlen($data->dokter->nama) < 23) {
                        $dokter = '-';
                        if ($data->dokter->nama != '-') $dokter = 'dr. ' . $data->dokter->nama . ', ' . $data->dokter->spesialisasi;
                    } else $dokter = 'dr. ' . substr($data->dokter->nama, 0, 19) . '...' . ', ' . $data->dokter->spesialisasi;
                    $btn_class = '';
                    $data->is_done == '0' ? $btn_class = 'btn btn-secondary' : $btn_class = 'btn btn-success';
                    if ($data->is_done == -1) {
                        $btn_class = 'btn btn-primary';
                    }
                    $output .= "
                        <tr class='align-middle'>
                            <td class='text-center'><a class='" . $btn_class . " p-1 pt-0 pb-0' href='/pendaftaran/" . $data->no_lab . "/order'><small>Select</small></a></td>
                            <td>" . $data->no_lab . "</td>
                            <td>" . $data->pasien->nama . "</td>
                            <td>" . $data->created_at . "</td>
                            <td>" . $data->pasien->jenis_kelamin . "</td>
                            <td>" . Carbon::parse($data->pasien->tanggal_lahir)->diff(Carbon::now())->format('%y tahun, %m bulan') . "</td>
                            <td>" . $dokter . "</td>
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

    // Src : pendaftaran-order order-reg
    public function getProvince(Request $request)
    {
        if ($request->ajax()) {
            $provinces = DB::table('t_provinsi')->get();
            $id = array();
            $name = array();
            foreach ($provinces as $province) {
                array_push($id, $province->id);
                array_push($name, $province->nama);
            }
            $datas = array(
                'id_prov' => $id,
                'nama_prov' => $name
            );
            echo json_encode($datas);
        }
    }

    // Src : pendaftaran-order order-reg
    public function getCities(Request $request)
    {
        if ($request->ajax()) {
            $cities = DB::table('t_kota')->where('id', 'LIKE', $request->provinceId . '%')->get();
            $id = array();
            $name = array();
            foreach ($cities as $city) {
                array_push($id, $city->id);
                array_push($name, $city->nama);
            }
            $datas = array(
                'id_kota' => $id,
                'nama_kota' => $name
            );
            echo json_encode($datas);
        }
    }

    // Src : pendaftaran-order order-reg
    public function getDistricts(Request $request)
    {
        if ($request->ajax()) {
            $districts = DB::table('t_kecamatan')->where('id', 'LIKE', $request->cityId . '%')->get();
            $id = array();
            $name = array();
            foreach ($districts as $district) {
                array_push($id, $district->id);
                array_push($name, $district->nama);
            }
            $datas = array(
                'id_kec' => $id,
                'nama_kec' => $name
            );
            echo json_encode($datas);
        }
    }

    // Src : pendaftaran-order order-reg
    public function getVillages(Request $request)
    {
        if ($request->ajax()) {
            $villages = DB::table('t_kelurahan')->where('id', 'LIKE', $request->districtId . '%')->get();
            $id = array();
            $name = array();
            foreach ($villages as $village) {
                array_push($id, $village->id);
                array_push($name, $village->nama);
            }
            $datas = array(
                'id_desa' => $id,
                'nama_desa' => $name
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
                'no_skp' => ['nullable'],
                'no_sertif_skp' => ['nullable'],
                'ttd' => ['required', 'image', 'file', 'max:1024'],
                'alamat' => ['required'],
                'alamat_praktek' => ['nullable'],
                'no_telp' => ['nullable'],
                'no_hp' => ['nullable'],
                'email' => ['nullable']
            ];
            $validatedData = $request->validate($rules);
            $validatedData['ttd'] = $request->file('ttd')->store('doctorImage');
            Dokter::create($validatedData);
            $datas = array(
                'alert' => '<div class="alert alert-success alert-dismissible fade show mt-0 mb-0" role="alert">
                                <div class="container-fluid px-0">
                                    <div class="row">
                                        <div class="col-11 text-truncate">
                                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill" /></svg>
                                            Berhasil menambahkan data dokter !
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
            $dataPeriksa = Periksa::where('no_lab', '=', $validatedData['nolab'])->get();
            $dokter = '';
            if (strlen($dataPeriksa[0]->dokter->nama) < 33) $dokter = $dataPeriksa[0]->dokter->nama;
            else $dokter = substr($dataPeriksa[0]->dokter->nama, 0, 29) . '...';
            $output = '';
            $output .= "
            <tr class='align-middle'>
                <td class='text-center'><input class='form-check-input' type='checkbox' value=''></td>
                <td>" . $dataPeriksa[0]->dokter->kode . "</td>
                <td>dr. " . $dokter . ", " . $dataPeriksa[0]->dokter->spesialisasi . "</td>
            </tr>
            ";
            if ($dataPeriksa[0]->dokter->id == 0) {
                $output = '
                <tr>
                    <td class="text-center" colspan="3">Doctor not set</td>
                </tr>
                ';
            }
            $datas = array(
                'table_data' => $output,
                'kode' => $dataPeriksa[0]->dokter->kode,
                'spesialisasi' => $dataPeriksa[0]->dokter->spesialisasi,
                'nama' => $dataPeriksa[0]->dokter->nama,
                'no_skp' => $dataPeriksa[0]->dokter->no_skp,
                'no_sertif_skp' => $dataPeriksa[0]->dokter->no_sertif_skp,
                'ttd' => $dataPeriksa[0]->dokter->ttd,
                'alamat' => $dataPeriksa[0]->dokter->alamat,
                'alamat_praktek' => $dataPeriksa[0]->dokter->alamat_praktek,
                'no_telp' => $dataPeriksa[0]->dokter->no_telp,
                'no_hp' => $dataPeriksa[0]->dokter->no_hp,
                'email' => $dataPeriksa[0]->dokter->email
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
                'spesialisasi' => ['required'],
                'nama' => ['required'],
                'no_skp' => ['nullable'],
                'no_sertif_skp' => ['nullable'],
                'ttd' => ['image', 'file', 'max:1024'],
                'alamat' => ['required'],
                'alamat_praktek' => ['nullable'],
                'no_telp' => ['nullable'],
                'no_hp' => ['nullable'],
                'email' => ['nullable']
            ];
            $validatedData = $request->validate($rules);
            if ($request->file('ttd')) {
                $currentPhoto = $dokter->ttd;
                if ($currentPhoto) Storage::delete($currentPhoto);
                $validatedData['ttd'] = $request->file('ttd')->store('doctorImage');
            } else $validatedData['ttd'] = $dokter->ttd;

            // Update Validated Data to 'dokters' table
            Dokter::where('id', '=', $dokter->id)->update($validatedData);
        }
    }

    // Src : pendaftaran-order order-test
    public function destroyDoctor(Request $request, Dokter $dokter)
    {
        if ($request->ajax()) {
            Periksa::where('no_lab', '=', $request->get('nolab'))->get()[0]->update(array('dokter_id' => 0));
            // Delete selected doctor
            $currentPhoto = $dokter->ttd;
            if ($currentPhoto) Storage::delete($currentPhoto);
            Dokter::destroy($dokter->id);
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
                    <div class="row">
                        <div class="col">
                            <div class="card-body p-0 border border-top-0">
                                <p class="card-text dokterlist mb-0 p-2">dr. ' . $data->nama . ', ' . $data->spesialisasi . '</p>
                                <div class="d-none">' . $data->kode . '</div>
                            </div>  
                        </div>
                    </div>
                    ';
                }
            } else {
                if ($isNull) {
                    $output = '';
                } else {
                    $output = '
                    <div class="row g-0 text-center">
                        <div class="col">
                            <div class="card-body p-0 border border-top-0">
                                <p class="card-text mb-0 p-2"><small class="text-muted">Doctor not found</small></p>
                            </div>
                        </div>
                    </div>
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
        if ($periksa->is_done != 0) return;
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
