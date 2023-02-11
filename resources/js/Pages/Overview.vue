<template>
<GFinLayout>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Overview</h1>
</div>
<div class="row">
    <OverviewChart v-if="stats !== null" startingRange="month" :all_stats="stats" />
    <OverviewChart v-if="stats !== null" startingRange="quarter" :all_stats="stats" />
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
    import OverviewChart from "@/Components/OverviewChart.vue";
    export default {
        components: {
    GFinLayout,
    StatCard,
    OverviewChart
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
                let curMonthIndex = today.getMonth();
                let curQuarter = Math.floor(curMonthIndex / 3 + 1);
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
                let firstDayOfMonth = new Date(curYear,curMonthIndex,1);
                let lastDayOfMonth = new Date(curYear,curMonthIndex+1,0);
                let firstDayOfWeek = new Date(curYear,curMonthIndex,today.getDate()-today.getDay());
                let lastDayOfWeek = new Date(curYear,curMonthIndex,today.getDate()-today.getDay()+6);

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
                            today.getFullYear() + '-' + ((curMonthIndex+1)<9?("0"+(curMonthIndex+1)):(curMonthIndex+1)) + '-'
                            + (firstDayOfMonth.getDate() > 9 ? firstDayOfMonth.getDate() : ('0' + firstDayOfMonth.getDate()) ),
                        end:
                            lastDayOfMonth.getFullYear() + '-' + ((curMonthIndex+1)<9?("0"+(curMonthIndex+1)):(curMonthIndex+1)) + '-'
                            + (lastDayOfMonth.getDate() > 9 ? lastDayOfMonth.getDate() : ('0' + lastDayOfMonth.getDate()) )
                    },
                    week: {
                        start:
                            firstDayOfWeek.getFullYear() + '-' + ((firstDayOfWeek.getMonth()+1)<9?("0"+(firstDayOfWeek.getMonth()+1)):(firstDayOfWeek.getMonth()+1)) + '-'
                            + (firstDayOfWeek.getDate() > 9 ? firstDayOfWeek.getDate() : ('0' + firstDayOfWeek.getDate()) ),
                        end:
                            lastDayOfWeek.getFullYear() + '-' + ((lastDayOfWeek.getMonth()+1)<9?("0"+(lastDayOfWeek.getMonth()+1)):(lastDayOfWeek.getMonth()+1)) + '-'
                            + (lastDayOfWeek.getDate() > 9 ? lastDayOfWeek.getDate() : ('0' + lastDayOfWeek.getDate()) )
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>
