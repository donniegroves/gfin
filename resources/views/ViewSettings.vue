<template>
    <div>
        ViewSettings.vue
    </div>
    <v-select :options="options"></v-select>
</template>

<script>
export default{
    data: function(){
        return {
            author: 'Donnie Groves',
            options: [
                'foo',
                'bar',
                'baz'
            ]
        }
    }
}
</script>

<style scoped>
</style>