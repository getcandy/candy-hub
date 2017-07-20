<!--
<template>
    <div>
        <div class="form-group">

            <label :for="input.handle">{{ input.name }}</label>

            <div class="form-group">
                <candy-select :input="input" :product="product" v-model="inputValue" v-if="input.type == 'select'"></candy-select>
                <candy-textarea :input="input" :product="product" v-model="inputValue" v-else-if="input.type == 'textarea'"></candy-textarea>
                <candy-input :input="input" :product="product" v-model="inputValue" v-else></candy-input>
            </div>

        </div>
    </div>
</template>

<script>
    export default {

        props: {
            input: {
                type: Object
            },
            product: {
                type: Object
            }
        }

    }
</script>-->