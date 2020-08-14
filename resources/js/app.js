/*jshint esversion: 8 */
import Vue from 'vue';
import { ModalEvent, DropdownEvent, AlertEvent } from 'bootstrap/dist/js/bootstrap.min.js';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import VueCookies from 'vue-cookies';
import moment from 'moment/moment';
import 'moment/locale/id';
import money from 'v-money';
import accounting from 'accounting';
import datePicker from 'vue-bootstrap-datetimepicker';
import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';
import Multiselect from 'vue-multiselect';

Vue.use(money, {
    decimal: ',',
    thousands: '.',
    prefix: 'Rp ',
    suffix: '',
    precision: 0,
    masked: true
});

Vue.use(datePicker);
Vue.use(accounting);
Vue.use(VueCookies);

// Component Config
$.extend(true, $.fn.datetimepicker.defaults, {
    icons: {
        time: 'fa fa-clock',
        date: 'fa fa-calendar',
        up: 'fa fa-arrow-up',
        down: 'fa fa-arrow-down',
        previous: 'fa fa-chevron-left',
        next: 'fa fa-chevron-right',
        today: 'fa fa-calendar-check',
        clear: 'fa fa-trash-alt',
        close: 'fa fa-times-circle'
    }
});

VueCookies.config('1d');

// Date Filtering
Vue.filter('moment', function (date) {
    return moment(date).format('D MMMM YYYY');
});

// Date Filtering
Vue.filter('long_moment', function (date) {
    return moment(date).format('HH:mm [WIB], DD MMMM YYYY');
});

// Short Date Filtering
Vue.filter('short_moment', function (date) {
    return moment(date).format('D/MM/YYYY');
});

// Generate Year
Vue.filter('year', function (date) {
    return moment(date).format('YYYY');
});

// Date Filtering
Vue.filter('difference', function (date) {
    const now = moment(new Date());
    const end = moment(new Date(date));

    const duration = moment.duration(end.diff(now));
    let days = duration.asDays();
    let months = duration.asMonths();
    let output = '';

    if ((parseInt(days) <= 60) && (parseInt(days) > 0)) {
        output = '<span class="badge alert-danger" style="font-size:100%;">' + parseInt(days) + ' Hari</span>';
    } else if (parseInt(days) > 60) {
        output = '<span class="badge alert-success" style="font-size:100%;">' + parseInt(months) + ' Bulan</span>';
    } else if (parseInt(days) === 0) {
        output = '<span class="badge alert-danger" style="font-size:100%;">Hari Ini</span>';
    } else if (parseInt(days) < 0) {
        output = '<span class="badge alert-default" style="font-size:100%;">Kontrak Habis</span>';
    }
    return output;
});

// Short Date Filtering
Vue.filter('short_difference', function (since, until) {
    const from = moment(new Date(since));
    const end = moment(new Date(until));
    const duration = moment.duration(end.diff(from));
    let days = duration.asDays();
    let callback;
    if (parseInt(days) > 0) {
        callback = days;
    } else if (parseInt(days) === 0) {
        callback = 1;
    }
    return callback;
});

// Filter Generate Date
Vue.filter('month', function (month) {
    const bulan = {
        1: 'Januari',
        2: 'Februari',
        3: 'Maret',
        4: 'April',
        5: 'Mei',
        6: 'Juni',
        7: 'Juli',
        8: 'Agustus',
        9: 'September',
        10: 'Oktober',
        11: 'November',
        12: 'Desember',
    };
    let output = bulan[month];
    return output;
});

// Filter Rupiah
Vue.filter('rupiah', function (amount) {
    return accounting.formatMoney(amount, "", 0, ".", ",");
});

/**
 *  Component Registration
 */
Vue.component('v-alert', require('./components/Alert.vue'));
Vue.component('v-pagination', require('./components/Pagination.vue'));
Vue.component('v-delete', require('./components/Delete.vue'));
Vue.component('v-revision', require('./components/Revision.vue'));
Vue.component('v-approval', require('./components/Approval.vue'));
Vue.component('v-revision-log', require('./components/RevisionLog.vue'));
Vue.component('multiselect', Multiselect);
Vue.component('loading', Loading);

Vue.component('login', require('./views/Login.vue'));
Vue.component('dashboard', require('./views/Dashboard.vue'));
Vue.component('profile', require('./views/Profile.vue'));

Vue.component('program-index', require('./views/Program/Index.vue'));
Vue.component('program-create', require('./views/Program/Create.vue'));
Vue.component('program-update', require('./views/Program/Update.vue'));

Vue.component('kegiatan-index', require('./views/Kegiatan/Index.vue'));
Vue.component('kegiatan-create', require('./views/Kegiatan/Create.vue'));
Vue.component('kegiatan-update', require('./views/Kegiatan/Update.vue'));

Vue.component('belanja-index', require('./views/Belanja/Index.vue'));
Vue.component('belanja-create', require('./views/Belanja/Create.vue'));
Vue.component('belanja-update', require('./views/Belanja/Update.vue'));

Vue.component('pegawai-index', require('./views/Pegawai/Index.vue'));
Vue.component('pegawai-create', require('./views/Pegawai/Create.vue'));
Vue.component('pegawai-update', require('./views/Pegawai/Update.vue'));

Vue.component('user-index', require('./views/User/Index.vue'));
Vue.component('user-create', require('./views/User/Create.vue'));
Vue.component('user-update', require('./views/User/Update.vue'));

Vue.component('anggaran-index', require('./views/Anggaran/Index.vue'));
Vue.component('anggaran-create', require('./views/Anggaran/Create.vue'));
Vue.component('anggaran-update', require('./views/Anggaran/Update.vue'));

Vue.component('pelimpahan-index', require('./views/Pelimpahan/Index.vue'));
Vue.component('pelimpahan-create', require('./views/Pelimpahan/Create.vue'));
Vue.component('pelimpahan-update', require('./views/Pelimpahan/Update.vue'));
Vue.component('pelimpahan-detail', require('./views/Pelimpahan/Detail.vue'));
Vue.component('pelimpahan-nominal-create', require('./views/Pelimpahan/NominalCreate.vue'));
Vue.component('pelimpahan-nominal-update', require('./views/Pelimpahan/NominalUpdate.vue'));

Vue.component('sp2d-index', require('./views/SP2D/Index.vue'));
Vue.component('sp2d-create', require('./views/SP2D/Create.vue'));
Vue.component('sp2d-update', require('./views/SP2D/Update.vue'));
Vue.component('sp2d-detail', require('./views/SP2D/Detail.vue'));

Vue.component('sp2t-index', require('./views/SP2T/Index.vue'));
Vue.component('sp2t-create', require('./views/SP2T/Create.vue'));
Vue.component('sp2t-update', require('./views/SP2T/Update.vue'));
Vue.component('sp2t-detail', require('./views/SP2T/Detail.vue'));

Vue.component('report', require('./views/Report.vue'));
Vue.component('log-index', require('./views/Log.vue'));
Vue.component('penerima-index', require('./views/Penerima.vue'));

Vue.config.productionTip = false;
new Vue().$mount('#app');
