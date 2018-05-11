<script>
    export default {
        data() {
            return {
                defCols: ['Label', 'Value'],
                defRows: [],
                sortableOptions: {
                    onEnd: this.reorder,
                    filter: '.disabled',
                    handle: '.handle',
                    animation: 150
                }
            }
        },
        props: {
            columns: {
                type: Array,
                default() {
                    return [];
                }
            },
            rows: {
                type: Array,
                default() {
                    return [];
                }
            }
        },
        mounted() {
            if (this.columns.length) {
                this.defCols = this.columns;
            }
            if (this.rows.length) {
                this.defRows = this.rows;
            }
        },
        methods: {
            addRow() {
                let newRow = {
                    id: this.makeId()
                };
                _.each(this.defCols, (col, index) => {
                    this.$set(newRow, this.sanitize(col), '');
                });
                this.defRows.push(newRow);
            },
            reorder ({oldIndex, newIndex}) {
                const movedItem = this.defRows.splice(oldIndex, 1)[0];
                this.defRows.splice(newIndex, 0, movedItem);
            },
            makeId() {
                var text = "";
                var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

                for (var i = 0; i < 5; i++)
                    text += possible.charAt(Math.floor(Math.random() * possible.length));

                return text;
            },
            sanitize(word) {
                return word.toString().toLowerCase()
                .replace(/\s+/g, '-')           // Replace spaces with -
                .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                .replace(/^-+/, '')             // Trim - from start of text
                .replace(/-+$/, '');
            },
            remove(index) {
                this.defRows.splice(index, 1);
            }
        }
    }
</script>

<template>
    <div>
        <table class="table">
            <thead>
                <tr>
                    <th width="5%"></th>
                    <th :width="90 / defCols.length" v-for="col in defCols">{{ col }}</th>
                    <th width="5%"></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td :colspan="defCols.length + 2">
                        <button class="add-row-btn" @click="addRow">Add row</button>
                    </td>
                </tr>
            </tfoot>
            <tbody  v-sortable="sortableOptions">
                <tr v-for="(row, index) in defRows" :key="row.id">
                    <td class="handle">
                        <svg width="13px" viewBox="0 0 13 19" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Artboard" fill="#D8D8D8">
                                    <rect id="Rectangle" x="2" y="2" width="3" height="3"></rect>
                                    <rect id="Rectangle-Copy-2" x="2" y="8" width="3" height="3"></rect>
                                    <rect id="Rectangle-Copy-4" x="2" y="14" width="3" height="3"></rect>
                                    <rect id="Rectangle-Copy-5" x="8" y="14" width="3" height="3"></rect>
                                    <rect id="Rectangle-Copy" x="8" y="2" width="3" height="3"></rect>
                                    <rect id="Rectangle-Copy-3" x="8" y="8" width="3" height="3"></rect>
                                </g>
                            </g>
                        </svg>
                    </td>
                    <td v-for="col in defCols">
                        <input type="text" class="form-control" v-model="row[sanitize(col)]" >
                    </td>
                    <td width="2%">
                        <button class="btn btn-action" @click="remove(index)"><fa icon="trash-alt"></fa></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<style lang="scss" scoped>
    tfoot {
        > tr > td {
            padding:0;
        }
    }
    .add-row-btn {
        background-color:#f5f5f5;
        padding:1em 0;
        border:none;
        color:#A5A4A4;
        text-transform:uppercase;
        font-size:.875em;
        font-weight:600;
        display:block;
        width:100%;
        &:hover {
            color:#686868;
        }
    }
</style>
