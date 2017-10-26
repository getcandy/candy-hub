<script>
    export default {

        props: {
            pagination:{
                type: Object,
            },
            offset: {
                type: Number,
                default: 4
            }
        },
        mounted() {
        },
        computed: {
            isActived: function () {
                return this.pagination.current_page;
            },
            pagesNumber: function () {
                if (!this.pagination.total_pages) {
                    return [];
                }
                var from = this.pagination.current_page - this.offset;
                if (from < 1) {
                    from = 1;
                }
                var to = from + (this.offset * 2);
                if (to >= this.pagination.total_pages) {
                    to = this.pagination.total_pages;
                }
                var pagesArray = [];
                while (from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }
        },
        methods: {
            changePage: function (page) {
                // Need to return page number then use watch to watch that change and fire off new ajax request
                this.pagination.current_page = page;
                this.$emit('change', page);
            }
        }
    }
</script>

<template>
    <div>
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li v-if="pagination.current_page !== 1">
                    <a href="#" aria-label="First page" data-toggle="tooltip" data-placement="top" data-original-title="First page" title="First page" @click.prevent="changePage(pagination.current_page = 1)">
                        <span aria-hidden="true"><i class="fa fa-angle-double-left" aria-hidden="true"></i></span>
                    </a>
                </li>
                <li v-if="pagination.current_page > 1">
                    <a href="#" aria-label="Previous" data-toggle="tooltip" data-placement="top" data-original-title="Previous page" title="Previous page" @click.prevent="changePage(pagination.current_page - 1)">
                        <span aria-hidden="true"><i class="fa fa-angle-left" aria-hidden="true"></i></span>
                    </a>
                </li>
                <li v-for="page in pagesNumber" v-bind:class="[ page == isActived ? 'active' : '']">
                    <a href="#" @click.prevent="changePage(page)">{{ page }}</a>
                </li>
                <li v-if="pagination.current_page < pagination.total_pages">
                    <a href="#" aria-label="Next" data-toggle="tooltip" data-placement="top" data-original-title="Next page" title="Next page" @click.prevent="changePage(pagination.current_page + 1)">
                        <span aria-hidden="true"><i class="fa fa-angle-right" aria-hidden="true"></i></span>
                    </a>
                </li>
                <li v-if="pagination.current_page !== pagination.total_pages">
                    <a href="#" aria-label="Last page" data-toggle="tooltip" data-placement="top" data-original-title="Last page" title="Last page" @click.prevent="changePage(pagination.current_page = pagination.total_pages)">
                        <span aria-hidden="true"><i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</template>