<script setup>
import ModuleActivity from "./ModuleActivity.vue";
import { ref } from "vue";
let props = defineProps( {
    modelValue: {
        type: Object,
        required: true,
    },
} );

let local_state = ref( props.modelValue );
</script>

<template>
    <div class="form-floating mb-3">
        <input type="text" class="form-control" v-model="local_state.title">
        <label>Module Title</label>
    </div>
    <module-activity :model-value="local_state.module_activities"></module-activity>
    <div class="d-flex justify-content-center">
        <button @click.prevent="$emit('removeModule', modelValue.id)" class="btn text-danger"><i
                class="fa fa-times me-1"></i> Remove Module</button>
    </div>
</template>