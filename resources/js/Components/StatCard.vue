<template>
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">{{ title }}: ({{getMonth(date_start) + ' ' + getDate(date_start) + ' - ' + getMonth(date_end) + ' ' + getDate(date_end)}})</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"></div>
                    <div v-for="(amt, cat) in stats" class="mb-0 font-weight-bold text-gray-800">{{ cat }}: {{ amt }}</div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    export default {
        props: ['date_start','date_end','title','stats'],
        data: function(){
            return {
            }
        },
        methods: {
            getMonth(date){
                const re = '(?<=\\d+-)\\d+(?=-\\d+)';
                const month = date.match(re);
                const months = {
                    "01": "January",
                    "02": "February",
                    "03": "March",
                    "04": "April",
                    "05": "May",
                    "06": "June",
                    "07": "July",
                    "08": "August",
                    "09": "September",
                    "10": "October",
                    "11": "November",
                    "12": "December"
                }
                return months[month];
            },
            getDate(date){
                const re = '(?<=\\d+-\\d+-)\\d+';
                return this.dateWithSuffix(date.match(re)[0]);
            },
            dateWithSuffix(date){
                const suffixes = {
                    "01": "1st",
                    "02": "2nd",
                    "03": "3rd",
                    "04": "4th",
                    "05": "5th",
                    "06": "6th",
                    "07": "7th",
                    "08": "8th",
                    "09": "9th",
                    "10": "10th",
                    "11": "11th",
                    "12": "12th",
                    "13": "13th",
                    "14": "14th",
                    "15": "15th",
                    "16": "16th",
                    "17": "17th",
                    "18": "18th",
                    "19": "19th",
                    "20": "20th",
                    "21": "21st",
                    "22": "22nd",
                    "23": "23rd",
                    "24": "24th",
                    "25": "25th",
                    "26": "26th",
                    "27": "27th",
                    "28": "28th",
                    "29": "29th",
                    "30": "30th",
                    "31": "31st"
                }

                return suffixes[date];
            }
        }
    }
</script>

<style scoped>

</style>