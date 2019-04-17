<script>
    export default {

        props: {
            current: {
                type: Number,
                default: 1,
            },
            total: {
                type: Number,
                default: 1,
            },
            offset: {
                type: Number,
                default: 4
            }
        },
        computed: {
            isActived: function () {
                return this.current;
            },
            pagesNumber: function () {
                if (!this.total) {
                    return [];
                }
                var from = this.current - this.offset;

                if (from < 1) {
                    from = 1;
                }
                var to = from + (this.offset * 2);
                if (to >= this.total) {
                    to = this.total;
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
                this.$emit('change', page);
            }
        }
    }
</script>

<template>
    <div>
        <nav aria-label="Page navigation">
            <ul class="pagination" v-if="current && total">
                <li v-if="current !== 1">
                    <a href="#" aria-label="First page" data-toggle="tooltip" data-placement="top" data-original-title="First page" title="First page" @click.prevent="changePage(1)">
                        <span aria-hidden="true"><i class="fa fa-angle-double-left" aria-hidden="true"></i></span>
                    </a>
                </li>
                <li v-if="current > 1">
                    <a href="#" aria-label="Previous" data-toggle="tooltip" data-placement="top" data-original-title="Previous page" title="Previous page" @click.prevent="changePage(current - 1)">
                        <span aria-hidden="true"><i class="fa fa-angle-left" aria-hidden="true"></i></span>
                    </a>
                </li>
                <li v-for="page in pagesNumber" v-bind:class="[ page == isActived ? 'active' : '']">
                    <a href="#" @click.prevent="changePage(page)">{{ page }}</a>
                </li>
                <li v-if="current < total">
                    <a href="#" aria-label="Next" data-toggle="tooltip" data-placement="top" data-original-title="Next page" title="Next page" @click.prevent="changePage(current + 1)">
                        <span aria-hidden="true"><i class="fa fa-angle-right" aria-hidden="true"></i></span>
                    </a>
                </li>
                <li v-if="current !== total">
                    <a href="#" aria-label="Last page" data-toggle="tooltip" data-placement="top" data-original-title="Last page" title="Last page" @click.prevent="changePage(total)">
                        <span aria-hidden="true"><i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</template>