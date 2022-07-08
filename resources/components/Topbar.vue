<template>
    <div class="topbar"><code>TopBar</code></div>
</template>

<script>
    export default {
        data: function(){
            return {
                notifs_quant: 0,
                logged_in_user: null
            }
        },
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