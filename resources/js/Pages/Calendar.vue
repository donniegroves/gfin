<template>
    <GFinLayout v-if="budget_stgs && all_trans && all_categories">
        <div class="calendar-month">
            <div class="calendar-month-header">
                <CalendarDateIndicator :selected-date="selectedDate" class="calendar-month-header-selected-month" />

                <CalendarDateSelector :current-date="today" :selected-date="selectedDate" @dateSelected="selectDate" />
            </div>
            <CalendarWeekdays />

            <ol class="days-grid">
                <CalendarMonthDayItem 
                    v-for="day in days" 
                    :key="day.date" :day="day" 
                    :is-today="day.date === today"
                    :budget_stgs="budget_stgs"
                    :trans="all_trans" 
                    :cats="all_categories"
                />
            </ol>
        </div>
    </GFinLayout>
</template>

<script>
    import GFinLayout from "@/Layouts/GFinLayout.vue";
    import axios from "axios";
    import dayjs from "dayjs";
    import weekday from "dayjs/plugin/weekday";
    import weekOfYear from "dayjs/plugin/weekOfYear";
    import CalendarMonthDayItem from "@/Components/CalendarMonthDayItem.vue"
    import CalendarDateIndicator from "@/Components/CalendarDateIndicator.vue";
    import CalendarDateSelector from "@/Components/CalendarDateSelector.vue";
    import CalendarWeekdays from "@/Components/CalendarWeekdays.vue";

    dayjs.extend(weekday);
    dayjs.extend(weekOfYear);

    export default {
        name: "CalendarMonth",

        components: {
            GFinLayout,
            CalendarMonthDayItem,
            CalendarDateIndicator,
            CalendarDateSelector,
            CalendarWeekdays
        },

        data() {
            return {
                selectedDate: dayjs(),
                budget_stgs: null,
                all_trans: null,
                all_categories: null
            };
        },

        computed: {
            days() {
                return [
                    ...this.previousMonthDays,
                    ...this.currentMonthDays,
                    ...this.nextMonthDays
                ];
            },

            today() {
                return dayjs().format("YYYY-MM-DD");
            },

            month() {
                return Number(this.selectedDate.format("M"));
            },

            year() {
                return Number(this.selectedDate.format("YYYY"));
            },

            numberOfDaysInMonth() {
                return dayjs(this.selectedDate).daysInMonth();
            },

            currentMonthDays() {
                return [...Array(this.numberOfDaysInMonth)].map((day, index) => {
                    return {
                        date: dayjs(`${this.year}-${this.month}-${index + 1}`).format(
                            "YYYY-MM-DD"
                        ),
                        isCurrentMonth: true
                    };
                });
            },

            previousMonthDays() {
                const firstDayOfTheMonthWeekday = this.getWeekday(
                    this.currentMonthDays[0].date
                );
                const previousMonth = dayjs(`${this.year}-${this.month}-01`).subtract(
                    1,
                    "month"
                );

                // Cover first day of the month being sunday (firstDayOfTheMonthWeekday === 0)
                const visibleNumberOfDaysFromPreviousMonth = firstDayOfTheMonthWeekday ?
                    firstDayOfTheMonthWeekday - 0 :
                    6;

                const previousMonthLastMondayDayOfMonth = dayjs(
                        this.currentMonthDays[0].date
                    )
                    .subtract(visibleNumberOfDaysFromPreviousMonth, "day")
                    .date();

                return [...Array(visibleNumberOfDaysFromPreviousMonth)].map(
                    (day, index) => {
                        return {
                            date: dayjs(
                                `${previousMonth.year()}-${previousMonth.month() +
                                1}-${previousMonthLastMondayDayOfMonth + index}`
                            ).format("YYYY-MM-DD"),
                            isCurrentMonth: false
                        };
                    }
                );
            },

            nextMonthDays() {
                const lastDayOfTheMonthWeekday = this.getWeekday(
                    `${this.year}-${this.month}-${this.currentMonthDays.length}`
                );

                const nextMonth = dayjs(`${this.year}-${this.month}-01`).add(1, "month");

                const visibleNumberOfDaysFromNextMonth = lastDayOfTheMonthWeekday ?
                    6 - lastDayOfTheMonthWeekday :
                    lastDayOfTheMonthWeekday;

                return [...Array(visibleNumberOfDaysFromNextMonth)].map((day, index) => {
                    return {
                        date: dayjs(
                            `${nextMonth.year()}-${nextMonth.month() + 1}-${index + 1}`
                        ).format("YYYY-MM-DD"),
                        isCurrentMonth: false
                    };
                });
            }
        },
        created(){
            this.getBudgets();
            this.getTransactions();
            this.getCategories();
        },

        methods: {
            getWeekday(date) {
                return dayjs(date).weekday();
            },

            selectDate(newSelectedDate) {
                this.selectedDate = newSelectedDate;
            },
            async getBudgets(){
                const response = await axios.get('reqs/settings', {});
                if (response.status == 200){
                    console.log('received budget settings.');
                    this.budget_stgs = {
                        daily_exp_budget:   response.data.daily_exp_budget,
                        weekly_exp_budget:  response.data.weekly_exp_budget
                    };
                }
            },
            async getTransactions(){
                const response = await axios.get('reqs/transactions?range=all', {});
                if (response.status == 200){
                    console.log('received ' + response.data.length + ' transactions.');
                    this.all_trans = response.data;
                }
            },
            async getCategories(){
                const response = await axios.get('reqs/categories', {});
                if (response.status == 200){
                    console.log('received ' + Object.keys(response.data).length + ' categories.');
                    this.all_categories = response.data;
                }
            }
        }
    };

</script>

<style scoped>
    body {
        font-family: sans-serif;
        font-weight: 100;
        --grid-gap: 1px;
        --day-label-size: 20px;
    }

    ol,
    li {
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .calendar-month-header {
        display: flex;
        justify-content: space-between;
        background-color: #fff;
        padding: 10px;
    }

    .calendar-month {
        position: relative;
        background-color: #cfd7e3;
        border: solid 1px #b5c0cd;
    }

    .day-of-week {
        color: #3e4e63;
        font-size: 18px;
        background-color: #fff;
        padding-bottom: 5px;
        padding-top: 10px;
    }

    .day-of-week,
    .days-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
    }

    .day-of-week>* {
        text-align: right;
        padding-right: 5px;
    }

    .days-grid {
        height: 100%;
        position: relative;
        grid-column-gap: var(--grid-gap);
        grid-row-gap: var(--grid-gap);
        border-top: solid 1px #cfd7e3;
    }

</style>
