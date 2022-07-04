<template>
    <div class="transactionRow">
        <div class="field">{{ date }}</div>
        <div class="field">{{ payee.name }}</div>
        <div class="field">{{ desc }}</div>
        <div class="field">{{ amt }}</div>
        <div class="field col-12"><button>Edit</button></div>
        <div class="field"><button>Delete</button></div>
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
    display: flex;
    flex-direction: row;
    justify-content: flex-start;
    align-items: center;
    gap: 10px;
    height: 25px;
}
</style>