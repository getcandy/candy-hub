<template>

    <li class="nestable-item" :id="category.id" :data-id="category.id" :data-attribute_data="getAttributeData(category.attribute_data)">
        <div class="nestable-handle">
            <i class="fa fa-arrows nestable-icon"></i>
            <img class="nestable-image" src="http://via.placeholder.com/45x45">
            <span>{{ category.attribute_data.name.ecommerce.gb }}</span>
        </div>

        <ol class="nestable-list" v-if="category.children && category.children.length > 0">
            <candy-category v-for="child in category.children" :category="child.data" :key="child.data.id"></candy-category>
        </ol>

    </li>

</template>

<script>
    export default {
        props: {
            children: {
                type: Array
            },
            category: {
                type: Object
            }
        },
        methods: {
            getAttributeData(data){
                return JSON.stringify(data);
            }
        }
    }
</script>