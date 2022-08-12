<template>
    <ol>
        <li
            class="calendar-day"
            :class="{
            'calendar-day--not-current': !day.isCurrentMonth,
            'calendar-day--today': isToday
            }"
        >
            <span>{{ label }}</span> <br />
            <div v-if="cat_totals" v-for="(amt, cat_label) in cat_totals" class="text-right">
                <div class="cat_line text-xs">
                    <span class="cat_name"><strong>{{ cat_label }}: </strong></span>
                    <span class="amt">{{ amt }}</span>
                </div>
            </div>
        </li>
    </ol>
</template>

<script>
import dayjs from "dayjs";

export default {
    name: "CalendarMonthDayItem",

    props: {
        day: {
            type: Object,
            required: true
        },

        isCurrentMonth: {
            type: Boolean,
            default: false
        },

        isToday: {
            type: Boolean,
            default: false
        },

        trans: {
            type: Object,
            required: true
        },
        cats: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            filtered_trans: [],
            cat_totals: null
        }
    },
    mounted(){
        this.setFilteredTrans();
        this.setCategoryTotals();
    },
    methods: {
        setFilteredTrans(){
            let filter_result = this.trans.filter(tran => tran.trans_date == this.day.date);
            this.filtered_trans = filter_result;
        },
        setCategoryTotals(){
            let final_obj = {};
            this.filtered_trans.forEach((tran)=>{
                let amt = parseInt(tran.new_amt ?? tran.orig_amt);
                let cat_id = tran.category_id;
                let cat_name = this.getCatNameById(cat_id);
                if (final_obj[cat_name] == undefined){
                    final_obj[cat_name] = amt;
                }
                else {
                    final_obj[cat_name] += amt;
                }
                this.cat_totals = final_obj;
            });
        },
        getCatNameById(cat_id){
            let found_name = false;
            this.cats.forEach((cat) => {
                if (cat_id == cat.id){
                    found_name = cat.name;
                }
            });
            return found_name ? found_name : 'Uncategorized';
        }
    },
    computed: {
        label() {
            return dayjs(this.day.date).format("D");
        }
    }
};
</script>

<style scoped>
.calendar-day {
  position: relative;
  min-height: 100px;
  font-size: 16px;
  background-color: #fff;
  color: #3e4e63;
  padding: 5px;
  border:#4e72df3a solid 1px;
}

.calendar-day > span {
  display: flex;
  justify-content: center;
  align-items: center;
  position: absolute;
  right: 2px;
  width: var(--day-label-size);
  height: var(--day-label-size);
}

.calendar-day--not-current {
  background-color: #e4e9f0;
  color: #b5c0cd;
}

.calendar-day--today {
  padding-top: 4px;
}

.calendar-day--today > span {
  color: #fff;
  border-radius: 9999px;
  background-color: #3e4e63;
}
</style>
