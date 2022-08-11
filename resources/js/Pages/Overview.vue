<template>
<GFinLayout>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Overview</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
</div>

<!-- Content Row -->
<div class="row">
    <StatCard title="This Week" :date_start="cDates.week.start" :date_end="cDates.week.end"/>
    <StatCard title="This Month" :date_start="cDates.month.start" :date_end="cDates.month.end"/>
    <StatCard title="This Quarter" :date_start="cDates.quarter.start" :date_end="cDates.quarter.end"/>
    <StatCard title="Year to Date" :date_start="cDates.ytd.start" :date_end="cDates.ytd.end"/>
</div>

<div class="row">

    <!-- Area Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
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
                <div class="chart-area">
                    <canvas id="myAreaChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Pie Chart -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
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

<!-- Content Row -->
<div class="row">

    <!-- Content Column -->
    <div class="col-lg-6 mb-4">

        <!-- Project Card Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Projects</h6>
            </div>
            <div class="card-body">
                <h4 class="small font-weight-bold">Server Migration <span
                        class="float-right">20%</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 20%"
                        aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <h4 class="small font-weight-bold">Sales Tracking <span
                        class="float-right">40%</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 40%"
                        aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <h4 class="small font-weight-bold">Customer Database <span
                        class="float-right">60%</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar" role="progressbar" style="width: 60%"
                        aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <h4 class="small font-weight-bold">Payout Details <span
                        class="float-right">80%</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 80%"
                        aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <h4 class="small font-weight-bold">Account Setup <span
                        class="float-right">Complete!</span></h4>
                <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%"
                        aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>

        <!-- Color System -->
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card bg-primary text-white shadow">
                    <div class="card-body">
                        Primary
                        <div class="text-white-50 small">#4e73df</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card bg-success text-white shadow">
                    <div class="card-body">
                        Success
                        <div class="text-white-50 small">#1cc88a</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card bg-info text-white shadow">
                    <div class="card-body">
                        Info
                        <div class="text-white-50 small">#36b9cc</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card bg-warning text-white shadow">
                    <div class="card-body">
                        Warning
                        <div class="text-white-50 small">#f6c23e</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card bg-danger text-white shadow">
                    <div class="card-body">
                        Danger
                        <div class="text-white-50 small">#e74a3b</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card bg-secondary text-white shadow">
                    <div class="card-body">
                        Secondary
                        <div class="text-white-50 small">#858796</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card bg-light text-black shadow">
                    <div class="card-body">
                        Light
                        <div class="text-black-50 small">#f8f9fc</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card bg-dark text-white shadow">
                    <div class="card-body">
                        Dark
                        <div class="text-white-50 small">#5a5c69</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="col-lg-6 mb-4">

        <!-- Illustrations -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Illustrations</h6>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                        src="img/undraw_posting_photo.svg" alt="...">
                </div>
                <p>Add some quality, svg illustrations to your project courtesy of <a
                        target="_blank" rel="nofollow" href="https://undraw.co/">unDraw</a>, a
                    constantly updated collection of beautiful svg images that you can use
                    completely free and without attribution!</p>
                <a target="_blank" rel="nofollow" href="https://undraw.co/">Browse Illustrations on
                    unDraw &rarr;</a>
            </div>
        </div>

        <!-- Approach -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Development Approach</h6>
            </div>
            <div class="card-body">
                <p>SB Admin 2 makes extensive use of Bootstrap 4 utility classes in order to reduce
                    CSS bloat and poor page performance. Custom CSS classes are used to create
                    custom components and custom utility classes.</p>
                <p class="mb-0">Before working with this theme, you should become familiar with the
                    Bootstrap framework, especially the utility classes.</p>
            </div>
        </div>

    </div>
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