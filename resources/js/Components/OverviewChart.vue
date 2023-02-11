<template>
<div class="col-6">
    <div class="card shadow mb-3">
        <div class="card-header">
            <h6>Bar Chart!</h6>
            <v-select v-model="cur_range" @option:selected="changeRange($event)" :searchable=false :options="vselect_opts" :clearable="false"></v-select>
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
        return {
            chart_data: this.removeUncategorized(),
            chart_options: {
                responsive: true,
                backgroundColor: "#4e73df",
                barThickness: 35
            },
            cur_range: this.range,
            vselect_opts: [
                { label: 'Weekly', rangeVal: "week" },
                { label: 'Monthly', rangeVal: "month" },
                { label: 'Quarterly', rangeVal: "quarter" },
                { label: 'Yearly', rangeVal: "year" }
            ],
            // vselect_opts: [
            //     "week", "month", "quarter", "year"
            // ]
        }
    },
    props: {
        range: {
            type: String,
            required: true,
            validator: function(value) {
                return ['week', 'month', 'quarter', 'year'].includes(value)
            }
        },
        stats: {
            type: [Object, null],
            required: true
        }
    },
    methods: {
        changeRange(x){
            this.chart_data = this.removeUncategorized();
        },
        removeUncategorized(){
            let chart_stats;
            if (typeof(this?.cur_range?.rangeVal) == "undefined"){
                chart_stats = this.stats[this.range];
            }
            else {
                chart_stats = this.stats[this.cur_range.rangeVal];
            }
            let sorted_stats = {};
            Object.keys(chart_stats).sort().forEach(function(key) {
                sorted_stats[key] = chart_stats[key];
            });
            delete sorted_stats.Uncategorized;
            let cat_labels = Object.keys(sorted_stats);
            let cat_values = Object.values(sorted_stats);
            let inv_cat_values = {data: cat_values.map(value => value * -1)};

            return {
                labels: cat_labels,
                datasets: [inv_cat_values]
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
