<template>
    <div class="transactionRow row pt-1 pb-1 align-items-center">
        <div class="col-2">{{ date }}</div>
        <div class="col-3 transactionRow-payee">{{ payee.name }}</div>
        <div class="col-4 transactionRow-desc">{{ desc }}</div>
        <div class="col-1">{{ amt }}</div>
        <div class="col-2 text-right p-0">
                <button>Edit</button>
                <button class="ml-2">Delete</button>
        </div>
    </div>
</template>

<script>
export default{
    props: ['date','payee','desc','amt'],
    data: function(){
        return {
            transaction: null
        }
    }
}
</script>

<style scoped>
.transactionRow{
    border-bottom: grey;
    border-bottom-width: thin;
    border-bottom-style: solid;
}
.transactionRow-desc, .transactionRow-payee{
    overflow: hidden;
    white-space: nowrap;
}
</style>