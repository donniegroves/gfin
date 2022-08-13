<template>
<GFinLayout v-if="settings">
<div class="row">
    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Notification Settings</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body pb-3">
                <div class="row mb-3">
                    <div class="col-6">
                        Enable Text Messages: 
                    </div>
                    <div class="col-6">
                        <ToggleSwitch @checkboxChange="(key, value) => changeSettingData(key, value)" identifier="enable_sms_notifs" :checked="settings.enable_sms_notifs"/>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-6">
                        Primary SMS #:
                    </div>
                    <div class="col-6">
                        <input type="tel" class="form-control" v-model="settings.primary_sms">
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        Secondary SMS #:
                    </div>
                    <div class="col-6">
                        <input type="tel" class="form-control" v-model="settings.secondary_sms">
                    </div>
                </div>
                <div class="row mt-3 mb-0 text-right">
                    <div class="col-12">
                        <a @click="saveNotifSettings()" id="save_notifs_btn" class="btn btn-primary btn-icon-split btn-sm">
                            <span class="icon text-white-50">
                                <i class="fas fa-check"></i>
                            </span>
                            <span class="text">Save</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Revenue Sources</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Dropdown Header:</div>
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="myPieChart"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    <span class="mr-2">
                        <i class="fas fa-circle text-primary"></i> Direct
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-success"></i> Social
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-info"></i> Referral
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>



</GFinLayout>
</template>

<script>
import axios from "axios";
import GFinLayout from "@/Layouts/GFinLayout.vue";
import ToggleSwitch from "@/Components/ToggleSwitch.vue";
export default{
    components:{
        GFinLayout,
        ToggleSwitch
    },
    data: function(){
        return {
            settings: null
        }
    },
    methods:{
        async setOriginalSettings(){
            console.log('setOriginalSettings');
            const response = await axios.get('reqs/settings', {});
            if (response.status == 200){
                console.log('received original settings.');
                this.settings = response.data;
            }
        },
        async saveNotifSettings(){
            console.log('saveNotifSettings');
            const response = await axios.post('reqs/settings', {
                enable_sms_notifs: this.settings.enable_sms_notifs,
                primary_sms: this.settings.primary_sms,
                secondary_sms: this.settings.secondary_sms
            });
            if (response.status == 200){
                console.log('saved notif settings.');
            }
        },
        changeSettingData(key,value){
            this.settings[key] = value;
        }
    },
    created(){
        this.setOriginalSettings();
    }
}
</script>

<style scoped>
input{
    width: 100%;
}
.form-control {
    padding: 0 0.50rem;
    height: calc(1em + 0.75rem + 2px);
}
</style>