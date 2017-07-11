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
                <a :href="tab.href" @click="selectTab(tab)" :aria-controls="tab.href" role="tab">{{ tab.name }}</a>
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
            }
        },
        methods: {
            selectTab(selectedTab) {
                this.tabs.forEach(tab => {
                    if (tab.name == selectedTab.name) {
                        tab.isActive = true;
                        Event.$emit('current-tab', tab);
                    } else {
                        tab.isActive = false;
                    }
                });
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