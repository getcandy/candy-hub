<template>
    <div>
        <table class="table">
            <thead>
                <tr><slot name="cols"></slot></tr>
            </thead>
            <tbody>
                <slot></slot>
            </tbody>
            <tfoot>
                <slot name="foot"></slot>
            </tfoot>
        </table>
    </div>
</template>

<script>
    export default {

    }
</script>

<style scoped>

</style>