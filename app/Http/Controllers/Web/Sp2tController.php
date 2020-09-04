<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use App\Models\Sp2t;
use App\Models\Sp2tDetail;
use App\Models\Anggaran;
use App\Models\Kegiatan;
use App\Models\Program;
use App\Models\Belanja;
use App\Libraries\Access;
use App\Libraries\Common;
use Closure;

class Sp2tController extends Controller
{
    protected $title = 'SP2T (Surat Perintah Pembayaran Transfer)';
    protected $link  = 'sp2t';
    protected $api   = 'api/sp2t';
    protected $route = 'sp2t';
    protected $access;
    protected $common;
    protected $_nip;

    public function __construct()
    {
        $this->middleware(
            function ($request, Closure $next) {
                if (Cookie::get('login') == true) {
                    $access = new Access();
                    $this->common = new Common();
                    $this->access = $access->generateAccess(Cookie::get('level'));
                    $this->_nip = Cookie::get('nip');
                    return $next($request);
                } else {
                    return redirect('login');
                }
            }
        );
    }

    public function index()
    {
        $breadcrumb = [];
        $breadcrumb[0] = '<a href="' . url('dashboard') . '"><i class="fa fa-dashboard"></i> Dashboard</a>';
        $breadcrumb[1] = '<i class="fa fa-database"></i> ' . $this->title;

        $data = [];
        $data['breadcrumb'] = $breadcrumb;
        $data['title']  = $this->title;
        $data['link'] = $this->link;
        $data['api'] = url($this->api);
        $data['route'] = url($this->route);
        $data['access'] = $this->access;
        return View::make('sp2t.index', $data);
    }

    public function create(Request $request)
    {
        $breadcrumb = [];
        $breadcrumb[0] = '<a href="' . url('dashboard') . '"><i class="fa fa-dashboard"></i> Dashboard</a>';
        $breadcrumb[1] = '<a href="' . url($this->route) . '"><i class="fa fa-database"></i> ' . $this->title . '</a>';
        $breadcrumb[2] = '<i class="fa fa-plus"></i> Tambah Data';

        $program = Program::all();
        $kegiatan = [];
        $belanja = [];

        $data = [];
        $data['title']  = $this->title;
        $data['link'] = $this->link;
        $data['breadcrumb'] = $breadcrumb;
        $data['api'] = url($this->api.'?sp2t='.$request['sp2t'].'&nip=' . $this->_nip);
        $data['act'] = 'create';
        $data['program'] = $program;
        $data['kegiatan'] = $kegiatan;
        $data['belanja'] = $belanja;
        $data['route'] = url($this->route);
        return View::make('sp2t.form', $data);
    }

    public function edit(Request $request)
    {
        $breadcrumb = [];
        $breadcrumb[0] = '<a href="' . url('dashboard') . '"><i class="fa fa-dashboard"></i> Dashboard</a>';
        $breadcrumb[1] = '<a href="' . url($this->route) . '"><i class="fa fa-database"></i> ' . $this->title . '</a>';
        $breadcrumb[2] = '<i class="fa fa-wrench"></i> Ubah Data';

        $sp2t = Sp2tDetail::find($request['id']);
        $program = Program::all();
        $kegiatan = [];
        $belanja = [];

        $data = [];
        $data['title']  = $this->title;
        $data['link'] = $this->link;
        $data['sp2t'] = $sp2t;
        $data['program'] = $program;
        $data['kegiatan'] = $kegiatan;
        $data['belanja'] = $belanja;
        $data['breadcrumb'] = $breadcrumb;
        $data['api'] = url($this->api . '?nip='.$this->_nip.'&id=' . $sp2t->id);
        $data['act'] = 'edit';
        $data['route'] = url($this->route);
        return View::make('sp2t.form', $data);
    }

    public function detail(Request $request)
    {
        $breadcrumb = [];
        $breadcrumb[0] = '<a href="' . url('dashboard') . '"><i class="fa fa-dashboard"></i> Dashboard</a>';
        $breadcrumb[1] = '<a href="' . url($this->route) . '"><i class="fa fa-database"></i> ' . $this->title . '</a>';
        $breadcrumb[2] = '<i class="fa fa-wrench"></i> Detail';

        $sp2t = Sp2t::with('pegawai')->find($request['id']);
        $sp2tdetail = Sp2tDetail::where('sp2t_id', $sp2t->id)->with('kegiatan', 'program', 'belanja')->get();
        $data = [];
        $data['title']  = $this->title;
        $data['link'] = $this->link;
        $data['sp2t'] = $sp2t;
        $data['sp2tdetail'] = $sp2tdetail;
        $data['breadcrumb'] = $breadcrumb;
        $data['api'] = url($this->api . '?nip='.$this->_nip);
        $data['send_api'] = url($this->api . '/nominal/send?sp2t='.$sp2t->id.'&nip='.$this->_nip);
        $data['act'] = 'edit';
        $data['route'] = url($this->route);
        $data['access'] = $this->access;
        return View::make('sp2t.detail', $data);
    }
}
