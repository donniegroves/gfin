<template>
    <header>
        <h1>{{title}}<sup>2</sup></h1>
    </header>
</template>

<script>
    export default {
        props: {
            title: {
                type: String,
                default: 'GFin',
            }
        }
    }
</script>

<style scoped>

</style>