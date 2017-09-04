<template>

    <li class="nestable-item" :data-id="category.id" :data-name="category.attribute_data.name.ecommerce.gb">

        <div class="nestable-handle">
            <i class="fa fa-arrows nestable-icon"></i>
            <img class="nestable-image" src="http://via.placeholder.com/45x45">
            {{ category.attribute_data.name.ecommerce.gb }} ({{ category.children.length }})
        </div>

        <ol class="nestable-list" v-if="category.children.length > 0">
            <candy-category-children v-for="child in category.children" :category="child.data" :key="child.data.id"></candy-category-children>
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
        }
    }
</script>