<template>
    <small v-show="errors_form.has(attribute_name)" class="text-danger">{{ errors_form.first(attribute_name) }}</small>
</template>

<script>
    export default {
        props: ['attribute_name', 'errors_form']
    }
</script>