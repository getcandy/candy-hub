<template>
    <div>
        <template v-if="money">
            &pound;{{ top.money() }}<br>
        </template>
        <template v-else>
            {{ top }}
        </template>
        <section style="margin-top:10px;font-size:.75em">
            <template v-if="top - bottom >= 0">
                <span class="text-success"><sup><fa icon="caret-up"></fa></sup>
                    <template v-if="money">
                        &pound;{{ (top - bottom).money() }}
                    </template>
                    <template v-else>
                        {{ (top - bottom) }}
                    </template>
                </span>
            </template>
            <template v-else>
                <span class="text-danger"><sup><fa icon="caret-down"></fa></sup>
                        <template v-if="money">
                            &pound;{{ (top - bottom).money() }}
                        </template>
                        <template v-else>
                            {{ (top - bottom) }}
                        </template>
                </span>
            </template>
        </section>
    </div>
</template>

<script>
    export default {
        props: ['top', 'bottom', 'money']
    }
</script>

<style scoped>

</style>