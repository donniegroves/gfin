<template>
<GFinLayout>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Overview</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
</div>

<!-- Content Row -->
<div v-if="stats" class="row">
    <StatCard title="This Week" :date_start="cDates.week.start" :date_end="cDates.week.end" :stats="stats.week"/>
    <StatCard title="This Month" :date_start="cDates.month.start" :date_end="cDates.month.end" :stats="stats.month"/>
    <StatCard title="This Quarter" :date_start="cDates.quarter.start" :date_end="cDates.quarter.end" :stats="stats.quarter"/>
    <StatCard title="YTD" :date_start="cDates.ytd.start" :date_end="cDates.ytd.end" :stats="stats.year"/>
</div>
</GFinLayout>
</template>

<script>
    import GFinLayout from "@/Layouts/GFinLayout.vue";
    import StatCard from "@/Components/StatCard.vue";
    export default {
        components: {
    GFinLayout,
    StatCard
},
        data: function(){
            return {
                stats: null
            }
        },
        methods: {
            async getStats(){
                console.log('getStats');
                const response = await axios.get('reqs/transactions/stats', {});
                if (response.status == 200){
                    console.log('received stats',response.data);
                    this.stats = response.data;
                }
            }
        },
        created(){
            this.getStats();
        },
        computed:{
            cDates(){
                let today = new Date();
                let curYear = today.getFullYear();
                let curMonth = today.getMonth();
                let curQuarter = Math.floor(curMonth / 3 + 1);
                let quarters = {
                    1: {
                        start: curYear + '-01-01',
                        end: curYear + '-03-31'
                    },
                    2: {
                        start: curYear + '-04-01',
                        end: curYear + '-06-30'
                    },
                    3: {
                        start: curYear + '-07-01',
                        end: curYear + '-09-30'
                    },
                    4: {
                        start: curYear + '-10-01',
                        end: curYear + '-12-31'
                    }
                }
                let firstDayOfMonth = new Date(curYear,curMonth,1);
                let lastDayOfMonth = new Date(curYear,curMonth+1,0);
                let firstDayOfWeek = new Date(curYear,curMonth,today.getDate()-today.getDay());
                let lastDayOfWeek = new Date(curYear,curMonth,today.getDate()-today.getDay()+6);                

                return {
                    ytd: {
                        start: curYear + '-01-01',
                        end: curYear + '-12-31'
                    },
                    quarter: {
                        start: quarters[curQuarter].start,
                        end: quarters[curQuarter].end
                    },
                    month: {
                        start: 
                            firstDayOfMonth.getFullYear() + '-' 
                            + (firstDayOfMonth.getMonth() > 9 ? firstDayOfMonth.getMonth() : ('0' + firstDayOfMonth.getMonth()) ) + '-' 
                            + (firstDayOfMonth.getDate() > 9 ? firstDayOfMonth.getDate() : ('0' + firstDayOfMonth.getDate()) ),
                        end: 
                            lastDayOfMonth.getFullYear() + '-' 
                            + (lastDayOfMonth.getMonth() > 9 ? lastDayOfMonth.getMonth() : ('0' + lastDayOfMonth.getMonth()) ) + '-' 
                            + (lastDayOfMonth.getDate() > 9 ? lastDayOfMonth.getDate() : ('0' + lastDayOfMonth.getDate()) )
                    },
                    week: {
                        start: 
                            firstDayOfWeek.getFullYear() + '-' 
                            + (firstDayOfWeek.getMonth() > 9 ? firstDayOfWeek.getMonth() : ('0' + firstDayOfWeek.getMonth()) ) + '-' 
                            + (firstDayOfWeek.getDate() > 9 ? firstDayOfWeek.getDate() : ('0' + firstDayOfWeek.getDate()) ),
                        end: 
                            lastDayOfWeek.getFullYear() + '-' 
                            + (lastDayOfWeek.getMonth() > 9 ? lastDayOfWeek.getMonth() : ('0' + lastDayOfWeek.getMonth()) ) + '-' 
                            + (lastDayOfWeek.getDate() > 9 ? lastDayOfWeek.getDate() : ('0' + lastDayOfWeek.getDate()) )
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>