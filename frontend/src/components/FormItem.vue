<template>
    <div class="test">
        <h1>{{ msg }}</h1>
    </div>

    <InputItem />
</template>






<script>
    export default {
        name: 'FormItem',
        props: {
            msg: String
        }
    }
</script>