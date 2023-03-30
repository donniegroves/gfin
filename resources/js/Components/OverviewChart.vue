<template>
<div class="col-6">
    <div class="card shadow mb-3">
        <div class="card-header">
            <h6>{{ displayDate(cur_stats.start_date) }} - {{  displayDate(cur_stats.end_date) }}</h6>
            <v-select v-model="selected_range" @option:selected="changeRange($event)" :searchable=false :options="vselect_opts" :clearable="false"></v-select>
        </div>
        <div class="card-body">
            <Bar :data="chart_data" :options="chart_options" />
        </div>
    </div>
</div>
</template>

<script>
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    BarElement,
    CategoryScale,
    LinearScale
} from 'chart.js';
import { Bar } from 'vue-chartjs';
import vSelect from 'vue-select';

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip)

export default {
    components: {
        Bar, vSelect
    },
    data() {
        let vselect_opts = [
            { label: 'Weekly', rangeVal: "week" },
            { label: 'Monthly', rangeVal: "month" },
            { label: 'Quarterly', rangeVal: "quarter" },
            { label: 'Yearly', rangeVal: "year" }
        ];

        return {
            chart_data: this.removeUncategorized(this.startingRange),
            chart_options: {
                responsive: true,
                backgroundColor: "#4e73df",
                barThickness: 35,
                plugins: {
                    tooltip: {
                        callbacks: {
                            title: function(context){
                                return "";
                            }
                        },
                        displayColors: false,
                        padding: 10,
                        bodyFont: {
                            size: 18
                        }
                    }
                },
            },
            selected_range: vselect_opts.find(o => o.rangeVal === this.startingRange).label,
            cur_stats: this.all_stats[this.startingRange],
            vselect_opts: [
                { label: 'Weekly', rangeVal: "week" },
                { label: 'Monthly', rangeVal: "month" },
                { label: 'Quarterly', rangeVal: "quarter" },
                { label: 'Yearly', rangeVal: "year" }
            ]
        }
    },
    props: {
        startingRange: {
            type: String,
            required: true,
            validator: function(value) {
                return ['week', 'month', 'quarter', 'year'].includes(value)
            }
        },
        all_stats: {
            type: [Object, null],
            required: true
        }
    },
    methods: {
        displayDate(date_string){
            let [year, month, day] = date_string.split('-');
            let proper_date = new Date(year, month - 1, day);
            return proper_date.toLocaleDateString('en-US', { month: 'short', day: 'numeric'});
        },
        changeRange(x){
            let range_to_change_to = x.rangeVal;
            this.chart_data = this.removeUncategorized(range_to_change_to);
        },
        removeUncategorized(range){
            this.cur_stats = this.all_stats[range];
            let chart_stats = this.all_stats[range].cat_totals;
            let sorted_stats = {};
            Object.keys(chart_stats).sort().forEach(function(key) {
                sorted_stats[key] = chart_stats[key];
            });
            delete sorted_stats.Uncategorized;
            return {
                labels: Object.keys(sorted_stats),
                datasets: [{data: Object.values(sorted_stats).map(value => value * -1)}]
            }
        }
    }
}
</script>

<style>
.card-header h6, .card-header .v-select {
    display: inline;
}
.card-header .v-select {
    float: right;
    width: 150px;
}
</style>
