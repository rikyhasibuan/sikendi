<template>
    <div>
        <transition name="fade">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="pull-right">
                                <button type="button" v-on:click.prevent="toggle" class="btn btn-info mb-2"><i class="fa fa-search"></i> Form Pencarian</button>
                            </div>
                            <transition name="fade">
                                <div class="card" style="margin-top:50px;" v-show="showForm">
                                    <div class="card-body">
                                        <form class="form" v-on:submit.prevent="fetchData()" autocomplete="off">
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <input type="text" class="form-control" v-model="search.q" placeholder="Nama / Nomor Rekening">
                                                </div>
                                                <div class="form-group col-md-4 col-xs-6">
                                                    <button type="submit" class="btn btn-success mb-2">
                                                        <i class="fa fa-search"></i> <span class="hidden-xs hidden-sm">Cari Data</span>
                                                    </button>
                                                    <button type="button" v-on:click.prevent="clear" class="btn btn-info mb-2">
                                                        <i class="fa fa-refresh"></i> <span class="hidden-xs hidden-sm">Reset</span>
                                                    </button>
                                                </div>
                                            </div>
                                            </form>
                                    </div>
                                </div>
                            </transition>
                        </div>
                        <div class="card-body table-responsive">
                            <v-alert :alert="alert"></v-alert>
                            <loading :opacity="100" :active.sync="isLoading" :can-cancel="false" :is-full-page="false"></loading>
                            <transition name="fade">
                                <table class="table table-hover table-striped table-bordered" v-if="showTable == true">
                                    <thead>
                                        <tr>
                                            <th style="width:25%; text-align: center;">Nama Penerima</th>
                                            <th style="width:50%; text-align: center;">Nomor Rekening</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="v in penerima" :key="v.id">
                                            <td>{{ v.nama_penerima }}</td>
                                            <td>{{ v.norek }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </transition>

                            <transition name="fade">
                                <div class="card-footer clearfix">
                                    <v-pagination
                                        :pagination="pagination"
                                        v-on:next="nextPage"
                                        v-on:previous="prevPage"
                                        v-if="showTable === true">
                                    </v-pagination>
                                </div>
                            </transition>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
import service from './../services.js';

export default {
    data: function() {
        return {
            penerima: {},
            search: {
                q:''
            },
            alert: {
                error:false,
                empty:false,
                delete:false
            },
            options: {
                format: 'YYYY-MM-DD',
                useCurrent: false,
                locale: 'id'
            },
            pagination: {
                page: 1,
                last:0,
                total:0,
                to:0,
                from:0
            },
            isLoading: false,
            showForm: false,
            showTable: false,
            id:'',
            usernip:''
        }
    },
    props: ['api','route','access'],
    methods: {
        toggle() {
            this.showForm = !this.showForm
        },
        clear() {
            this.search.q = '';
            this.fetchData();
        },
        nextPage() {
            this.pagination.page++;
            this.fetchData();
        },
        prevPage() {
            this.pagination.page--;
            this.fetchData();
        },
        fetchData() {
            this.isLoading = true;
            let query  = this.generateParams();
            service.fetchData(this.api + '?'+ query + '&page='+ this.pagination.page)
            .then(response => {
                this.renderData(response);
            })
            .catch(error => {
                this.isLoading = false;
                this.alert.error = true;
                console.log(error);
            });
        },
        renderData(response) {
            if (response.total === 0) {
                this.showTable = false;
                this.alert.empty = true;
                this.alert.error = false;
            } else {
                this.showTable = true;
                this.alert.empty = false;
                this.alert.error = false;
                this.penerima = response.data;
                this.pagination.last = response.last_page;
                this.pagination.from = response.from;
                this.pagination.to = response.to;
                this.pagination.total = response.total;
            }
            this.isLoading = false;
        },
        generateParams() {
            return Object.keys(this.search).map(key => key + '=' + this.search[key]).join('&');
        }
    },
    created() {
        this.isLoading = true;
        this.fetchData();
    }
};
</script>
