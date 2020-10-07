<template>
    <div style="margin-bottom:35px;">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" href="#revisi_kassubag" role="tab" data-toggle="tab">
                    <span v-if="approval.kassubag === 1"><i class="fa fa-check"></i>&nbsp;</span>Catatan Revisi Kassubag
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#revisi_verifikatur" role="tab" data-toggle="tab">
                    <span v-if="approval.verifikatur === 1"><i class="fa fa-check"></i>&nbsp;</span>Catatan Revisi Verifikatur
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="revisi_verifikatur">
                <div style="margin-top:10px;"></div>
                <div class="direct-chat-messages" style="height:auto !important;" v-if="revisi.verifikatur.length > 0">
                    <div class="direct-chat-msg" v-for="(v,k) in revisi.verifikatur" :key="k">
                        <div class="direct-chat-infos clearfix" style="font-size:inherit;">
                            <span class="direct-chat-name float-left">Verifikatur</span>
                            <span class="direct-chat-timestamp float-right">{{ v.created_at | long_moment }}</span>
                        </div>
                        <div class="direct-chat-text" style="margin-left: 0px !important;">
                            {{ v.catatan }}
                        </div>
                    </div>
                </div>
                <div class="alert alert-danger" role="alert" v-else style="text-align: center;">
                    <i class="fa fa-exclamation-triangle"></i> TIDAK ADA CATATAN REVISI
                </div>
            </div>

            <div role="tabpanel" class="tab-pane fade show active" id="revisi_kassubag">
                <div style="margin-top:10px;"></div>
                <div class="direct-chat-messages" style="height:auto !important;" v-if="revisi.kassubag.length > 0">
                    <div class="direct-chat-msg" v-for="(v,k) in revisi.kassubag" :key="k">
                        <div class="direct-chat-infos clearfix" style="font-size:inherit;">
                            <span class="direct-chat-name float-left">Kassubag</span>
                            <span class="direct-chat-timestamp float-right">{{ v.created_at | long_moment }}</span>
                        </div>
                        <div class="direct-chat-text" style="margin-left: 0px !important;">
                            {{ v.catatan }}
                        </div>
                    </div>
                </div>
                <div class="alert alert-danger" role="alert" v-else style="text-align: center;">
                    <i class="fa fa-exclamation-triangle"></i> TIDAK ADA CATATAN REVISI
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['revisi', 'approval']
}
</script>
