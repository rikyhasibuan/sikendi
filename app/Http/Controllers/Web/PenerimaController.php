<?php

namespace App\Http\Controllers\Web;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use App\Libraries\Access;
use Closure;

class PenerimaController extends Controller
{
    protected $title = 'Penerima';
    protected $link  = 'penerima';
    protected $api   = 'api/penerima';
    protected $route = 'penerima';
    protected $access;
    protected $_nip;

    public function __construct()
    {
        $this->middleware(
            function ($request, Closure $next) {
                if (Cookie::get('login') == true) {
                    $access = new Access();
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
        $breadcrumb[0] = '<a href="' . url('admin/dashboard') . '"><i class="fa fa-dashboard"></i> Dashboard</a>';
        $breadcrumb[1] = '<i class="fa fa-file-text"></i> ' . $this->title;

        $data = [];
        $data['breadcrumb'] = $breadcrumb;
        $data['title']  = $this->title;
        $data['link'] = $this->link;
        $data['api'] = url($this->api);
        $data['route'] = url($this->route);
        $data['access'] = $this->access;
        return View::make('penerima', $data);
    }
}
