<template>
    <li class="nestable-item" :id="category.id" :data-id="category.id" :data-attribute_data="getAttributeData(category.attribute_data)">

        <div class="nestable-handle">
            <i class="fa fa-arrows nestable-icon"></i>
        </div>

        <div class="nestable-content">
            <img class="nestable-image" src="/images/placeholder/no-image.svg" height="41">
            <a class="category-name" href="#">{{ category.attribute_data.name.ecommerce|t }}</a>
            <a data-toggle="modal" data-target="#createCategoryModal" class="btn btn-default" @click="modalParentID(category.id)"><i class="fa fa-plus"></i> Create Subcategory</a>
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
            },
            modalParentID(parentID){
                window.modalParentID = parentID;
            }
        }
    }
</script>