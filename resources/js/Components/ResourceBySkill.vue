<script setup>
import ModuleActivity from "./ModuleActivity.vue";
import draggable from 'vuedraggable'
import { computed, onMounted, ref } from "vue";
let props = defineProps( {
    resource: {
        type: Object,
    },
} );
let dragOptions = computed( () => ( {
    animation: 200, ghostClass: "ghost"
} ) );

let local_state = ref( props.resource );
</script>

<template>
    <module-activity label="Skillset" :model-value="local_state" @removeActivity="removeActivity"></module-activity>
</template>
        