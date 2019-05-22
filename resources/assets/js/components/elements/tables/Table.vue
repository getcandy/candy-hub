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
                <tr v-if="empty">
                    <td colspan="50" class="text-muted text-center">No results</td>
                </tr>
                <slot name="foot"></slot>
            </tfoot>
        </table>
    </div>
</template>

<script>
    export default {
        props: {
            empty: {
                default: true,
            }
        }
    }
</script>

<style scoped>

</style>