<aside class="main-sidebar sidebar-light-info elevation-4">
    <a href="#" class="brand-link logo-switch">
      <img src="{{ asset('img/logo-h.png') }}" class="brand-image-xl logo-xs" style="display:none;">
      <img src="{{ asset('img/logo-h.png') }}" class="brand-image-xs logo-xl" style="margin-top: -30px;width: 65%; max-height:none !important;">
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-flat nav-compact mt-sm-4" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a @if($link=='dashboard') class="nav-link active" @else class="nav-link" @endif
                    href="{!! url('dashboard') !!}">
                        <i class="nav-icon fa fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li @if (in_array($link, ['pelimpahan','sp2t', 'sp2d'])) class="nav-item has-treeview active" @else
                class="nav-item has-treeview" @endif>
                    <a class="nav-link" href="#"><i class="nav-icon fa fa-money"></i>
                        <p>Data Kendali Keuangan <i class="fa fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a class="nav-link" href="{!! url('sp2d') !!}">
                                <i class="nav-icon fa fa-circle-o"></i>
                                <p>SP2D</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{!! url('pelimpahan') !!}">
                                <i class="nav-icon fa fa-circle-o"></i>
                                <p>Pelimpahan Uang</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{!! url('sp2t') !!}">
                                <i class="nav-icon fa fa-circle-o"></i>
                                <p>SP2T</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a @if($link=='report' ) class="nav-link active" @else class="nav-link" @endif
                    href="{!! url('report') !!}">
                        <i class="nav-icon fa fa-file-excel-o"></i>
                        <p>Cetak Data Kendali Keuangan</p>
                    </a>
                </li>
                <?php
                    $routes_master = [
                        'anggaran',
                        'program',
                        'kegiatan',
                        'belanja',
                        'pegawai',
                        'penerima'
                    ];
                ?>
                @if (in_array(Cookie::get('level'), ['1','2']))
                    <li @if (in_array($link, $routes_master)) class="nav-item has-treeview" @else class="nav-item has-treeview" @endif>
                        <a class="nav-link" href="#"><i class="nav-icon fa fa-database"></i>
                            <p>Data Master <i class="fa fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a class="nav-link" href="{!! url('user') !!}">
                                    <i class="nav-icon fa fa-circle-o"></i>
                                    <p>User</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{!! url('pegawai') !!}">
                                    <i class="nav-icon fa fa-circle-o"></i>
                                    <p>Pegawai Keuangan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{!! url('program') !!}">
                                    <i class="nav-icon fa fa-circle-o"></i>
                                    <p>Program</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{!! url('kegiatan') !!}">
                                    <i class="nav-icon fa fa-circle-o"></i>
                                    <p>Kegiatan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{!! url('belanja') !!}">
                                    <i class="nav-icon fa fa-circle-o"></i>
                                    <p>Belanja</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{!! url('anggaran') !!}">
                                    <i class="nav-icon fa fa-circle-o"></i>
                                    <p>Anggaran</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{!! url('penerima') !!}">
                                    <i class="nav-icon fa fa-circle-o"></i>
                                    <p>Penerima SP2T</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                <li class="nav-item">
                    <a @if($link=='logaktifitas' ) class="nav-link active" @else class="nav-link" @endif href="{!! url('logaktifitas') !!}">
                        <i class="nav-icon fa fa-file-text-o"></i>
                        <p>Log Aktifitas</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a @if($link=='profile' ) class="nav-link active" @else class="nav-link" @endif
                    href="{!! url('profile') !!}">
                        <i class="nav-icon fa fa-lock"></i>
                        <p>Ganti Password</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{!! url('logout') !!}">
                        <i class="nav-icon fa fa-sign-out"></i>
                        <p>Log Out</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
