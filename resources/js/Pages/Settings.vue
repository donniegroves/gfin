<template>
<GFinLayout v-if="settings">
<div class="row">
    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">SMS Notifications</h6>
                <ToggleSwitch @checkboxChange="(key, value) => changeSettingData(key, value)" identifier="enable_sms_notifs" :checked="settings.enable_sms_notifs"/>
            </div>
            <!-- Card Body -->
            <div class="card-body pb-3">
                <div class="row mb-1">
                    <div class="col-6">
                        Primary SMS #:
                    </div>
                    <div class="col-6">
                        <input type="tel" class="form-control" v-model="settings.primary_sms">
                    </div>
                </div>
                <div class="row pb-2">
                    <div class="col-6">
                        Secondary SMS #:
                    </div>
                    <div class="col-6">
                        <input type="tel" class="form-control" v-model="settings.secondary_sms">
                    </div>
                </div>
                <div class="row pb-2">
                    <div class="col-6">
                        Daily Notifications:
                    </div>
                    <div class="col-6">
                        <ToggleSwitch @checkboxChange="(key, value) => changeSettingData(key, value)" identifier="send_daily_sms" :checked="settings.send_daily_sms"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        Weekly Notifications:
                    </div>
                    <div class="col-6">
                        <ToggleSwitch @checkboxChange="(key, value) => changeSettingData(key, value)" identifier="send_weekly_sms" :checked="settings.send_weekly_sms"/>
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
                <h6 class="m-0 font-weight-bold text-primary">Calendar Settings</h6>
            </div>
            <div class="card-body pb-3">
                <div class="row mb-1">
                    <div class="col-6">
                        Daily Expense Budget
                    </div>
                    <div class="col-6">
                        <input type="number" min="0.01" step="0.01" class="form-control" v-model="settings.daily_exp_budget">
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-6">
                        Weekly Expense Budget
                    </div>
                    <div class="col-6">
                        <input type="number" min="0.01" step="0.01" class="form-control" v-model="settings.weekly_exp_budget">
                    </div>
                </div>
                <div class="row mt-3 mb-0 text-right">
                    <div class="col-12">
                        <a @click="saveCalendarSettings()" id="save_cal_stgs_btn" class="btn btn-primary btn-icon-split btn-sm">
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
                this.settings = {
                    enable_sms_notifs:  response.data.enable_sms_notifs === "1",
                    primary_sms:        response.data.primary_sms,
                    secondary_sms:      response.data.secondary_sms,
                    send_daily_sms:     response.data.send_daily_sms === "1",
                    send_weekly_sms:    response.data.send_weekly_sms === "1",
                    daily_exp_budget:   response.data.daily_exp_budget,
                    weekly_exp_budget:  response.data.weekly_exp_budget
                };
            }
        },
        async saveNotifSettings(){
            console.log('saveNotifSettings');
            const response = await axios.post('reqs/settings', {
                enable_sms_notifs: this.settings.enable_sms_notifs,
                primary_sms: this.settings.primary_sms,
                secondary_sms: this.settings.secondary_sms,
                send_daily_sms: this.settings.send_daily_sms,
                send_weekly_sms: this.settings.send_weekly_sms
            });
            if (response.status == 200){
                console.log('saved notif settings.');
            }
        },
        async saveCalendarSettings(){
            console.log('saveCalendarSettings');
            const response = await axios.post('reqs/settings', {
                daily_exp_budget: this.settings.daily_exp_budget,
                weekly_exp_budget: this.settings.weekly_exp_budget
            });
            if (response.status == 200){
                console.log('saved calendar settings.');
            }
        },
        changeSettingData(key,value){
            console.log ('changing ' + key + ' to ' + value);
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