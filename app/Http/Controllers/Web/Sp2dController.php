<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use App\Models\Anggaran;
use App\Models\Sp2d;
use App\Libraries\Access;
use App\Libraries\Common;
use Closure;

class Sp2dController extends Controller
{
    protected $title = 'SP2D';
    protected $link  = 'sp2d';
    protected $api   = 'api/sp2d';
    protected $route = 'sp2d';
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
        return View::make('sp2d.index', $data);
    }

    public function create()
    {
        $breadcrumb = [];
        $breadcrumb[0] = '<a href="' . url('dashboard') . '"><i class="fa fa-dashboard"></i> Dashboard</a>';
        $breadcrumb[1] = '<a href="' . url($this->route) . '"><i class="fa fa-database"></i> ' . $this->title . '</a>';
        $breadcrumb[2] = '<i class="fa fa-plus"></i> Tambah Data';

        $tahun = Anggaran::groupBy('tahun')->select('tahun')->get();

        $data = [];
        $data['title']  = $this->title;
        $data['link'] = $this->link;
        $data['breadcrumb'] = $breadcrumb;
        $data['api'] = url($this->api.'?nip=' . $this->_nip);
        $data['act'] = 'create';
        $data['tahun'] = $tahun;
        $data['route'] = url($this->route);
        return View::make('sp2d.form', $data);
    }

    public function edit(Request $request)
    {
        $breadcrumb = [];
        $breadcrumb[0] = '<a href="' . url('dashboard') . '"><i class="fa fa-dashboard"></i> Dashboard</a>';
        $breadcrumb[1] = '<a href="' . url($this->route) . '"><i class="fa fa-database"></i> ' . $this->title . '</a>';
        $breadcrumb[2] = '<i class="fa fa-wrench"></i> Ubah Data';

        $sp2d = Sp2d::find($request['id']);

        $data = [];
        $data['title']  = $this->title;
        $data['link'] = $this->link;
        $data['sp2d'] = $sp2d;
        $data['breadcrumb'] = $breadcrumb;
        $data['api'] = url($this->api . '?nip='.$this->_nip.'&id=' . $sp2d->id);
        $data['act'] = 'edit';
        $data['route'] = url($this->route);
        return View::make('sp2d.form', $data);
    }

    public function detail(Request $request)
    {
        $breadcrumb = [];
        $breadcrumb[0] = '<a href="' . url('dashboard') . '"><i class="fa fa-dashboard"></i> Dashboard</a>';
        $breadcrumb[1] = '<a href="' . url($this->route) . '"><i class="fa fa-database"></i> ' . $this->title . '</a>';
        $breadcrumb[2] = '<i class="fa fa-wrench"></i> Detail';

        $pelimpahan = Pelimpahan::find($request['id']);
        $pelimpahandetail = PelimpahanDetail::where('pelimpahan_id', $pelimpahan->id)->get();
        $bendahara = Kegiatan::with('pegawai')->groupBy('bendahara')->get();

        $data = [];
        $data['title']  = $this->title;
        $data['link'] = $this->link;
        $data['pelimpahan'] = $pelimpahan;
        $data['pelimpahandetail'] = $pelimpahandetail;
        $data['bendahara'] = $bendahara;
        $data['breadcrumb'] = $breadcrumb;
        $data['api'] = url($this->api . '?nip='.$this->_nip.'&id=' . $pelimpahan->id);
        $data['act'] = 'edit';
        $data['route'] = url($this->route);
        $data['access'] = $this->access;
        return View::make('pelimpahan.detail', $data);
    }
}
