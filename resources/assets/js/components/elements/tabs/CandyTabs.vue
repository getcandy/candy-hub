<template>
    <div>
        <ul class="nav nav-tabs sub-nav-tabs" v-if="!nested" role="tablist">
            <li role="presentation" :class="{ 'active' : tab.isActive}" v-for="tab in tabs">
                <a :href="tab.href" @click="selectTab(tab)" :aria-controls="tab.href" role="tab">{{ tab.name }}</a>
            </li>
        </ul>
        <div :class="{'tab-content' : true, 'sub-content section block' : nested }">
            <slot></slot>
        </div>
        <ul class="nav nav-tabs secondary sub-nav" v-if="nested" role="tablist">
            <li role="presentation" :class="{ 'active' : tab.isActive}" v-for="tab in tabs">
                <a :href="tab.href" @click="selectTab(tab)" :aria-controls="tab.href" role="tab">{{ tab.name }} <span class="badge">{{ tab.badge }}</span></a>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                tabs: []
            }
        },
        props: {
            nested: {
                default: false
            },
            badge: ''
        },
        methods: {
            selectTab(selectedTab) {
                this.tabs.forEach(tab => {
                    if (tab.name == selectedTab.name) {
                        CandyEvent.$emit('current-tab', tab);
                        tab.isActive = true;
                    } else {
                        tab.isActive = false;
                    }
                });
            },
            selectTabByName(name) {
                this.tabs.forEach(tab => {
                    if (tab.name == name) {
                        this.selectTab(tab);
                    }
                });
            },
            selectTabByHref(href) {
                this.tabs.forEach(tab => {
                    if (tab.getHref() == href) {
                        this.selectTab(tab);
                    }
                })
            }
        },
        mounted() {
            CandyEvent.$on('select-tab', tab => {
                this.selectTabByName(tab);
            });

            var tab = window.location.hash;

            if (tab) {
                this.selectTabByHref(tab);
            }
        },
        computed: {
            filteredTabs() {
                return this.$children;
            }
        },
        created() {
            this.tabs = this.$children;
        }
    }
</script>
