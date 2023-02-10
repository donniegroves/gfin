<template>
<GFinLayout v-if="settings">
<div class="row">
    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">SMS Notifications</h6>
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
                        Include Deposits in Calcs:
                    </div>
                    <div class="col-6">
                        <ToggleSwitch @checkboxChange="(key, value) => changeSettingData(key, value)" identifier="include_deps_in_calcs" :checked="settings.include_deps_in_calcs"/>
                    </div>
                </div>
                <div class="row mt-3 mb-0 text-right">
                    <div class="col-12">
                        <a @click="sendDailyNotif($event)" id="send_daily" data-done="Sent" class="btn btn-primary btn-icon-split btn-sm mr-2">
                            <span class="icon text-white-50">
                                <i class="fas fa-paper-plane"></i>
                            </span>
                            <span class="text">Send Daily</span>
                        </a>
                        <a @click="saveNotifSettings($event)" id="save_notifs_btn" data-done="Saved" class="btn btn-primary btn-icon-split btn-sm">
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
                <div class="row mt-3 mb-0 text-right">
                    <div class="col-12">
                        <a @click="saveCalendarSettings($event)" data-done="Saved" id="save_cal_stgs_btn" class="btn btn-primary btn-icon-split btn-sm">
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
                    primary_sms:        response.data.primary_sms,
                    secondary_sms:      response.data.secondary_sms,
                    send_daily_sms:     response.data.send_daily_sms === "1",
                    include_deps_in_calcs:    response.data.include_deps_in_calcs === "1",
                    daily_exp_budget:   response.data.daily_exp_budget
                };
            }
        },
        async saveNotifSettings(event){
            console.log('saveNotifSettings');
            this.animateButton(event);
            const response = await axios.post('reqs/settings', {
                primary_sms: this.settings.primary_sms,
                secondary_sms: this.settings.secondary_sms,
                send_daily_sms: this.settings.send_daily_sms,
                include_deps_in_calcs: this.settings.include_deps_in_calcs
            });
            if (response.status == 200){
                console.log('saved notif settings.');
            }
        },
        async sendDailyNotif(event) {
            console.log('sendDailyNotif');
            this.animateButton(event);
            const response = await axios.get('reqs/senddailynotif');
            if (response.status == 200){
                console.log('sent daily notif.');
            }
        },
        async saveCalendarSettings(event){
            console.log('saveCalendarSettings');
            this.animateButton(event);
            const response = await axios.post('reqs/settings', {
                daily_exp_budget: this.settings.daily_exp_budget
            });
            if (response.status == 200){
                console.log('saved calendar settings.');
            }
        },
        animateButton(event){
            var clicked_btn = $(event.currentTarget);
            var orig_text = clicked_btn.find("span.text").text();
            var orig_width = clicked_btn.find("span.text").width();
            clicked_btn.find("span.text").fadeOut(100,() => {
                $(event.target).width(orig_width).text(event.target.parentElement.dataset.done).addClass("font-weight-bold").fadeIn(750,() => {
                    setTimeout(() => {
                        $(event.target).fadeOut(750,()=>{
                            $(event.target).removeClass("font-weight-bold").text(orig_text).fadeIn(750,() => {
                            });
                        });
                    }, 1500);
                });
            });
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
.btn .text{
    min-width: 65px;
}
input{
    width: 100%;
}
.form-control {
    padding: 0 0.50rem;
    height: calc(1em + 0.75rem + 2px);
}
</style>
